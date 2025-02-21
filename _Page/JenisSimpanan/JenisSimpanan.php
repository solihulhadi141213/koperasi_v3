<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'TruxAyhHOhvTrVYs6No');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
?>
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <?php
                    echo '<div class="alert alert-info alert-dismissible fade show" role="alert">';
                    echo '  Berikut ini adalah halaman untuk mengelola jenis simpanan.';
                    echo '  Anda bisa menambahkan data jenis simpanan secara dinamis, mengatur auto jurnal pada masing-masing jenis simpanan ';
                    echo '  dan menentukan apakah simapanan tersebut bersifat rutin atau sukarela.';
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
                            <div class="col-md-10 mb-3"></div>
                            <div class="col-md-2 mb-3">
                                <button type="button" class="btn btn-md btn-primary btn-block btn-rounded" data-bs-toggle="modal" data-bs-target="#ModalTambahJenisSimpanan" title="Tambah Jenis Simpanan">
                                    <i class="bi bi-plus-lg"></i> Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="MenampilkanTabelJenisSimpanan">

                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>