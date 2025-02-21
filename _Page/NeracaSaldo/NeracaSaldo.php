<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'incMmh5yyCmCs4IwCYz');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
?>
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <?php
                    echo '<div class="alert alert-info alert-dismissible fade show" role="alert">';
                    echo '  Berikut ini adalah halaman laporan neraca saldo.<br>';
                    echo '  Laporan ini menampilkan akumulasi transaksi saldo berdasarkan aku perkiraan.<br>';
                    echo '  Untuk menampilkan laporan, silahkan pilih periode awal dan akhir dari transaksi yang diinginkan.';
                    echo '  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '</div>';
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <form action="javascript:void(0);" id="ProsesLaporanNeracaSaldo">
                            <div class="row">
                                <div class="col-md-5 mt-3">
                                    <input type="date" name="periode1" id="periode1" class="form-control">
                                    <small>Periode Awal</small>
                                </div>
                                <div class="col-md-5 mt-3">
                                    <input type="date" name="periode2" id="periode2" class="form-control">
                                    <small>Periode Akhir</small>
                                </div>
                                <div class="col-md-2 mt-3">
                                    <button type="submit" class="btn btn-md btn-dark btn-block btn-rounded">
                                        <i class="bi bi-search"></i> Tampilkan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12" id="MenampilkanTabelNeracaSaldo">
                                <?php
                                    echo '<div class="alert alert-danger text-center" role="alert">';
                                    echo ' <b>Keterangan :</b><br>';
                                    echo ' Silahkan isi periode awal dan periode akhir transaksi yang ingin anda tampilkan pada laporan neraca.<br>';
                                    echo '</div>';
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>