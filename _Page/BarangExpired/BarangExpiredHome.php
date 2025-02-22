<?php
    if(!empty($_GET['short_expired'])){
        $short_expired=$_GET['short_expired'];
    }else{
        $short_expired="";
    }
?>
<div class="pagetitle">
    <h1>
        <a href="">
            <i class="bi bi-calendar"></i> Batch & Expired</a>
        </a>
    </h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Batch & Expired</li>
        </ol>
    </nav>
</div>
<section class="section dashboard">
    <div class="row">
        <div class="col-md-12">
            <?php
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
                echo '  <small>';
                echo '      Berikut ini adalah halaman untuk mengelola data batch dan tanggal expired barang.';
                echo '      Anda bisa menambahkan data batch berikut dengan tanggal expired.';
                echo '      Isi tanggal notifikasi agar sistem memberikan peringatan sebelum barang expired.';
                echo '      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                echo '  </small>';
                echo '</div>';
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-8"></div>
                        <div class="col-6 col-md-2 mb-2">
                            <a class="btn btn-md btn-outline-dark btn-rounded btn-block" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots"></i> Opsi Lanjutan
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                                <li class="dropdown-header text-start">
                                    <h6>Option</h6>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalFilter">
                                        <i class="bi bi-funnel"></i> Filter
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalDatabaseBarang">
                                        <i class="bi bi-cloud-arrow-down"></i> Export/Import
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-6 col-md-2 mb-2">
                            <button type="button" class="btn btn-md btn-primary btn-block btn-rounded" data-bs-toggle="modal" data-bs-target="#ModalPilihBarang" title="Tambah Data Expired Barang">
                                <i class="bi bi-calendar-plus"></i> Tambah
                            </button>
                        </div>
                    </div>
                </div>
                <div id="MenampilkanTabelBarangExpired">

                </div>
            </div>
        </div>
    </div>
</section>