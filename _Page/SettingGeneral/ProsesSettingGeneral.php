
<?php
    ob_start();

    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    date_default_timezone_set('Asia/Jakarta');

    header('Content-Type: application/json; charset=utf-8');

    // ==========================================
    // DEBUG CONFIGURATION
    // ==========================================
    $DEBUG_MODE = isset($_POST['debug']) && $_POST['debug'] === '1';

    /**
     * Tulis log debugging ke error_log dengan timestamp dan konteks
     */
    function writeDebugLog(string $step, string $message, array $context = []): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $log = sprintf(
            "[%s] [SettingGeneral] [STEP: %s] %s",
            $timestamp,
            $step,
            $message
        );

        if (!empty($context)) {
            $log .= " | Context: " . json_encode($context, JSON_UNESCAPED_UNICODE);
        }

        error_log($log);
    }

    /**
     * Kirim response JSON ke client
     */
    function JsonResponse($status, $message, $debugInfo = null)
    {
        if (ob_get_length()) {
            ob_clean();
        }

        $response = [
            'status' => $status,
            'message' => $message
        ];

        if ($debugInfo !== null) {
            $response['debug'] = $debugInfo;
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    try {
        $currentStep = 'INIT';

        // ==========================================
        // 1. VALIDASI SESSION
        // ==========================================
        $currentStep = 'VALIDASI_SESSION';
        writeDebugLog($currentStep, 'Memulai validasi session');

        if (empty($SessionIdAkses)) {
            writeDebugLog($currentStep, 'SessionIdAkses kosong');
            JsonResponse('failed', 'Sesi akses telah berakhir. Silakan login kembali.');
        }

        writeDebugLog($currentStep, 'Session valid');

        // ==========================================
        // 2. TANGKAP DATA FORM
        // ==========================================
        $currentStep = 'TANGKAP_DATA_FORM';
        writeDebugLog($currentStep, 'Mengambil data dari $_POST');

        $title_page     = trim($_POST['title_page'] ?? '');
        $deskripsi      = trim($_POST['deskripsi'] ?? '');
        $alamat_bisnis  = trim($_POST['alamat_bisnis'] ?? '');
        $email_bisnis   = trim($_POST['email_bisnis'] ?? '');
        $telepon_bisnis = trim($_POST['telepon_bisnis'] ?? '');
        $base_url       = trim($_POST['base_url'] ?? '');

        writeDebugLog($currentStep, 'Data form berhasil ditangkap');

        // ==========================================
        // 3. VALIDASI INPUT
        // ==========================================
        $currentStep = 'VALIDASI_INPUT';
        writeDebugLog($currentStep, 'Memulai validasi input');

        if ($title_page === '') {
            writeDebugLog($currentStep, 'Validasi gagal: title_page kosong');
            JsonResponse('failed', 'Nama koperasi tidak boleh kosong.');
        }

        if (function_exists('mb_strlen')) {
            $jumlahKarakter = mb_strlen($title_page, 'UTF-8');
        } else {
            $jumlahKarakter = strlen($title_page);
        }

        if ($jumlahKarakter > 20) {
            writeDebugLog($currentStep, 'Validasi gagal: title_page melebihi 20 karakter', ['jumlahKarakter' => $jumlahKarakter]);
            JsonResponse('failed', 'Nama koperasi maksimal 20 karakter.');
        }

        if ($deskripsi === '') {
            writeDebugLog($currentStep, 'Validasi gagal: deskripsi kosong');
            JsonResponse('failed', 'Deskripsi koperasi tidak boleh kosong.');
        }

        if ($alamat_bisnis === '') {
            writeDebugLog($currentStep, 'Validasi gagal: alamat_bisnis kosong');
            JsonResponse('failed', 'Alamat operasional tidak boleh kosong.');
        }

        if ($email_bisnis === '') {
            writeDebugLog($currentStep, 'Validasi gagal: email_bisnis kosong');
            JsonResponse('failed', 'Email perusahaan tidak boleh kosong.');
        }

        if (!filter_var($email_bisnis, FILTER_VALIDATE_EMAIL)) {
            writeDebugLog($currentStep, 'Validasi gagal: format email_bisnis tidak valid', ['email_bisnis' => $email_bisnis]);
            JsonResponse('failed', 'Format email perusahaan tidak valid.');
        }

        if ($telepon_bisnis === '') {
            writeDebugLog($currentStep, 'Validasi gagal: telepon_bisnis kosong');
            JsonResponse('failed', 'Kontak WA/Telepon tidak boleh kosong.');
        }

        if ($base_url === '') {
            writeDebugLog($currentStep, 'Validasi gagal: base_url kosong');
            JsonResponse('failed', 'Base URL tidak boleh kosong.');
        }

        $urlParts = parse_url($base_url);

        if (
            filter_var($base_url, FILTER_VALIDATE_URL) === false ||
            !is_array($urlParts) ||
            !isset($urlParts['scheme'], $urlParts['host']) ||
            !in_array(strtolower($urlParts['scheme']), ['http', 'https'], true)
        ) {
            writeDebugLog($currentStep, 'Validasi gagal: format base_url tidak valid', ['base_url' => $base_url, 'urlParts' => $urlParts]);
            JsonResponse('failed', 'Format Base URL tidak valid. Gunakan HTTP atau HTTPS.');
        }

        writeDebugLog($currentStep, 'Semua validasi input lulus');

        // ==========================================
        // 4. UPSERT DATABASE (INSERT jika belum ada, UPDATE jika sudah ada)
        // ==========================================
        // 4. UPSERT DATABASE (INSERT jika belum ada, UPDATE jika sudah ada)
        // ==========================================
        // 4a. CEK RECORD
        $currentStep = 'UPSERT_CHECK';
        writeDebugLog($currentStep, 'Memulai pengecekan keberadaan record');

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $cekSql = "SELECT id_setting_general FROM setting_general WHERE id_setting_general = '1' LIMIT 1";
        writeDebugLog($currentStep, 'Menjalankan query cek record', ['sql' => $cekSql]);

        $cekResult = mysqli_query($Conn, $cekSql);
        $recordExists = (mysqli_num_rows($cekResult) > 0);

        writeDebugLog($currentStep, 'Hasil cek record', [
            'recordExists' => $recordExists ? 'true' : 'false',
            'num_rows' => mysqli_num_rows($cekResult)
        ]);

        if ($recordExists) {
            // 4b. UPDATE
            $currentStep = 'UPSERT_UPDATE';
            writeDebugLog($currentStep, 'Record sudah ada, memulai operasi UPDATE');

            $sql = "UPDATE setting_general SET
                        title_page = ?,
                        deskripsi = ?,
                        alamat_bisnis = ?,
                        email_bisnis = ?,
                        telepon_bisnis = ?,
                        base_url = ?
                    WHERE id_setting_general = '1'";

            writeDebugLog($currentStep, 'Menjalankan UPDATE statement', ['sql' => $sql]);

            $stmt = mysqli_prepare($Conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "ssssss",
                $title_page,
                $deskripsi,
                $alamat_bisnis,
                $email_bisnis,
                $telepon_bisnis,
                $base_url
            );

            $executeResult = mysqli_stmt_execute($stmt);
            $affectedRows = mysqli_stmt_affected_rows($stmt);

            writeDebugLog($currentStep, 'UPDATE dieksekusi', [
                'execute_result' => $executeResult ? 'true' : 'false',
                'affected_rows' => $affectedRows
            ]);

            mysqli_stmt_close($stmt);

            writeDebugLog($currentStep, 'Operasi UPDATE selesai');
        } else {
            // 4c. INSERT
            $currentStep = 'UPSERT_INSERT';
            writeDebugLog($currentStep, 'Record belum ada, memulai operasi INSERT');
            $settingId = '1';

            $sql = "INSERT INTO setting_general (
                        id_setting_general,
                        title_page,
                        deskripsi,
                        alamat_bisnis,
                        email_bisnis,
                        telepon_bisnis,
                        base_url
                    ) VALUES (?, ?, ?, ?, ?, ?, ?)";

            writeDebugLog($currentStep, 'Menjalankan INSERT statement', ['sql' => $sql]);

            $stmt = mysqli_prepare($Conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "sssssss",
                $settingId,
                $title_page,
                $deskripsi,
                $alamat_bisnis,
                $email_bisnis,
                $telepon_bisnis,
                $base_url
            );

            $executeResult = mysqli_stmt_execute($stmt);
            $affectedRows = mysqli_stmt_affected_rows($stmt);
            $insertId = mysqli_stmt_insert_id($stmt);

            writeDebugLog($currentStep, 'INSERT dieksekusi', [
                'execute_result' => $executeResult ? 'true' : 'false',
                'affected_rows' => $affectedRows,
                'insert_id' => $insertId
            ]);

            mysqli_stmt_close($stmt);

            writeDebugLog($currentStep, 'Operasi INSERT selesai');
        }

        writeDebugLog('UPSERT_DATABASE', 'Semua operasi database selesai');

        // ==========================================
        // 5. RESPONSE SUCCESS
        // ==========================================
        $currentStep = 'RESPONSE_SUCCESS';
        writeDebugLog($currentStep, 'Mengirim response sukses ke client');

        $_SESSION['NotifikasiSwal'] = 'Simpan Setting General Berhasil';

        $debugInfo = null;
        if ($DEBUG_MODE) {
            $databaseStep = $recordExists ? 'UPSERT_UPDATE' : 'UPSERT_INSERT';
            $debugInfo = [
                'steps_completed' => [
                    'VALIDASI_SESSION',
                    'TANGKAP_DATA_FORM',
                    'VALIDASI_INPUT',
                    'UPSERT_CHECK',
                    $databaseStep,
                    'RESPONSE_SUCCESS'
                ],
                'record_exists' => $recordExists,
                'timestamp' => date('Y-m-d H:i:s')
            ];
        }

        JsonResponse('success', 'Pengaturan koperasi berhasil disimpan.', $debugInfo);

    } catch (Throwable $e) {

        // Catat error lengkap ke error_log untuk debugging server
        $trace = $e->getTrace();
        $traceSummary = [];

        foreach ($trace as $frame) {
            $traceSummary[] = sprintf(
                "%s:%s in %s%s%s",
                $frame['file'] ?? 'unknown',
                $frame['line'] ?? 'unknown',
                $frame['class'] ?? '',
                $frame['type'] ?? '',
                $frame['function'] ?? 'unknown'
            );
        }

        error_log(
            sprintf(
                "[%s] [SettingGeneral] [FATAL ERROR] Step: %s | Message: %s | File: %s | Line: %s | Trace: %s",
                date('Y-m-d H:i:s'),
                $currentStep ?? 'UNKNOWN',
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                implode(' -> ', array_slice($traceSummary, 0, 10))
            )
        );

        // Siapkan debug info untuk client (hanya jika DEBUG_MODE aktif)
        $debugInfo = null;
        if ($DEBUG_MODE) {
            $debugInfo = [
                'step_failed' => $currentStep ?? 'UNKNOWN',
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => array_slice($traceSummary, 0, 10)
            ];
        }

        JsonResponse(
            'failed',
            'Terjadi kesalahan pada server saat menyimpan pengaturan.',
            $debugInfo
        );
    }
?>
