
<?php
    ob_start();

    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    date_default_timezone_set('Asia/Jakarta');

    header('Content-Type: application/json; charset=utf-8');

    function JsonResponse($status, $message)
    {
        if (ob_get_length()) {
            ob_clean();
        }

        echo json_encode([
            'status' => $status,
            'message' => $message
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    try {
        // ==========================================
        // 1. VALIDASI SESSION
        // ==========================================
        if (empty($SessionIdAkses)) {
            JsonResponse('failed', 'Sesi akses telah berakhir. Silakan login kembali.');
        }

        // ==========================================
        // 2. TANGKAP DATA FORM
        // ==========================================
        $title_page     = trim($_POST['title_page'] ?? '');
        $deskripsi      = trim($_POST['deskripsi'] ?? '');
        $alamat_bisnis  = trim($_POST['alamat_bisnis'] ?? '');
        $email_bisnis   = trim($_POST['email_bisnis'] ?? '');
        $telepon_bisnis = trim($_POST['telepon_bisnis'] ?? '');
        $base_url       = trim($_POST['base_url'] ?? '');

        // ==========================================
        // 3. VALIDASI INPUT
        // ==========================================
        if ($title_page === '') {
            JsonResponse('failed', 'Nama koperasi tidak boleh kosong.');
        }

        if (function_exists('mb_strlen')) {
            $jumlahKarakter = mb_strlen($title_page, 'UTF-8');
        } else {
            $jumlahKarakter = strlen($title_page);
        }

        if ($jumlahKarakter > 20) {
            JsonResponse('failed', 'Nama koperasi maksimal 20 karakter.');
        }

        if ($deskripsi === '') {
            JsonResponse('failed', 'Deskripsi koperasi tidak boleh kosong.');
        }

        if ($alamat_bisnis === '') {
            JsonResponse('failed', 'Alamat operasional tidak boleh kosong.');
        }

        if ($email_bisnis === '') {
            JsonResponse('failed', 'Email perusahaan tidak boleh kosong.');
        }

        if (!filter_var($email_bisnis, FILTER_VALIDATE_EMAIL)) {
            JsonResponse('failed', 'Format email perusahaan tidak valid.');
        }

        if ($telepon_bisnis === '') {
            JsonResponse('failed', 'Kontak WA/Telepon tidak boleh kosong.');
        }

        if ($base_url === '') {
            JsonResponse('failed', 'Base URL tidak boleh kosong.');
        }

        // Validasi URL HTTP/HTTPS
        $urlParts = parse_url($base_url);

        if (
            filter_var($base_url, FILTER_VALIDATE_URL) === false ||
            !is_array($urlParts) ||
            !isset($urlParts['scheme'], $urlParts['host']) ||
            !in_array(strtolower($urlParts['scheme']), ['http', 'https'], true)
        ) {
            JsonResponse('failed', 'Format Base URL tidak valid. Gunakan HTTP atau HTTPS.');
        }

        // ==========================================
        // 4. UPDATE DATABASE
        // ==========================================
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $sql = "UPDATE setting_general SET
                    title_page = ?,
                    deskripsi = ?,
                    alamat_bisnis = ?,
                    email_bisnis = ?,
                    telepon_bisnis = ?,
                    base_url = ?
                WHERE id_setting_general = '1'";

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

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // ==========================================
        // 5. RESPONSE SUCCESS
        // ==========================================
        $_SESSION['NotifikasiSwal'] = 'Simpan Setting General Berhasil';

        JsonResponse('success', 'Pengaturan koperasi berhasil disimpan.');

    } catch (Throwable $e) {

        // Catat error untuk debugging server
        error_log(
            '[SettingGeneral] ' . $e->getMessage()
        );

        JsonResponse(
            'failed',
            'Terjadi kesalahan pada server saat menyimpan pengaturan.'
        );
    }
?>
