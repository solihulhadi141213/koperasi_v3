<?php
    require '../../vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Style\Fill;
    use PhpOffice\PhpSpreadsheet\Style\Border;
    use PhpOffice\PhpSpreadsheet\Style\Alignment;
    use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
    use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

    // Koneksi dan function global
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    // Ambil parameter tahun dari GET
    $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : "";
    if($tahun=="Semua"){
        $tahun="";
    }
    // Inisialisasi Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Header Dokumen Excel
    $sheet->setCellValue('A1', 'LAPORAN POTONGAN ANGSURAN ANGGOTA KOPERASI');
    $sheet->setCellValue('A2', 'PERIODE: ' . ($tahun ? $tahun : 'SEMUA PERIODE'));
    $sheet->mergeCells('A1:K1');
    $sheet->mergeCells('A2:K2');
    $sheet->getStyle('A1:A2')->getFont()->setBold(true);
    $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal('center');

    // Header Kolom
    $headers = ['No', 'Tanggal', 'Anggota', 'Pinjaman', 'Pinjaman + Jasa', 'Lama Angsuran', 'Sudah Bayar', 'Jumlah Bayar (Rp)', 'Sisa Pinjaman', 'Sisa Pinjaman (Rp)', 'Status'];
    $column = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($column . '4', $header);
        $sheet->getColumnDimension($column)->setAutoSize(true);
        $column++;
    }

    // Query Data Pinjaman
    $query = mysqli_query($Conn, "SELECT * FROM pinjaman WHERE tanggal LIKE '%$tahun%' ORDER BY id_pinjaman ASC");

    $row = 5; // Baris awal data
    $no = 1;
    while ($data = mysqli_fetch_array($query)) {
        $tanggal = date('d/m/Y', strtotime($data['tanggal']));
        $jumlah_pinjaman = $data['jumlah_pinjaman'];
        $jumlah_pinjaman_jasa = $jumlah_pinjaman + ($data['rp_jasa'] * $data['periode_angsuran']);

        // Sum Data Angsuran
        $sum = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS total FROM pinjaman_angsuran WHERE id_pinjaman='{$data['id_pinjaman']}'"));
        $jumlah_angsuran = $sum['total'];

        // Row Data Angsuran
        $jumlah_data_angsuran = mysqli_num_rows(mysqli_query($Conn, "SELECT id_pinjaman_angsuran FROM pinjaman_angsuran WHERE id_pinjaman='{$data['id_pinjaman']}'"));

        // Hitung Sisa Periode Angsuran
        $sisa_pinjaman = $data['periode_angsuran'] - $jumlah_data_angsuran;

        // Hitung Sisa Pinjaman
        $sisa_pinjaman_rp = $jumlah_pinjaman_jasa - $jumlah_angsuran;

        // Masukkan Data ke Excel
        $sheet->setCellValue('A' . $row, $no);
        $sheet->setCellValue('B' . $row, $tanggal);
        $sheet->setCellValue('C' . $row, $data['nama']);
        $sheet->setCellValue('D' . $row, $jumlah_pinjaman);
        $sheet->setCellValue('E' . $row, $jumlah_pinjaman_jasa);
        $sheet->setCellValue('F' . $row, $data['periode_angsuran']);
        $sheet->setCellValue('G' . $row, $jumlah_data_angsuran);
        $sheet->setCellValue('H' . $row, $jumlah_angsuran);
        $sheet->setCellValue('I' . $row, $sisa_pinjaman);
        $sheet->setCellValue('J' . $row, $sisa_pinjaman_rp);
        $sheet->setCellValue('K' . $row, $data['status']);

        $row++;
        $no++;
    }

    // Styling Header
    $sheet->getStyle('A4:K4')->getFont()->setBold(true);
    $sheet->getStyle('A4:K4')->getAlignment()->setHorizontal('center');

    // Set Nama File
    $filename = "Rekap_Pinjaman_Anggota_" . ($tahun ? $tahun : "Semua") . ".xlsx";

    // Proses Download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
?>
