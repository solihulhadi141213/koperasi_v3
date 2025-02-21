<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'X61viKZwNQuNrQ1Vrrg');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
?>
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <?php
                    echo '<div class="alert alert-info alert-dismissible fade show" role="alert">';
                    echo '  Berikut ini halaman data tagihan pinjaman anggota.';
                    echo '  Data yang ditampilkan adalah data pinjaman yang masih berjalan.';
                    echo '  Sistem akan melakukan filter dan perhitungan jumlah angsuran yang belum dibayar.';
                    echo '  Lihat masing-masing detail data untukk mengetahui rincian tunggakan.';
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
                                <div class="col-md-10"></div>
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
                                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalExportTagihan">
                                                <i class="bi bi-cloud-arrow-down"></i> Export
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body" id="MenampilkanTabelTagihan">

                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>