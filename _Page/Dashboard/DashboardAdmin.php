<?php
    //Jumlah Anggota Aktif
    $JumlahAnggota = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM anggota WHERE status='Aktif'"));
    $JumlahAnggotaFormat = "" . number_format($JumlahAnggota,0,',','.');
    //Jumlah Simpanan Bersih
    $SumSimpananKotor = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS jumlah FROM simpanan WHERE kategori!='Penarikan'"));
    $JumlahSimpananKotor = $SumSimpananKotor['jumlah'];
    //Penarikan Simpanan
    $SumPenarikan = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS jumlah FROM simpanan WHERE kategori='Penarikan'"));
    $JumlahPenarikan = $SumPenarikan['jumlah'];
    //Jumlah Simpanan Bersih
    $JumlahSimpananBersih=$JumlahSimpananKotor-$JumlahPenarikan;
    $JumlahSimpananBersih = "" . number_format($JumlahSimpananBersih,0,',','.');
    //Jumlah Pinjaman
    $SumPinjaman = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah_pinjaman) AS jumlah_pinjaman FROM pinjaman"));
    $JumlahPinjaman = $SumPinjaman['jumlah_pinjaman'];
    $JumlahPinjaman = "" . number_format($JumlahPinjaman,0,',','.');
    include "_Page/Dashboard/ProsesHitungSimpanPinjam.php";
?>
<section class="section dashboard">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-xxl-3 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Anggota Aktif</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <?php
                                        echo '  <span class="text-muted small pt-1 fw-bold">'.$JumlahAnggotaFormat.'</span><br>';
                                        echo '  <span class="text-muted small pt-2 ps-1">Orang</span>';
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card info-card revenue-card">
                        <div class="card-body">
                            <h5 class="card-title">Simpanan Netto</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash-coin"></i>
                                </div>
                                <div class="ps-3">
                                    <?php
                                        echo '  <span class="text-muted small pt-1 fw-bold">'.$JumlahSimpananBersih.'</span><br>';
                                        echo '  <span class="text-muted small pt-2 ps-1">Rp/IDR</span>';
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">Pinjaman Total</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-bank"></i>
                                </div>
                                <div class="ps-3">
                                    <?php
                                        echo '  <span class="text-muted small pt-1 fw-bold">'.$JumlahPinjaman.'</span><br>';
                                        echo '  <span class="text-muted small pt-2 ps-1">Rp/IDR</span>';
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card info-card blue-card">
                        <div class="card-body">
                            <h5 class="card-title">Angsuran Masuk</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-bank"></i>
                                </div>
                                <div class="ps-3">
                                    <?php
                                        echo '  <span class="text-muted small pt-1 fw-bold">'.$JumlahPinjaman.'</span><br>';
                                        echo '  <span class="text-muted small pt-2 ps-1">Rp/IDR</span>';
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Reports -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <b class="card-title">
                                Simpanan & Pinjaman Anggota Thn <?php echo date ('Y'); ?>
                            </b>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title" id="NamaTitleData"></h5>
                            <div id="chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <b class="card-title">Anggota</b><br>
                            <small class="credit">
                                <code class="text text-grayish">
                                    5 Record terbaru data anggota.
                                </code>
                            </small>
                            <div class="activity mt-4">
                                <?php
                                    $RowAnggota = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM anggota"));
                                    if(empty($RowAnggota)){
                                        echo '<div class="activity-item d-flex">';
                                        echo '  Data Anggota Belum Ada';
                                        echo '</div>';
                                    }else{
                                        //Arraykan Simpanan
                                        $QryAnggota = mysqli_query($Conn, "SELECT*FROM anggota ORDER BY tanggal_masuk DESC LIMIT 5");
                                        while ($DataAnggota = mysqli_fetch_array($QryAnggota)) {
                                            $id_anggota= $DataAnggota['id_anggota'];
                                            $tanggal= $DataAnggota['tanggal_masuk'];
                                            $nip= $DataAnggota['nip'];
                                            $nama= $DataAnggota['nama'];
                                            $strtotime_anggota= strtotime($tanggal);
                                            $tanggal_anggota_format=date('d/m/y', $strtotime_anggota);
                                            echo '<div class="activity-item d-flex">';
                                            echo '  <div class="activite-label"><code class="text-info">'.$tanggal_anggota_format.'</code></div>';
                                            echo '  <i class="bi bi-circle-fill activity-badge text-success align-self-start"></i>';
                                            echo '  <div class="activity-content">';
                                            echo '      <small class="credit">'.$nama.'</small><br><small class="credit"><code class="text text-grayish">'.$nip.'</code></small>';
                                            echo '  </div>';
                                            echo '</div>';
                                        }
                                    }
                                ?>
                                
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <a href="index.php?Page=Anggota">
                                        <small>
                                            Lihat Selengapnya <i class="bi bi-three-dots"></i>
                                        </small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <b class="card-title">Simpanan</b><br>
                            <small class="credit">
                                <code class="text text-grayish">
                                    5 Record terbaru data simpanan.
                                </code>
                            </small>
                            <div class="activity mt-4">
                                <?php
                                    $RowSimpanan = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM simpanan"));
                                    if(empty($RowSimpanan)){
                                        echo '<div class="activity-item d-flex">';
                                        echo '  Data Simpanan Anggota Belum Ada';
                                        echo '</div>';
                                    }else{
                                        //Arraykan Simpanan
                                        $QrySimpanan = mysqli_query($Conn, "SELECT*FROM simpanan ORDER BY id_simpanan DESC LIMIT 5");
                                        while ($DataSimpanan = mysqli_fetch_array($QrySimpanan)) {
                                            $id_simpanan= $DataSimpanan['id_simpanan'];
                                            $tanggal= $DataSimpanan['tanggal'];
                                            $nip= $DataSimpanan['nip'];
                                            $nama= $DataSimpanan['nama'];
                                            $nama= $DataSimpanan['nama'];
                                            $jumlah_simpanan= $DataSimpanan['jumlah'];
                                            $jumlah_simpanan_format = "Rp " . number_format($jumlah_simpanan,0,',','.');
                                            $strtotime_simpanan= strtotime($tanggal);
                                            $tanggal_simpanan_format=date('d/m/y', $strtotime_simpanan);
                                            echo '<div class="activity-item d-flex">';
                                            echo '  <div class="activite-label"><code class="text-info">'.$tanggal_simpanan_format.'</code></div>';
                                            echo '  <i class="bi bi-circle-fill activity-badge text-success align-self-start"></i>';
                                            echo '  <div class="activity-content">';
                                            echo '      <small class="credit">'.$nama.'</small><br><small class="credit"><code class="text text-grayish">'.$jumlah_simpanan_format.'</code></small>';
                                            echo '  </div>';
                                            echo '</div>';
                                        }
                                    }
                                ?>
                                
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <a href="index.php?Page=SimpananWajib">
                                        <small>
                                            Lihat Selengapnya <i class="bi bi-three-dots"></i>
                                        </small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <b class="card-title">Pinjaman</b><br>
                            <small class="credit">
                                <code class="text text-grayish">
                                    5 Record terbaru data pinjaman.
                                </code>
                            </small>
                            <div class="activity mt-4">
                                <?php
                                    $RowPinjaman = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM pinjaman"));
                                    if(empty($RowPinjaman)){
                                        echo '<div class="activity-item d-flex">';
                                        echo '  Data Simpanan Anggota Belum Ada';
                                        echo '</div>';
                                    }else{
                                        //Arraykan Pinjaman
                                        $QryPinjaman = mysqli_query($Conn, "SELECT*FROM pinjaman ORDER BY id_pinjaman DESC LIMIT 5");
                                        while ($DataPinjaman = mysqli_fetch_array($QryPinjaman)) {
                                            $id_pinjaman= $DataPinjaman['id_pinjaman'];
                                            $tanggal= $DataPinjaman['tanggal'];
                                            $nip= $DataPinjaman['nip'];
                                            $nama= $DataPinjaman['nama'];
                                            $jumlah_pinjaman= $DataPinjaman['jumlah_pinjaman'];
                                            $jumlah_pinjaman_format = "Rp " . number_format($jumlah_pinjaman,0,',','.');
                                            $strtotime_pinjaman= strtotime($tanggal);
                                            $tanggal_pinjaman_format=date('d/m/y', $strtotime_pinjaman);
                                            echo '<div class="activity-item d-flex">';
                                            echo '  <div class="activite-label"><code class="text-info">'.$tanggal_pinjaman_format.'</code></div>';
                                            echo '  <i class="bi bi-circle-fill activity-badge text-success align-self-start"></i>';
                                            echo '  <div class="activity-content">';
                                            echo '      <small class="credit">'.$nama.'</small><br><small class="credit"><code class="text text-grayish">'.$jumlah_pinjaman_format.'</code></small>';
                                            echo '  </div>';
                                            echo '</div>';
                                        }
                                    }
                                ?>
                                
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <a href="index.php?Page=Tagihan">
                                        <small>
                                            Lihat Selengapnya <i class="bi bi-three-dots"></i>
                                        </small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
