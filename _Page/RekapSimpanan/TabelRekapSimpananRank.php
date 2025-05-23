<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    //Time Zone
    date_default_timezone_set('Asia/Jakarta');
    //Cek Akses
    if(empty($SessionIdAkses)){
        echo '<div class="row mb-3">';
        echo '  <div class="col col-md-12 text-center">';
        echo '      <code>Sesi Akses Sudah Berakhir. Silahkan Login Ulang!</code>';
        echo '  </div>';
        echo '</div>';
    }else{
        if(empty($_POST['periode'])){
            echo '<div class="row mb-3">';
            echo '  <div class="col col-md-12 text-center">';
            echo '      <code>Mode Periode Data Tidak Boleh Kosong!</code>';
            echo '  </div>';
            echo '</div>';
        }else{
            $periode=$_POST['periode'];
            //Bersihkan Variabel
            $periode = validateAndSanitizeInput($periode);
            //Validasi Kelengkapan Data
            if($periode=="Semua"){
                $VariabelKelengkapanData="Valid";
                $keyword="";
            }else{
                if($periode=="Tahunan"){
                    if(empty($_POST['tahun'])){
                        $VariabelKelengkapanData="Parameter Tahun Tidak Boleh Kosong";
                    }else{
                        $VariabelKelengkapanData="Valid";
                        $tahun=$_POST['tahun'];
                        $tahun = validateAndSanitizeInput($tahun);
                        $keyword="$tahun";
                    }
                }else{
                    if($periode=="Bulanan"){
                        if(empty($_POST['tahun'])){
                            $VariabelKelengkapanData="Parameter Tahun Tidak Boleh Kosong";
                        }else{
                            if(empty($_POST['bulan'])){
                                $VariabelKelengkapanData="Parameter Bulan Tidak Boleh Kosong";
                            }else{
                                $VariabelKelengkapanData="Valid";
                                $tahun=$_POST['tahun'];
                                $bulan=$_POST['bulan'];
                                $bulan = validateAndSanitizeInput($bulan);
                                $tahun = validateAndSanitizeInput($tahun);
                                $keyword="$tahun-$bulan";
                            }
                        }
                    }else{
                        $VariabelKelengkapanData="Periode Data Tidak Valid";
                    }
                }
            }
            if($VariabelKelengkapanData!=="Valid"){
                echo '<div class="row mb-3">';
                echo '  <div class="col col-md-12 text-center">';
                echo '      <code>'.$VariabelKelengkapanData.'</code>';
                echo '  </div>';
                echo '</div>';
            }else{
                //Jumlah Jenis Simpanan
                $JumlahSimpanan = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM simpanan_jenis"));
?>
        <div class="row mb-3">
            <div class="col-md-12 text-center">
                REKAP DATA SIMPANAN  <span style="text-transform: uppercase;"><?php echo $periode; ?></span><br>
                <?php
                    if($periode=="Bulanan"){
                        $nama_bulan=getNamaBulan($bulan);
                        echo '<span style="text-transform: uppercase;">PERIODE '.$nama_bulan.' '.$tahun.'</span>';
                    }else{
                        if($periode=="Tahunan"){
                            echo '<span style="text-transform: uppercase;">PERIODE '.$tahun.'</span>';
                        }else{
    
                        }
                    }
                ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12 text-center">
                <button type="button"class="btn btn-sm btn-outline-secondary btn-rounded" data-bs-toggle="modal" data-bs-target="#ModalCetak2">
                    <i class="bi bi-printer"></i> Cetak Data Rekap
                </button>
            </div>
        </div>
        <div class="row mb-3">
            <div class="table table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <td align="center" rowspan="2" valign="middle"><b>No</b></td>
                            <td align="center" rowspan="2" valign="middle"><b>Rank</b></td>
                            <td align="center" colspan="<?php echo $JumlahSimpanan; ?>"><b>Simpanan</b></td>
                            <td align="center" rowspan="2" valign="middle"><b>Jumlah</b></td>
                            <td align="center" rowspan="2" valign="middle"><b>Penarikan</b></td>
                            <td align="center" rowspan="2" valign="middle"><b>saldo</b></td>
                        </tr>
                        <tr>
                            <?php
                                $query = mysqli_query($Conn, "SELECT*FROM simpanan_jenis ORDER BY id_simpanan_jenis ASC");
                                while ($data = mysqli_fetch_array($query)) {
                                    $id_simpanan_jenis= $data['id_simpanan_jenis'];
                                    $nama_simpanan= $data['nama_simpanan'];
                                    echo '<td align="center"><small class="credit">'.$nama_simpanan.'</small></td>';
                                }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            //Hitung Jumlah Data
                            if(empty($keyword)){
                                $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM simpanan"));
                            }else{
                                $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM simpanan WHERE tanggal like '%$keyword%'"));
                            }
                            //Jumlah Kolom
                            $JumlahKolom=$JumlahSimpanan+5;
                            if(empty($jml_data)){
                                echo '<tr>';
                                echo '  <td colspan="'.$JumlahKolom.'" class="text-center">';
                                echo '      <code class="text-danger">';
                                echo '          Tidak Ada Data Simpanan Yang Dapat Ditampilkan';
                                echo '      </code>';
                                echo '  </td>';
                                echo '</tr>';
                                $JumlahTotalSimpanan=0;
                                $JumlahTotalPenarikan=0;
                                $JumlahTotalSaldo=0;
                            }else{
                                $no = 1;
                                //KONDISI PENGATURAN MASING FILTER
                                $JumlahTotalSimpanan=0;
                                $JumlahTotalPenarikan=0;
                                $JumlahTotalSaldo=0;
                                $query = mysqli_query($Conn, "SELECT DISTINCT ranking FROM anggota ORDER BY ranking ASC");
                                while ($data = mysqli_fetch_array($query)) {
                                    $ranking= $data['ranking'];
                                    //Jumlah Simpanan
                                    $SumSimpanan = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS total FROM simpanan WHERE ranking='$ranking' AND kategori!='Penarikan' AND tanggal like '%$keyword%'"));
                                    $jumlah_simpanan = $SumSimpanan['total'];
                                    $JumlahSimpananFormat = "" . number_format($jumlah_simpanan,0,',','.');
                                    //Jumlah Penarikan
                                    $SumPenarikan = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS total FROM simpanan WHERE ranking='$ranking' AND kategori='Penarikan' AND tanggal like '%$keyword%'"));
                                    $jumlah_penarikan = $SumPenarikan['total'];
                                    $JumlahPenarikanFormat = "" . number_format($jumlah_penarikan,0,',','.');
                                    //Menghitung Saldo
                                    $Saldo=$jumlah_simpanan-$jumlah_penarikan;
                                    $SaldoFormat = "" . number_format($Saldo,0,',','.');
                                    //Menghitung Jumlah Total
                                    $JumlahTotalSimpanan=$JumlahTotalSimpanan+$jumlah_simpanan;
                                    $JumlahTotalPenarikan=$JumlahTotalPenarikan+$jumlah_penarikan;
                                    $JumlahTotalSaldo=$JumlahTotalSaldo+$Saldo;
                        ?>
                                    <tr>
                                        <td align="center"><small class="credit"><?php echo $no; ?></small></td>
                                        <td align="left">
                                            <small class="credit">
                                                <code class="text-dark">
                                                    <?php echo $ranking; ?>
                                                </code>
                                            </small>
                                        </td>
                                        <?php
                                            $query_simpanan = mysqli_query($Conn, "SELECT*FROM simpanan_jenis ORDER BY id_simpanan_jenis ASC");
                                            while ($data_simpanan = mysqli_fetch_array($query_simpanan)) {
                                                $id_simpanan_jenis= $data_simpanan['id_simpanan_jenis'];
                                                $nama_simpanan= $data_simpanan['nama_simpanan'];
                                                //Menghitung Jumlah Simpanan Berdasarkan Jenis
                                                $SumSimpananByJenis = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS total FROM simpanan WHERE ranking='$ranking' AND kategori='$nama_simpanan' AND tanggal like '%$keyword%'"));
                                                $jumlah_simpanan_jenis = $SumSimpananByJenis['total'];
                                                $jumlah_simpanan_jenis_format = "" . number_format($jumlah_simpanan_jenis,0,',','.');
                                                echo '<td align="right"><small class="credit"><code class="text-dark">'.$jumlah_simpanan_jenis_format.'</code></small></td>';
                                            }
                                        ?>
                                        <td align="right">
                                            <small class="credit">
                                                <code class="text-dark">
                                                    <?php echo $JumlahSimpananFormat; ?>
                                                </code>
                                            </small>
                                        </td>
                                        <td align="right">
                                            <small class="credit">
                                                <code class="text-dark">
                                                    <?php echo $JumlahPenarikanFormat; ?>
                                                </code>
                                            </small>
                                        </td>
                                        <td align="right">
                                            <small class="credit">
                                                <code class="text-dark">
                                                    <?php echo $SaldoFormat; ?>
                                                </code>
                                            </small>
                                        </td>
                                    </tr>
                        <?php
                                    $no++; 
                                }
                            }
                            $JumlahTotalSimpananFormat = "" . number_format($JumlahTotalSimpanan,0,',','.');
                            $JumlahTotalPenarikanFormat = "" . number_format($JumlahTotalPenarikan,0,',','.');
                            $JumlahTotalSaldoFormat = "" . number_format($JumlahTotalSaldo,0,',','.');
                        ?>
                        <tr>
                            <td colspan="2"><b>JUMLAH</b></td>
                            <?php
                                $query_simpanan = mysqli_query($Conn, "SELECT*FROM simpanan_jenis ORDER BY id_simpanan_jenis ASC");
                                while ($data_simpanan = mysqli_fetch_array($query_simpanan)) {
                                    $id_simpanan_jenis= $data_simpanan['id_simpanan_jenis'];
                                    $nama_simpanan= $data_simpanan['nama_simpanan'];
                                    //Menghitung Jumlah Simpanan Berdasarkan Jenis
                                    $SumSimpananByJenis = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS total FROM simpanan WHERE kategori='$nama_simpanan' AND tanggal like '%$keyword%'"));
                                    $jumlah_simpanan_jenis = $SumSimpananByJenis['total'];
                                    $jumlah_simpanan_jenis_format = "" . number_format($jumlah_simpanan_jenis,0,',','.');
                                    echo '<td align="right"><b>'.$jumlah_simpanan_jenis_format.'</b></td>';
                                }
                            ?>
                            <td align="right"><b><?php echo "$JumlahTotalSimpananFormat"; ?></b></td>
                            <td align="right"><b><?php echo "$JumlahTotalPenarikanFormat"; ?></b></td>
                            <td align="right"><b><?php echo "$JumlahTotalSaldoFormat"; ?></b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

<?php
            }
        }
    }
?>