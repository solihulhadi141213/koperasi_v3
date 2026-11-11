<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");
    if(empty($SessionIdAkses)){
        echo '<div class="alert alert-danger text-center">';
        echo '  Sesi Akses Sudah Berakhir, Silahkan Login Ulang';
        echo '</div>';
    }else{
        if(empty($_POST['tahun'])){
            echo '<div class="alert alert-danger text-center">';
            echo '  Pilih Tahun Data Yang Ingin Ditampilkan Terlebih Dulu';
            echo '</div>';
        }else{
            $tahun=$_POST['tahun'];
?>
        <div class="row mb-3">
            <div class="col-md-12 text-center">
                <b>REKAPITULASI JUMLAH PINJAMAN DAN ANGSURAN</b><br>
                <?php
                    echo 'PERIODE TAHUN '.$tahun.'';
                ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12 text-center">
                <a href="javascript:void(0);" class="btn btn-sm btn-outline-secondary btn-rounded" data-bs-toggle="modal" data-bs-target="#ModalCetakPinjamanPeriode">
                    <i class="bi bi-printer"></i> Cetak / Export
                </a>
            </div>
        </div>
        <div class="row mb-3">
            <div class="table table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <td align="center"><b>No</b></td>
                            <td align="center"><b>Bulan</b></td>
                            <td align="center"><b>Jumlah Anggota</b></td>
                            <td align="center"><b>Pinjaman (Rp)</b></td>
                            <td align="center"><b>Angsuran Masuk (Rp)</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $JumlahTotalAnggota=0;
                            $JumlahTotalPinjaman=0;
                            $JumlahTotalAngsuran=0;
                            //Looping Bulan
                            $namaBulan = [
                                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                            ];
                            // Looping dari 1 hingga 12
                            for ($i = 1; $i <= 12; $i++) {
                                $angkaBulan = str_pad($i, 2, '0', STR_PAD_LEFT);
                                $namaBulanIni = $namaBulan[$i - 1];
                                $keyword="$tahun-$angkaBulan";
                                //Menghitung Jumlah Anggota Yang Melakukan Pinjaman Secara Distinct Pada Periode Tersebut
                                $stmt_anggota = mysqli_prepare($Conn, "SELECT id_anggota FROM pinjaman WHERE tanggal LIKE CONCAT('%', ?, '%')");
                                mysqli_stmt_bind_param($stmt_anggota, "s", $keyword);
                                mysqli_stmt_execute($stmt_anggota);
                                $result_anggota = mysqli_stmt_get_result($stmt_anggota);
                                $JumlahAnggota = mysqli_num_rows($result_anggota);
                                mysqli_stmt_close($stmt_anggota);
                                //Sum Jumlah Pinjaman Pada Periode Tersebut
                                $stmt_pinjaman = mysqli_prepare($Conn, "SELECT COALESCE(SUM(jumlah_pinjaman), 0) AS jumlah FROM pinjaman WHERE tanggal LIKE CONCAT('%', ?, '%')");
                                mysqli_stmt_bind_param($stmt_pinjaman, "s", $keyword);
                                mysqli_stmt_execute($stmt_pinjaman);
                                $result_pinjaman = mysqli_stmt_get_result($stmt_pinjaman);
                                $SumPinjaman = mysqli_fetch_array($result_pinjaman);
                                $JumlahPinjaman = $SumPinjaman['jumlah'];
                                mysqli_stmt_close($stmt_pinjaman);
                                $JumlahPinjamanFormat = "Rp " . number_format($JumlahPinjaman,0,',','.');
                                //Menghitung Angsuran Masuk
                                $stmt_angsuran = mysqli_prepare($Conn, "SELECT COALESCE(SUM(jumlah), 0) AS total FROM pinjaman_angsuran WHERE tanggal_bayar LIKE CONCAT('%', ?, '%')");
                                mysqli_stmt_bind_param($stmt_angsuran, "s", $keyword);
                                mysqli_stmt_execute($stmt_angsuran);
                                $result_angsuran = mysqli_stmt_get_result($stmt_angsuran);
                                $SumAngsuran = mysqli_fetch_array($result_angsuran);
                                $JumlahAngsuran = $SumAngsuran['total'];
                                mysqli_stmt_close($stmt_angsuran);
                                $JumlahAngsuranFormat = "Rp " . number_format($JumlahAngsuran,0,',','.');
                                //Akumulasi Jumlah Total
                                $JumlahTotalAnggota=$JumlahTotalAnggota+$JumlahAnggota;
                                $JumlahTotalPinjaman=$JumlahTotalPinjaman+$JumlahPinjaman;
                                $JumlahTotalAngsuran=$JumlahTotalAngsuran+$JumlahAngsuran;
                                echo '<tr>';
                                echo '  <td align="center">'.$i.'</td>';
                                echo '  <td align="left">'.$namaBulanIni.'</td>';
                                echo '  <td align="left">'.$JumlahAnggota.' Anggota</td>';
                                echo '  <td align="right">'.$JumlahPinjamanFormat.'</td>';
                                echo '  <td align="right">'.$JumlahAngsuranFormat.'</td>';
                                echo '</tr>';
                            }
                            echo '<tr>';
                            echo '  <td colspan="5"></td>';
                            echo '</tr>';
                            echo '<tr>';
                            //Forma RP
                            $JumlahTotalPinjaman = "Rp " . number_format($JumlahTotalPinjaman,0,',','.');
                            $JumlahTotalAngsuran = "Rp " . number_format($JumlahTotalAngsuran,0,',','.');
                            echo '  <td align="center" colspan="2"><b>JUMLAH</b></td>';
                            echo '  <td align="left"><b>'.$JumlahTotalAnggota.' Anggota</b></td>';
                            echo '  <td align="right"><b>'.$JumlahTotalPinjaman.'</b></td>';
                            echo '  <td align="right"><b>'.$JumlahTotalAngsuran.'</b></td>';
                            echo '</tr>';
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
<?php
        }
    }
?>