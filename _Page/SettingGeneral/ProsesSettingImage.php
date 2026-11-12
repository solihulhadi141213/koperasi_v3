
<?php
    ob_start();

    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    date_default_timezone_set('Asia/Jakarta');

    header('Content-Type: application/json; charset=utf-8');

    // ==========================================
    // FUNCTION JSON RESPONSE
    // ==========================================
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

    // ==========================================
    // KONFIGURASI UPLOAD
    // ==========================================
    $UploadDirectory = __DIR__ . '/../../assets/img/Company/';
    $MaxFileSize = 2 * 1024 * 1024; // 2 MB

    $AllowedMimeTypes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif'
    ];

    $NewFilePath = null;

    // ==========================================
    // PROSES UTAMA
    // ==========================================
    try {

        // ==========================================
        // 1. VALIDASI SESSION
        // ==========================================
        if (empty($SessionIdAkses)) {
            JsonResponse('failed', 'Sesi akses telah berakhir. Silakan login kembali.');
        }

        // ==========================================
        // 2. VALIDASI REQUEST
        // ==========================================
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            JsonResponse('failed', 'Metode request tidak diizinkan.');
        }

        if (!isset($_FILES['logo'])) {
            JsonResponse('failed', 'File logo belum dipilih.');
        }

        $File = $_FILES['logo'];

        // ==========================================
        // 3. VALIDASI ERROR UPLOAD
        // ==========================================
        switch ($File['error']) {
            case UPLOAD_ERR_OK:
                break;

            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                JsonResponse('failed', 'Ukuran file melebihi batas maksimal upload server.');

            case UPLOAD_ERR_PARTIAL:
                JsonResponse('failed', 'File hanya berhasil diupload sebagian.');

            case UPLOAD_ERR_NO_FILE:
                JsonResponse('failed', 'Silakan pilih file logo terlebih dahulu.');

            case UPLOAD_ERR_NO_TMP_DIR:
                JsonResponse('failed', 'Direktori temporary upload tidak ditemukan.');

            case UPLOAD_ERR_CANT_WRITE:
                JsonResponse('failed', 'Server gagal menulis file upload.');

            case UPLOAD_ERR_EXTENSION:
                JsonResponse('failed', 'Upload file dihentikan oleh ekstensi PHP.');

            default:
                JsonResponse('failed', 'Terjadi kesalahan pada proses upload file.');
        }

        // ==========================================
        // 4. VALIDASI UKURAN FILE
        // ==========================================
        if ($File['size'] <= 0) {
            JsonResponse('failed', 'File logo kosong atau tidak valid.');
        }

        if ($File['size'] > $MaxFileSize) {
            JsonResponse('failed', 'Ukuran file logo maksimal 2 MB.');
        }

        // ==========================================
        // 5. VALIDASI FILE UPLOAD
        // ==========================================
        if (!is_uploaded_file($File['tmp_name'])) {
            JsonResponse('failed', 'File upload tidak valid.');
        }

        // ==========================================
        // 6. VALIDASI MIME TYPE
        // ==========================================
        $FileInfo = new finfo(FILEINFO_MIME_TYPE);
        $MimeType = $FileInfo->file($File['tmp_name']);

        if (!isset($AllowedMimeTypes[$MimeType])) {
            JsonResponse('failed', 'Format file hanya boleh JPG, JPEG, PNG, atau GIF.');
        }

        // ==========================================
        // 7. VALIDASI KONTEN GAMBAR
        // ==========================================
        $ImageInfo = getimagesize($File['tmp_name']);

        if ($ImageInfo === false) {
            JsonResponse('failed', 'File yang diupload bukan gambar yang valid.');
        }

        $AllowedImageTypes = [
            IMAGETYPE_JPEG,
            IMAGETYPE_PNG,
            IMAGETYPE_GIF
        ];

        if (!in_array($ImageInfo[2], $AllowedImageTypes, true)) {
            JsonResponse('failed', 'Jenis gambar tidak diizinkan.');
        }

        // ==========================================
        // 8. VALIDASI DIREKTORI
        // ==========================================
        if (!is_dir($UploadDirectory)) {
            JsonResponse('failed', 'Direktori penyimpanan logo tidak ditemukan.');
        }

        if (!is_writable($UploadDirectory)) {
            JsonResponse('failed', 'Direktori penyimpanan logo tidak memiliki izin tulis.');
        }

        // ==========================================
        // 9. GENERATE NAMA FILE
        // ==========================================
        $Extension = $AllowedMimeTypes[$MimeType];

        $NewFileName = bin2hex(random_bytes(16)) . '.' . $Extension;

        $NewFilePath = $UploadDirectory . $NewFileName;

        // ==========================================
        // 10. PINDAHKAN FILE
        // ==========================================
        if (!move_uploaded_file($File['tmp_name'], $NewFilePath)) {
            JsonResponse('failed', 'Gagal menyimpan file logo ke server.');
        }

        // ==========================================
        // 11. UPDATE DATABASE
        // ==========================================
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $Sql = "UPDATE setting_general
                SET logo = ?
                WHERE id_setting_general = '1'";

        $Stmt = mysqli_prepare($Conn, $Sql);

        mysqli_stmt_bind_param(
            $Stmt,
            "s",
            $NewFileName
        );

        mysqli_stmt_execute($Stmt);

        $AffectedRows = mysqli_stmt_affected_rows($Stmt);

        mysqli_stmt_close($Stmt);

        // Pastikan record pengaturan ditemukan
        if ($AffectedRows !== 1) {
            throw new RuntimeException('Data pengaturan koperasi tidak ditemukan atau gagal diperbarui.');
        }

        // ==========================================
        // 12. RESPONSE SUCCESS
        // ==========================================
        $_SESSION['NotifikasiSwal'] = 'Simpan Logo Perusahaan Berhasil';

        JsonResponse(
            'success',
            'Logo perusahaan berhasil diperbarui.'
        );

    } catch (Throwable $e) {

        // Hapus file baru jika database gagal diperbarui
        if ($NewFilePath !== null && is_file($NewFilePath)) {
            unlink($NewFilePath);
        }

        error_log('[SettingImage] ' . $e->getMessage());

        JsonResponse(
            'failed',
            'Terjadi kesalahan pada server saat menyimpan logo.'
        );
    }
?>
