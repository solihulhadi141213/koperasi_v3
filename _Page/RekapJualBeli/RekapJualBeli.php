<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'ungKMcHQ0OvFgMhS1y8');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
?>
    <div class="pagetitle">
        <h1>
            <a href="">
                <i class="bi bi-table"></i> Rekapitulasi Jual-Beli</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active"> Rekapitulasi Jual-Beli</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <?php
                    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
                    echo '  <small>';
                    echo '      Berikut ini halaman rekapitulasi data transaksi jual-beli berdasarkan periode waktu dan jenis transaksi yang sudah berlangsung.';
                    echo '      Gunakan tombol "Filter" untuk menentukan dasar pengelompokan data.';
                    echo '      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '  </small>';
                    echo '</div>';
                ?>
            </div>
        </div>
        <?php
            if(empty($_POST['tahun'])){
                $tahun=date('Y');
            }else{
                $tahun=$_POST['tahun'];
            }
        ?>
        <form action="javascript:void(0);" id="ProsesFilterGrafik">
            <input type="hidden" name="tahun" value="<?php echo "$tahun"; ?>">
        </form>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10">
                                <b class="card-title">
                                    <i class="bi bi-graph-up"></i> Grafik Rekapitulasi Transaksi
                                </b>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a class="btn btn-md btn-outline-dark btn-rounded btn-block" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalFilterGrafik">
                                    <i class="bi bi-filter"></i> Mode Data
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12 text-center" id="MenampilkanJudulGrafik">
                                
                            </div>
                        </div>
                        <div class="row mb-3 border-1 border-bottom">
                            <div class="col-md-12 mb-3 text-center" id="MenampilkanGambarGrafik">
                                
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12 text-center">
                                <b>
                                    TABEL REKAPITULASI TRANSAKSI <br>
                                    PENJUALAN-PEMBELIAN <br>
                                    PERIODE TAHUN <?php echo "$tahun"; ?>
                                </b>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12 table table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <td align="center" rowspan="2" class="bg-dark text-light" valign="top"><b>No</b></td>
                                            <td align="center" rowspan="2" class="bg-dark text-light" valign="top"><b>Periode</b></td>
                                            <td align="center" colspan="3" class="bg-success text-light"><b>Transaksi Penjualan</b></td>
                                            <td align="center" colspan="3" class="bg-warning text-light"><b>Transaksi Pembelian</b></td>
                                        </tr>
                                        <tr>
                                            <td align="center" class="bg-success text-light"><b>Penjualan</b></td>
                                            <td align="center" class="bg-success text-light"><b>Retur</b></td>
                                            <td align="center" class="bg-success text-light"><b>Jumlah</b></td>
                                            <td align="center" class="bg-warning text-light"><b>Pembelian</b></td>
                                            <td align="center" class="bg-warning text-light"><b>Retur</b></td>
                                            <td align="center" class="bg-warning text-light"><b>Jumlah</b></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $JumlahTotalPenjualan=0;
                                            $JumlahTotalReturPenjualan=0;
                                            $JumlahTotalPenjualanBersih=0;
                                            $JumlahTotalPembelian=0;
                                            $JumlahTotalReturPembelian=0;
                                            $JumlahTotalPembelianBersih=0;
                                            // Array dengan nama-nama bulan
                                            $namaBulan = [
                                                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                                            ];
                                            // Looping dari 1 hingga 12
                                            for ($i = 1; $i <= 12; $i++) {
                                                // Mengubah angka menjadi format dua digit
                                                $angkaBulan = str_pad($i, 2, '0', STR_PAD_LEFT);
                                                // Mengambil nama bulan dari array
                                                $namaBulanIni = $namaBulan[$i - 1];
                                                ///Membentuk Tahun Bulan
                                                $periode="$tahun-$angkaBulan";

                                                //Menghitung Jumlah Transaksi Penjualan
                                                $SumPenjualan = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(total) AS total FROM transaksi_jual_beli WHERE kategori='Penjualan' AND tanggal like '%$periode%'"));
                                                if(empty($SumPenjualan['total'])){
                                                    $JumlahPenjualan=0;
                                                }else{
                                                    $JumlahPenjualan = $SumPenjualan['total'];
                                                }
                                                
                                                //Menghitung Jumlah Transaksi Retur Penjualan
                                                $SumReturPenjualan = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(total) AS total FROM transaksi_jual_beli WHERE kategori='Retur Penjualan' AND tanggal like '%$periode%'"));
                                                if(empty($SumReturPenjualan['total'])){
                                                    $JumlahReturPenjualan=0;
                                                }else{
                                                    $JumlahReturPenjualan = $SumReturPenjualan['total'];
                                                }

                                                //Menghitung Jumlah Penjualan Bersih
                                                $JumlahPenjualanBersih = $JumlahPenjualan-$JumlahReturPenjualan;

                                                //Menghitung Jumlah Transaksi Pembelian
                                                $SumPembelian = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(total) AS total FROM transaksi_jual_beli WHERE kategori='Pembelian' AND tanggal like '%$periode%'"));
                                                if(empty($SumPembelian['total'])){
                                                    $JumlahPembelian=0;
                                                }else{
                                                    $JumlahPembelian = $SumPembelian['total'];
                                                }

                                                //Menghitung Jumlah Transaksi Retur Pembelian
                                                $SumReturPembelian = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(total) AS total FROM transaksi_jual_beli WHERE kategori='Retur Pembelian' AND tanggal like '%$periode%'"));
                                                if(empty($SumReturPembelian['total'])){
                                                    $JumlahReturPembelian=0;
                                                }else{
                                                    $JumlahReturPembelian = $SumReturPembelian['total'];
                                                }
                                                //Menghitung Jumlah Penjualan Bersih
                                                $JumlahPembelianBersih = $JumlahPembelian-$JumlahReturPembelian;

                                                //Ubah Format Penjualan, Retur Penjualan, Penjualan Bersih, Pembelian, Retur Pembelian, Pembelian Bersih
                                                $JumlahPenjualanRp = "Rp " . number_format($JumlahPenjualan,0,',','.');
                                                $JumlahReturPenjualanRp = "Rp " . number_format($JumlahReturPenjualan,0,',','.');
                                                $JumlahPenjualanBersihRp = "Rp " . number_format($JumlahPenjualanBersih,0,',','.');
                                                $JumlahPembelianhRp = "Rp " . number_format($JumlahPembelian,0,',','.');
                                                $JumlahReturPembelianRp = "Rp " . number_format($JumlahReturPembelian,0,',','.');
                                                $JumlahPembelianBersihRp = "Rp " . number_format($JumlahPembelianBersih,0,',','.');
                                                echo '<tr>';
                                                echo '  <td align="center"><small>'.$i.'</small></td>';
                                                echo '  <td align="left"><small>'.$namaBulanIni.'</small></td>';
                                                echo '  <td align="right" class="text-success"><small>'.$JumlahPenjualanRp.'</small></td>';
                                                echo '  <td align="right" class="text-success"><small>'.$JumlahReturPenjualanRp.'</small></td>';
                                                echo '  <td align="right" class="text-success"><small>'.$JumlahPenjualanBersihRp.'</small></td>';
                                                echo '  <td align="right" class="text-warning"><small>'.$JumlahPembelianhRp.'</small></td>';
                                                echo '  <td align="right" class="text-warning"><small>'.$JumlahReturPembelianRp.'</small></td>';
                                                echo '  <td align="right" class="text-warning"><small>'.$JumlahPembelianBersihRp.'</small></td>';
                                                echo '</tr>';

                                                //Hitung Jumlah Total
                                                $JumlahTotalPenjualan=$JumlahTotalPenjualan+$JumlahPenjualan;
                                                $JumlahTotalReturPenjualan=$JumlahTotalReturPenjualan+$JumlahReturPenjualan;
                                                $JumlahTotalPenjualanBersih=$JumlahTotalPenjualanBersih+$JumlahPenjualanBersih;
                                                $JumlahTotalPembelian=$JumlahTotalPembelian+$JumlahPembelian;
                                                $JumlahTotalReturPembelian=$JumlahTotalReturPembelian+$JumlahReturPembelian;
                                                $JumlahTotalPembelianBersih=$JumlahTotalPembelianBersih+$JumlahPembelianBersih;
                                            }

                                            //Ubah Format Jumlah Total Menjadi Rp
                                            $JumlahTotalPenjualan = "Rp " . number_format($JumlahTotalPenjualan,0,',','.');
                                            $JumlahTotalReturPenjualan = "Rp " . number_format($JumlahTotalReturPenjualan,0,',','.');
                                            $JumlahTotalPenjualanBersih = "Rp " . number_format($JumlahTotalPenjualanBersih,0,',','.');
                                            $JumlahTotalPembelian = "Rp " . number_format($JumlahTotalPembelian,0,',','.');
                                            $JumlahTotalReturPembelian = "Rp " . number_format($JumlahTotalReturPembelian,0,',','.');
                                            $JumlahTotalPembelianBersih = "Rp " . number_format($JumlahTotalPembelianBersih,0,',','.');
                                            echo '
                                                <tr>
                                                    <td colspan="2"><b>JUMLAH/TOTAL</b></td>
                                                    <td align="right"><b>'.$JumlahTotalPenjualan.'</b></td>
                                                    <td align="right"><b>'.$JumlahTotalReturPenjualan.'</b></td>
                                                    <td align="right"><b>'.$JumlahTotalPenjualanBersih.'</b></td>
                                                    <td align="right"><b>'.$JumlahTotalPembelian.'</b></td>
                                                    <td align="right"><b>'.$JumlahTotalReturPembelian.'</b></td>
                                                    <td align="right"><b>'.$JumlahTotalPembelianBersih.'</b></td>
                                                </tr>
                                            ';
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>