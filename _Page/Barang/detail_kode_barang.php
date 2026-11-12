
<?php
    // ======================================================
    // KONFIGURASI
    // ======================================================
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    date_default_timezone_set('Asia/Jakarta');

    require_once "../../vendor/autoload.php";

    // ======================================================
    // FUNGSI BANTU
    // ======================================================
    $Escape = function ($Value): string {
        return htmlspecialchars((string) $Value, ENT_QUOTES, 'UTF-8');
    };

    $ShowError = function (string $Message) use ($Escape): void {
        echo '<div class="alert alert-danger mb-0"><small>' .
            $Escape($Message) .
            '</small></div>';
    };

    // ======================================================
    // VALIDASI SESI
    // ======================================================
    if (empty($SessionIdAkses)) {
        $ShowError('Sesi Akses Sudah Berakhir, Silahkan Login Ulang!');
        exit;
    }

    // ======================================================
    // AMBIL INPUT FORM
    // ======================================================
    $id_barang = trim($_POST['id_barang'] ?? '');
    $type_code = trim($_POST['type_code'] ?? '');
    $kategori_harga_kode = trim($_POST['kategori_harga_kode'] ?? '');
    $tampilkan_nama_barang = trim($_POST['tampilkan_nama_barang_for_code'] ?? 'Tidak');
    $type_file_code = trim($_POST['type_file_code'] ?? 'Direct');

    // ======================================================
    // VALIDASI INPUT
    // ======================================================
    if ($id_barang === '') {
        $ShowError('ID Barang Tidak Boleh Kosong!');
        exit;
    }

    if ($type_code === '') {
        $ShowError('Tipe Code Tidak Boleh Kosong!');
        exit;
    }

    $TipeBarcode = [
        'code128' => Picqer\Barcode\BarcodeGenerator::TYPE_CODE_128,
        'code39'  => Picqer\Barcode\BarcodeGenerator::TYPE_CODE_39,
        'code25'  => Picqer\Barcode\BarcodeGenerator::TYPE_STANDARD_2_5
    ];

    if (!isset($TipeBarcode[$type_code]) && $type_code !== 'qrcode') {
        $ShowError('Tipe Barcode Tidak Valid!');
        exit;
    }

    if (!in_array($type_file_code, ['Direct', 'Image', 'PDF'], true)) {
        $ShowError('Tipe Cetak Tidak Valid!');
        exit;
    }

    if (!in_array($tampilkan_nama_barang, ['Tampilkan', 'Tidak'], true)) {
        $ShowError('Pilihan Nama Barang Tidak Valid!');
        exit;
    }

    if (
        $kategori_harga_kode !== '' &&
        (!ctype_digit($kategori_harga_kode) || (int) $kategori_harga_kode < 1)
    ) {
        $ShowError('Kategori Harga Tidak Valid!');
        exit;
    }

    // ======================================================
    // PROSES DATA DAN GENERATE KODE
    // ======================================================
    try {

        // --------------------------------------------------
        // 1. AMBIL DATA BARANG
        // --------------------------------------------------
        $Qry = $Conn->prepare("
            SELECT
                id_barang,
                kode_barang,
                nama_barang
            FROM barang
            WHERE id_barang = ?
            LIMIT 1
        ");

        if (!$Qry) {
            throw new Exception('Gagal menyiapkan query data barang.');
        }

        $Qry->bind_param("s", $id_barang);

        if (!$Qry->execute()) {
            throw new Exception('Gagal mengambil data barang.');
        }

        $Result = $Qry->get_result();
        $DataBarang = $Result->fetch_assoc();
        $Qry->close();

        if (!$DataBarang) {
            $ShowError('Data Barang Tidak Ditemukan!');
            exit;
        }

        $kode_barang = trim((string) $DataBarang['kode_barang']);
        $nama_barang = (string) $DataBarang['nama_barang'];

        if ($kode_barang === '') {
            $ShowError('Kode Barang Belum Memiliki Kode!');
            exit;
        }

        // --------------------------------------------------
        // 2. PENGATURAN NAMA BARANG
        // --------------------------------------------------
        if ($tampilkan_nama_barang === 'Tidak') {
            $nama_barang = '';
        }

        // --------------------------------------------------
        // 3. AMBIL HARGA BARANG
        // --------------------------------------------------
        $harga_barang = '';

        if ($kategori_harga_kode !== '') {

            $id_barang_kategori_harga = (int) $kategori_harga_kode;

            $QryHarga = $Conn->prepare("
                SELECT harga
                FROM barang_harga
                WHERE id_barang_kategori_harga = ?
                AND id_barang = ?
                LIMIT 1
            ");

            if (!$QryHarga) {
                throw new Exception('Gagal menyiapkan query harga barang.');
            }

            $QryHarga->bind_param(
                "is",
                $id_barang_kategori_harga,
                $id_barang
            );

            if (!$QryHarga->execute()) {
                throw new Exception('Gagal mengambil harga barang.');
            }

            $ResultHarga = $QryHarga->get_result();
            $DataHarga = $ResultHarga->fetch_assoc();
            $QryHarga->close();

            if ($DataHarga) {
                $harga_barang = 'Rp ' . number_format(
                    (float) $DataHarga['harga'],
                    0,
                    ',',
                    '.'
                );
            }
        }

        // --------------------------------------------------
        // 4. PERSIAPAN OUTPUT
        // --------------------------------------------------
        $kode_barang_html = $Escape($kode_barang);
        $nama_barang_html = $Escape($nama_barang);
        $harga_barang_html = $Escape($harga_barang);
        $type_file_code_html = $Escape($type_file_code);

        // --------------------------------------------------
        // 5. GENERATE BARCODE / QR CODE
        // --------------------------------------------------
        if (isset($TipeBarcode[$type_code])) {

            // ==============================================
            // BARCODE - PICQER
            // ==============================================
            $Generator = new Picqer\Barcode\BarcodeGeneratorSVG();

            $BarcodeSVG = $Generator->getBarcode(
                $kode_barang,
                $TipeBarcode[$type_code],
                2,
                65
            );

            $KodeImage = '
                <div class="barcode-container">
                    ' . $BarcodeSVG . '
                </div>
            ';

        } else {

            // ==============================================
            // QR CODE - CHILLERLAN
            // ==============================================
            $QRCode = new chillerlan\QRCode\QRCode();

            // Default output SVG dalam format Base64 Data URI
            $QRCodeDataURI = $QRCode->render($kode_barang);

            if (empty($QRCodeDataURI)) {
                throw new Exception('Gagal membuat QR Code.');
            }

            $KodeImage = '
                <div class="qrcode-container">
                    <img
                        src="' . $Escape($QRCodeDataURI) . '"
                        alt="QR Code ' . $kode_barang_html . '"
                        width="180"
                        height="180"
                        style="max-width:100%;height:auto;"
                    >
                </div>
            ';
        }

        // --------------------------------------------------
        // 6. TAMPILKAN PREVIEW
        // --------------------------------------------------
        echo '
            <div
                class="row mb-3"
                id="PreviewKodeBarang"
                data-type-code="' . $Escape($type_code) . '"
                data-type-file="' . $type_file_code_html . '"
            >
                <div class="col-12 text-center">

                    <small class="name_of_product">
                        ' . $nama_barang_html . '
                    </small>

                    <br>

                    ' . $KodeImage . '

                    <br>

                    <small class="price_of_product">
                        ' . $harga_barang_html . '
                    </small>

                </div>
            </div>
        ';

    } catch (Throwable $e) {

        // Catat detail kesalahan pada PHP error log
        error_log(
            '[PreviewKodeBarang] ' .
            $e->getMessage()
        );

        // Tampilkan pesan yang aman kepada pengguna
        $ShowError(
            'Terjadi kesalahan ketika membuat Barcode / QR Code. Silahkan periksa konfigurasi dan data barang.'
        );
    }
?>
