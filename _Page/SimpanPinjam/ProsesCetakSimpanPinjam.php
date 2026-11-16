<?php
    // Include PhpSpreadsheet
    require '../../vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

    // Set timezone
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/Session.php";

    if (empty($SessionIdAkses)) {
        die('Sesi Akses Sudah Berakhir, Silahkan Login Ulang');
    } else {
        if (empty($_GET['tahun']) || empty($_GET['periode'])) {
            die('Tahun dan Periode tidak boleh kosong!');
        } else {
            $tahun = validateAndSanitizeInput($_GET['tahun']);
            $periode = validateAndSanitizeInput($_GET['periode']);

            $bulan=0;
            if($periode=="Bulan"){
                if(empty($_GET['bulan'])){
                    $validasi_kelengkapan="Bulan Tidak Boleh Kosong!";
                }else{
                    $bulan=$_GET['bulan'];
                    $validasi_kelengkapan="Valid";
                }
            }else{
                $validasi_kelengkapan="Valid";
            }
            if($validasi_kelengkapan!=="Valid"){
                die($validasi_kelengkapan);
            }else{

                //Buat Keyword
                if($periode=="Bulan"){
                    $keyword = "$tahun-$bulan";
                }else{
                    $keyword = "$tahun";
                }
                

                // Buat Spreadsheet baru
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Judul laporan
                $sheet->setCellValue('A1', 'LAPORAN SIMPAN PINJAM');
                $sheet->setCellValue('A2', 'Lembaga : Semua Lembaga');
                if($periode=="Bulan"){
                    $sheet->setCellValue('A3', 'Periode : Bulan ' . getNamaBulan($bulan) . ' ' . $tahun);
                }else{
                    $sheet->setCellValue('A3', 'Periode : Tahun ' . $tahun);
                }
                

                // Header kolom
                $sheet->setCellValue('A5', 'NO');
                $sheet->setCellValue('B5', 'LEMBAGA');
                $sheet->setCellValue('C5', 'ANGGOTA');

                // Menampilkan header jenis simpanan
                $qrySimpanan = mysqli_query($Conn, "SELECT * FROM simpanan_jenis ORDER BY id_simpanan_jenis ASC");
                $colIndex = 4;
                while ($dataSimpanan = mysqli_fetch_array($qrySimpanan)) {
                    $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex) . '5', $dataSimpanan['nama_simpanan']);
                    $colIndex++;
                }

                // Header kolom pinjaman
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex) . '5', 'ANGSURAN');
                $colIndex++;
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex) . '5', 'PER');
                $colIndex++;
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex) . '5', 'JASA');
                $colIndex++;
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex) . '5', 'JUMLAH');

                // Fetch data anggota dan simpanan/pinjaman
                $qryAnggota = mysqli_query($Conn, "SELECT * FROM anggota ORDER BY lembaga ASC, nama ASC");
                $row = 6; // Mulai dari baris ke-6
                $no = 1;
                $qrySimpanan = mysqli_query($Conn, "SELECT * FROM simpanan_jenis ORDER BY id_simpanan_jenis ASC");

                // Inisialisasi total
                $totalSimpanan = [];
                while ($dataSimpanan = mysqli_fetch_array($qrySimpanan)) {
                    $id_simpanan_jenis = $dataSimpanan['id_simpanan_jenis'];
                    $totalSimpanan[$id_simpanan_jenis] = 0;
                }
                $totalAngsuran = 0;
                $totalJasa = 0;
                $totalGrand = 0;

                while ($dataAnggota = mysqli_fetch_array($qryAnggota)) {
                    $id_anggota = $dataAnggota['id_anggota'];
                    $nama = $dataAnggota['nama'];
                    $lembagaAnggota = $dataAnggota['lembaga'];

                    $sheet->setCellValue('A' . $row, $no);
                    $sheet->setCellValue('B' . $row, $lembagaAnggota);
                    $sheet->setCellValue('C' . $row, $nama);

                    // Menampilkan simpanan
                    $qrySimpanan = mysqli_query($Conn, "SELECT * FROM simpanan_jenis ORDER BY id_simpanan_jenis ASC");
                    $colIndex = 4;
                    $jumlahSimpananAnggota = 0;
                    while ($dataSimpanan = mysqli_fetch_array($qrySimpanan)) {
                        $id_simpanan_jenis = $dataSimpanan['id_simpanan_jenis'];
                        $sumSimpanan = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS jumlah FROM simpanan WHERE id_anggota='$id_anggota' AND id_simpanan_jenis='$id_simpanan_jenis' AND tanggal LIKE '%$keyword%'"));
                        $jumlahSimpanan = $sumSimpanan['jumlah'] ?? 0;
                        $totalSimpanan[$id_simpanan_jenis] += $jumlahSimpanan;
                        $jumlahSimpananAnggota += $jumlahSimpanan;
                        $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex) . $row, $jumlahSimpanan);
                        $colIndex++;
                    }

                    // Menampilkan angsuran dan jasa
                    $qryPinjaman = mysqli_query($Conn, "SELECT * FROM pinjaman WHERE id_anggota='$id_anggota' AND status='Berjalan'");
                    $dataPinjaman = mysqli_fetch_array($qryPinjaman);
                    if ($dataPinjaman) {
                        $jumlahPinjaman = $dataPinjaman['jumlah_pinjaman'] ?? 0;
                    } else {
                        $jumlahPinjaman = 0;
                    }

                    $sumAngsuran = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(pokok) AS pokok FROM pinjaman_angsuran WHERE id_anggota='$id_anggota' AND tanggal_bayar LIKE '%$keyword%'"));
                    $jumlahPokok = $sumAngsuran['pokok'] ?? 0;
                    $totalAngsuran += $jumlahPokok;
                    $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex) . $row, $jumlahPokok);
                    $colIndex++;

                    // Null check for dataPinjaman
                    if ($dataPinjaman) {
                        $periodeAngsuran = $dataPinjaman['periode_angsuran'] ?? 0;
                        $angsuranMasuk = mysqli_num_rows(mysqli_query($Conn, "SELECT * FROM pinjaman_angsuran WHERE id_pinjaman='{$dataPinjaman['id_pinjaman']}'"));
                    } else {
                        $periodeAngsuran = 0;
                        $angsuranMasuk = 0;
                    }
                    $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex) . $row, "$angsuranMasuk/$periodeAngsuran");
                    $colIndex++;

                    $sumJasa = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jasa) AS jasa FROM pinjaman_angsuran WHERE id_anggota='$id_anggota' AND tanggal_bayar LIKE '%$keyword%'"));
                    $jumlahJasa = $sumJasa['jasa'] ?? 0;
                    $totalJasa += $jumlahJasa;
                    $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex) . $row, $jumlahJasa);
                    $colIndex++;

                    // Total per anggota
                    $jumlahPemasukan = $jumlahSimpananAnggota + $jumlahPokok + $jumlahJasa;
                    $totalGrand += $jumlahPemasukan;
                    $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex) . $row, $jumlahPemasukan);

                    $row++;
                    $no++;
                }

                // Tambahkan baris total di bagian akhir
                $sheet->setCellValue('A' . $row, 'JUMLAH');
                $sheet->mergeCells('A' . $row . ':C' . $row);

                $colIndex = 4;
                foreach ($totalSimpanan as $total) {
                    $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex) . $row, $total);
                    $colIndex++;
                }
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex) . $row, $totalAngsuran);
                $colIndex++;
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex) . $row, '');
                $colIndex++;
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex) . $row, $totalJasa);
                $colIndex++;
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex) . $row, $totalGrand);

                // === Dinamisasi lebar kolom sesuai karakter paling panjang ===
                $columnWidths = [];
                $minWidth = 8;
                $maxWidth = 60;
                $padding = 2;
                $highColumn = $sheet->getHighestColumn();
                $highRow = $sheet->getHighestRow();
                $maxColIndex = Coordinate::columnIndexFromString($highColumn);

                // Inisialisasi lebar kolom dengan minWidth
                for ($c = 1; $c <= $maxColIndex; $c++) {
                    $colLetter = Coordinate::stringFromColumnIndex($c);
                    $columnWidths[$colLetter] = $minWidth;
                }

                // Hitung panjang karakter maksimum per kolom (termasuk header)
                for ($r = 1; $r <= $highRow; $r++) {
                    for ($c = 1; $c <= $maxColIndex; $c++) {
                        $colLetter = Coordinate::stringFromColumnIndex($c);
                        $cellValue = $sheet->getCell($colLetter . $r)->getValue();
                        if ($cellValue !== null && $cellValue !== '') {
                            $len = mb_strlen((string) $cellValue, 'UTF-8');
                            if ($len > $columnWidths[$colLetter]) {
                                $columnWidths[$colLetter] = $len;
                            }
                        }
                    }
                }

                // Terapkan lebar kolom (tambahkan padding, batas maksimal maxWidth)
                foreach ($columnWidths as $colLetter => $width) {
                    $finalWidth = min(max($width + $padding, $minWidth), $maxWidth);
                    $sheet->getColumnDimension($colLetter)->setWidth($finalWidth);
                }
                // ============================================================

                // Simpan file ke Excel
                $filename = 'Laporan_Simpan_Pinjam_SemuaLembaga_' . $bulan . '_' . $tahun . '.xlsx';
                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                $writer->save('php://output');
                exit;
            }
        }
    }
?>
