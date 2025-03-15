<?php

    //Jumlah Penjualan
    $SumPenjualan = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(total) AS jumlah_penjualan FROM transaksi_jual_beli WHERE kategori='Penjualan'"));
    $JumlahPenjualan = $SumPenjualan['jumlah_penjualan'];
    $JumlahPenjualan = "" . number_format($JumlahPenjualan,0,',','.');

    //Jumlah Pembelian
    $SumPembelian = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(total) AS jumlah_pembelian FROM transaksi_jual_beli WHERE kategori='Pembelian'"));
    $JumlahPembelian = $SumPembelian['jumlah_pembelian'];
    $JumlahPembelian = "" . number_format($JumlahPembelian,0,',','.');

    //Jumlah Shu
    $SumShu = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(shu) AS jumlah_shu FROM shu_rincian"));
    $JumlahShu = $SumShu['jumlah_shu'];
    $JumlahShu = "" . number_format($JumlahShu,0,',','.');

    //Jumlah Transaksi
    $SumTransaksi = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS jumlah FROM transaksi"));
    $JumlahTransaksi = $SumTransaksi['jumlah'];
    $JumlahTransaksi = "Rp " . number_format($JumlahTransaksi,0,',','.');

    //Untuk Menampilkan grafik maka dibuat dulu file json
    include "_Page/Dashboard/ProsesHitungSimpanPinjam.php";
?>
<div class="pagetitle">
    <h1>
        <a href="">
            <i class="bi bi-grid"></i> Dashboard
        </a>
    </h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>
<section class="section dashboard">
    <div class="row">
        <div class="col-md-12" id="notifikasi_proses">
            <!-- Kejadian Kegagalan Menampilkan Data Akan Ditampilkan Disini -->
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-xxl-4 col-md-6 col-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Barang</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-box"></i>
                                        </div>
                                        <div class="ps-3">
                                            <span class="text-muted small pt-1 fw-bold" id="put_count_rp_barang"></span><br>
                                            <span class="text-muted small pt-2 ps-1" id="put_count_item_barang"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6 col-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Anggota</h5>
                                    <div class="d-flex align-items-center gap-3">
                                        <!-- Icon -->
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <!-- Data Info -->
                                        <div>
                                            <div class="d-flex">
                                                <span class="text-success small pt-2" style="width: 60px;">Aktif</span>
                                                <span class="text-muted small pt-2" id="put_anggota_aktif">2.500</span>
                                            </div>
                                            <div class="d-flex">
                                                <span class="text-danger small pt-2" style="width: 60px;">Keluar</span>
                                                <span class="text-muted small pt-2" id="put_anggota_keluar">3.500</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6 col-6">
                            <div class="card info-card revenue-card">
                                <div class="card-body">
                                    <h5 class="card-title">Simpanan</h5>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-cash-coin"></i>
                                        </div>
                                        <div class="ps-3">
                                            <span class="text-info small pt-2 ps-1" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                data-bs-custom-class="custom-tooltip" 
                                                data-bs-title="Jumlah Simpanan Anggota Setelah Dikurangi Penarikan" 
                                                id="put_simpanan_anggota">
                                                <!-- Menampilkan Simpanan Anggota -->
                                            </span><br>
                                            <span class="text-warning small pt-2 ps-1" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                data-bs-custom-class="custom-tooltip" 
                                                data-bs-title="Jumlah Total Penarikan Dana Simpanan" 
                                                id="put_penarikan_dana">
                                                <!-- Menampilkan Penarikan Dana Anggota -->
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6 col-6">
                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title">Pinjaman</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-bank"></i>
                                        </div>

                                        <div class="ps-3">
                                            <span class="text-dark small pt-2 ps-1"  
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                data-bs-custom-class="custom-tooltip" 
                                                data-bs-title="Jumlah Total Pinjaman Anggota Yang Belum Lunas"  
                                                id="put_pinjaman_anggota">
                                            </span><br>
                                            <span class="text-muted small pt-2 ps-1" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                data-bs-custom-class="custom-tooltip" 
                                                data-bs-title="Jumlah Sesi Pinjaman Belum Lunas" 
                                                id="put_sesi_pinjaman">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6 col-6">
                            <div class="card info-card blue-card">
                                <div class="card-body">
                                    <h5 class="card-title">Angsuran</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-bank"></i>
                                        </div>
                                        <div class="ps-3">
                                            <span class="text-muted small pt-2 ps-1"  
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                data-bs-custom-class="custom-tooltip" 
                                                data-bs-title="Jumlah Nominal Angsuran Masuk" 
                                                id="put_nominal_angsuran">
                                            </span><br>
                                            <span class="text-muted small pt-2 ps-1"  
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                data-bs-custom-class="custom-tooltip" 
                                                data-bs-title="Jumlah Record Angsuran Masuk" 
                                                id="put_record_angsuran">
                                            </span><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6 col-6">
                            <div class="card info-card purple-card">
                                <div class="card-body">
                                    <h5 class="card-title">Penjualan</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-cart-dash"></i>
                                        </div>
                                        <div class="ps-3">
                                            <?php
                                                echo '  <span class="text-muted small pt-1 fw-bold">'.$JumlahPenjualan.'</span><br>';
                                                echo '  <span class="text-muted small pt-2 ps-1">Rp/IDR</span>';
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6 col-6">
                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title">Pembelian</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-cart-plus"></i>
                                        </div>
                                        <div class="ps-3">
                                            <?php
                                                echo '  <span class="text-muted small pt-1 fw-bold">'.$JumlahPembelian.'</span><br>';
                                                echo '  <span class="text-muted small pt-2 ps-1">Rp/IDR</span>';
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6 col-6">
                            <div class="card info-card revenue-card">
                                <div class="card-body">
                                    <h5 class="card-title">Bagi Hasil</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-calculator"></i>
                                        </div>
                                        <div class="ps-3">
                                            <?php
                                                echo '  <span class="text-muted small pt-1 fw-bold">'.$JumlahShu.'</span><br>';
                                                echo '  <span class="text-muted small pt-2 ps-1">Rp/IDR</span>';
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6 col-6">
                            <div class="card info-card transsaction-card">
                                <div class="card-body">
                                    <h5 class="card-title">Transaksi Operasional</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-arrow-left-right"></i>
                                        </div>
                                        <div class="ps-3">
                                            <?php
                                                echo '  <span class="text-muted small pt-1 fw-bold">'.$JumlahTransaksi.'</span><br>';
                                                echo '  <span class="text-muted small pt-2 ps-1">Rp/IDR</span>';
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-12">
                        <div class="card" id="card_jam_menarik">
                            <div class="card-body">
                                <div id="tanggal_menarik"></div>
                                <div id="jam_menarik">00:00:00</div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <b class="card-title"># Pemberitahuan Sistem</b> 
                            </div>
                            <div class="card-body">
                                <?php
                                    //Hitung Jumlah Barang Yang Hampir Habis
                                    $sqlStokMinimum = "SELECT COUNT(*) AS total_minimum FROM barang WHERE stok_barang < stok_minimum";
                                    $resultMinimum = $Conn->query($sqlStokMinimum);
                                    $totalMinimum = $resultMinimum->fetch_assoc()['total_minimum'] ?? 0;
                                    if(!empty($totalMinimum)){
                                        echo '
                                            <div class="alert alert-warning mb-3 alert-dismissible fade show">
                                                <small>
                                                    Terdapat '.$totalMinimum.' item barang yang hampir habis.
                                                </small>
                                            </div>
                                        ';
                                    }else{
                                        echo '
                                            <div class="alert alert-success mb-3 alert-dismissible fade show">
                                                <small>
                                                    <i class="bi bi-check"></i> Stok barang dalam keadaan baik.
                                                </small>
                                            </div>
                                        ';
                                    }
                                    // Tanggal saat ini dalam format Y-m-d
                                    $currentDate = date('Y-m-d');

                                    // Query untuk menghitung jumlah item barang yang sudah expired
                                    $sqlExpired = "SELECT COUNT(*) AS total_expired FROM barang_bacth WHERE reminder_date <= ?";
                                    $stmt = $Conn->prepare($sqlExpired);
                                    $stmt->bind_param("s", $currentDate);
                                    $stmt->execute();
                                    $resultExpired = $stmt->get_result();

                                    if ($resultExpired) {
                                        $totalExpired = intval($resultExpired->fetch_assoc()['total_expired'] ?? 0);
                                        
                                        if ($totalExpired > 0) {
                                            echo '
                                                <div class="alert alert-danger mb-3 alert-dismissible fade show">
                                                    <small>
                                                        <i class="bi bi-exclamation-octagon"></i> Terdapat '.$totalExpired.' item barang expire.
                                                    </small>
                                                </div>
                                            ';
                                        } else {
                                            echo '
                                                <div class="alert alert-success mb-3 alert-dismissible fade show">
                                                    <small>
                                                        <i class="bi bi-check-circle"></i> Tidak ada barang yang expire.
                                                    </small>
                                                </div>
                                            ';
                                        }
                                    } else {
                                        echo '
                                            <div class="alert alert-danger mb-3 alert-dismissible fade show">
                                                <small>
                                                    <i class="bi bi-x-circle"></i> Gagal mengambil data barang expired.
                                                </small>
                                            </div>
                                        ';
                                    }
                                    // Tanggal dan waktu saat ini dalam format Y-m-d H:i:s
                                    $currentDateTime = date('Y-m-d H:i:s');

                                    // Query untuk menghitung jumlah data yang sudah expired
                                    $sqlExpiredAccess = "SELECT COUNT(*) AS total_expired FROM akses_login WHERE date_expired > ?";
                                    $stmt = $Conn->prepare($sqlExpiredAccess);
                                    $stmt->bind_param("s", $currentDateTime);
                                    $stmt->execute();
                                    $resultExpiredAccess = $stmt->get_result();

                                    if ($resultExpiredAccess) {
                                        $totalExpiredAccess = intval($resultExpiredAccess->fetch_assoc()['total_expired'] ?? 0);
                                        
                                        if ($totalExpiredAccess > 0) {
                                            echo '
                                                <div class="alert alert-info mb-3 alert-dismissible fade show">
                                                    <small>
                                                        <i class="bi bi-person-circle"></i> Terdapat '.$totalExpiredAccess.' pengguna sedang login.
                                                    </small>
                                                </div>
                                            ';
                                        } else {
                                            echo '
                                                <div class="alert alert-success mb-3 alert-dismissible fade show">
                                                    <small>
                                                        <i class="bi bi-unlock"></i> Tidak ada pengguna yang login.
                                                    </small>
                                                </div>
                                            ';
                                        }
                                    } else {
                                        echo '
                                            <div class="alert alert-danger mb-3 alert-dismissible fade show">
                                                <small>
                                                    <i class="bi bi-x-circle"></i> Gagal mengambil data akses login yang expired.
                                                </small>
                                            </div>
                                        ';
                                    }
                                    $stmt->close();
                                ?>
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
