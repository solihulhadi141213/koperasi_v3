<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'Ay1ssiaoRqiK70E9HM6');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
        if(empty($_GET['id'])){
            include "_Page/Error/PageNotFound.php";
        }else{
            $id_transaksi_jual_beli=$_GET['id'];
            
?>
            <div class="pagetitle">
                <h1>
                    <a href="">
                        <i class="bi bi-cart-plus"></i> Transaksi Penjualan</a>
                    </a>
                </h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php?Page=Penjualan">Penjualan</a></li>
                        <li class="breadcrumb-item active">Detail Penjualan</li>
                    </ol>
                </nav>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <small>
                            Berikut ini halaman detail transaksi yang menampilkan secara rinci informasi transaksi uraian item barang.
                            Pada halaman ini anda bisa melakukan perubahan pada informasi transaksi dan item barang. 
                            Pada halaman ini juga tersedia tombol 'Cetak' untuk melakukan export data transaksi beserta uraiannya.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </small>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-8 mb-2 mt-2">
                                    <b class="card-title"># Detail Transaksi</b>
                                </div>
                                <div class="col-4 mb-2 mt-2 text-end">
                                    <button type="button" class="btn btn-md btn-floating btn-grayish button_kembali" title="Kembali">
                                        <i class="bi bi-chevron-left"></i>
                                    </button>
                                    <button type="button" class="btn btn-md btn-floating btn-outline-grayish" data-bs-toggle="modal" data-bs-target="#ModalEditTransaksi" data-id="<?php echo $id_transaksi_jual_beli; ?>" title="Edit Transaksi">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-md btn-floating btn-outline-grayish" data-bs-toggle="modal" data-bs-target="#ModalHapusTransaksi" data-id="<?php echo $id_transaksi_jual_beli; ?>" title="Hapus Transaksi">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">

                        </div>

                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-8 mb-2 mt-2">
                                    <b class="card-title"># Rincian Transaksi</b>
                                </div>
                                <div class="col-4 mb-2 mt-2 text-end">
                                    <button type="button" class="btn btn-md btn-floating btn-grayish button_kembali" title="Kembali">
                                        <i class="bi bi-chevron-left"></i>
                                    </button>
                                    <button type="button" class="btn btn-md btn-floating btn-outline-grayish" data-bs-toggle="modal" data-bs-target="#ModalEditTransaksi" data-id="<?php echo $id_transaksi_jual_beli; ?>" title="Edit Transaksi">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-md btn-floating btn-outline-grayish" data-bs-toggle="modal" data-bs-target="#ModalHapusTransaksi" data-id="<?php echo $id_transaksi_jual_beli; ?>" title="Hapus Transaksi">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">

                        </div>
                        
                    </div>
                </div>
            </div>
<?php
        }
    }
?>