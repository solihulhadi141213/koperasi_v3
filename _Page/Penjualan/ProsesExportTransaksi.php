<?php 
    require '../../vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Style\Fill;
    use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
    use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

    // Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    if(empty($SessionIdAkses)){
        echo "Sesi Akses Sudah Berakhir, Silahkan Login Ulang";
        exit;
    }
    $JumlahData = mysqli_num_rows(mysqli_query($Conn, "SELECT * FROM transaksi_jual_beli WHERE kategori='Penjualan' OR kategori='Retur Penjualan'"));
    if(empty($JumlahData)){
        echo "Belum ada data transaksi";
        exit;
    } else {
        // Membuat objek Spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Menulis judul
        $headers = [
            'A1' => 'No',
            'B1' => 'Tanggal',
            'C1' => 'Kategori',
            'D1' => 'Nama Anggota',
            'E1' => 'Subtotal',
            'F1' => 'PPN',
            'G1' => 'Diskon',
            'H1' => 'Total',
            'I1' => 'Cash',
            'J1' => 'Kembalian',
            'K1' => 'Status'
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Mengatur gaya baris judul
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        $sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Membuat objek Writer untuk menulis Spreadsheet ke dalam file XLSX
        $writer = new Xlsx($spreadsheet);

        $no = 1;
        $row = 2;
        $query = mysqli_query($Conn, "SELECT * FROM transaksi_jual_beli WHERE kategori='Penjualan' OR kategori='Retur Penjualan' ORDER BY id_transaksi_jual_beli ASC");
        
        while ($data = mysqli_fetch_array($query)) {
            $id_transaksi_jual_beli= $data['id_transaksi_jual_beli'];
            $kategori= $data['kategori'];
            $tanggal= $data['tanggal'];
            $subtotal= $data['subtotal'];
            $ppn= $data['ppn'];
            $diskon= $data['diskon'];
            $cash= $data['cash'];
            $kembalian= $data['kembalian'];
            $total= $data['total'];
            $status= $data['status'];
            $total_rp = "Rp " . number_format($total,0,',','.');
            //Buka nama anggota dari tabel anggota
            if(empty($data['id_anggota'])){
                $id_anggota= "";
                $nama_anggota="-";
            }else{
                $id_anggota= $data['id_anggota'];
                $nama_anggota=GetDetailData($Conn, 'anggota', 'id_anggota', $id_anggota, 'nama');
            }
            
            // Format tanggal
            $tanggal_format = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(new DateTime($tanggal));

            // Menampilkan Data
            $sheet->setCellValue('A'.$row, $no);
            
            // Format tanggal di Excel
            $sheet->setCellValue('B'.$row, $tanggal_format);
            $sheet->getStyle('B'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);

            $sheet->setCellValue('C'.$row, $kategori);
            $sheet->setCellValue('D'.$row, $nama_anggota);
            
            // Format angka di Excel
            $sheet->setCellValue('E'.$row, $subtotal);
            $sheet->getStyle('E'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            // Format angka di Excel
            $sheet->setCellValue('F'.$row, $ppn);
            $sheet->getStyle('F'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            // Format angka di Excel
            $sheet->setCellValue('G'.$row, $diskon);
            $sheet->getStyle('G'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            // Format angka di Excel
            $sheet->setCellValue('H'.$row, $total);
            $sheet->getStyle('H'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            // Format angka di Excel
            $sheet->setCellValue('I'.$row, $cash);
            $sheet->getStyle('I'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            // Format angka di Excel
            $sheet->setCellValue('J'.$row, $kembalian);
            $sheet->getStyle('J'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            $sheet->setCellValue('K'.$row, $status);

            $row++; // Pindah ke baris berikutnya
            $no++;
        }

        // Menyesuaikan lebar kolom dengan karakter terpanjang
        foreach(range('A', 'K') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $filename = "Transaksi-Penjualan.xlsx";
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename .'"');
        header('Cache-Control: max-age=0');
        
        // Menulis Spreadsheet ke dalam output PHP
        $writer->save('php://output');
    }
?>
