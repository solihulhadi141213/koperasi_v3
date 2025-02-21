<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'0uITsO9Ci4O394dsIyu');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
?>
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <?php
                    echo '<div class="alert alert-info alert-dismissible fade show" role="alert">';
                    echo '  Berikut ini halaman rekapitulasi simpanan anggota.';
                    echo '  Gunakan "Filter" untuk menentukan periode tahun data.';
                    echo '  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '</div>';
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10">
                                <b class="card-title">Rekap Berdasarkan Lembaga</b>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a class="btn btn-md btn-outline-dark btn-rounded btn-block" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalFilter">
                                    <i class="bi bi-filter"></i> Filter
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="MenampilkanTabelRekapSimpanan">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <code>Silahkan Gunakan Opsi Data Untuk Memulai Menampilkan Data Rekapitulasi</code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>