<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'ihTHNu1ROJ28tpP2LlH');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
?>
    <div class="pagetitle">
        <h1>
            <a href="">
                <i class="bi bi-calculator"></i> Bagi Hasil</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active"> Bagi Hasil</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <?php
                    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
                    echo '  <small>';
                    echo '      Berikut ini adalah halaman bagi hasil.';
                    echo '      Anda bisa menambahkan sesi bagi hasil baru, mengatur persentase pembagian dan menentukan periode perhitungan.';
                    echo '      Anda juga bisa melihat rincian pembagian jasa anggota dengan memilih salah satu sesi bagi hasil.';
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
                            <div class="col-md-8 mb-3">
                                <b class="card-title">
                                    <i class="bi bi-calendar"></i> Data Sesi Bagi Hasil (SHU)
                                </b>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a class="btn btn-md btn-outline-dark btn-rounded btn-block" href="javascript:void(0);" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalFilter" title="Filter Data Bagi Hasil">
                                    <i class="bi bi-funnel"></i> Filter
                                </a>
                            </div>
                            <div class="col-md-2 text-center mb-3">
                                <button type="button" class="btn btn-md btn-primary btn-block btn-rounded" data-bs-toggle="modal" data-bs-target="#ModalTambahBagiHasil" title="Tambah Sesi Pembagian">
                                    <i class="bi bi-plus-lg"></i> Tambah Sesi
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="MenampilkanTabelBagiHasil">
                        <!-- Menampilkan Tabel Bagi Hasil -->
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>