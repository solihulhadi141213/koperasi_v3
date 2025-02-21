<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'oWpF1xPn8dLgRi8hRJx');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
?>
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <?php
                    echo '<div class="alert alert-info alert-dismissible fade show" role="alert">';
                    echo '  Berikut ini adalah halaman pengelolaan data anggota.<br>';
                    echo '  Anda bisa menambahkan data anggota baru, merubah informasi identitas, melihat detail informasi anggota, dan melihat riwayat transaksi anggota.<br>';
                    echo '  Untuk melihat data keluar masuk anggota, silahkan lihat opsi lanjutan dan tampilkan rekapitulasi data keluar masuk anggota.';
                    echo '  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '</div>';
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <form action="javascript:void(0);" id="ProsesBatas">
                            <div class="row">
                                <div class="col-md-8 mb-3"></div>
                                <div class="col-md-2 mb-3">
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
                                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalExport">
                                                <i class="bi bi-cloud-arrow-down"></i> Export
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalImport">
                                                <i class="bi bi-download"></i> Import
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <button type="button" class="btn btn-md btn-primary btn-block btn-rounded" data-bs-toggle="modal" data-bs-target="#ModalTambahAnggota" title="Tambah Data Anggota Baru">
                                        <i class="bi bi-plus-lg"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body" id="MenampilkanTabelAnggota">
                        <!-- Menampilkan Data Anggota -->
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>