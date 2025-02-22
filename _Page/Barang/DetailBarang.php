<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'sPkDxRYJPn1A8K24ki2');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
?>
    <div class="pagetitle">
        <h1>
            <a href="">
                <i class="bi bi-box"></i> Barang</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="index.php?Page=Barang">Barang</a></li>
                <li class="breadcrumb-item active">Detail Barang</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <?php
                    $id_barang="";
                    if(empty($_GET['id'])){
                        echo '
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <small>
                                    ID Barang Tidak Boleh Kosong! Tidak Ada Data Barang Dapat Ditampilkan Pada Halaman Ini.
                                </small>
                            </div>
                        ';
                    }else{
                        $id_barang=$_GET['id'];
                        echo '
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <small>
                                    Berikut ini adalah halaman detail barang.
                                    Pada halaman ini dapat mengelola data batch dan expire date barang secara spesifik. 
                                    Selain itu juga terdapat riwayat transaksi barang yang menampilkan daftar riwayat penjualan dan pembelian barang sepanjang waktu.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </small>
                            </div>
                        ';
                    }
                    echo '<input type="hidden" id="put_id_barang_in_line_page" value="'.$id_barang.'">';
                ?>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-10">
                                <b class="card-title">
                                    <i class="bi bi-info-circle"></i> Detail Barang
                                </b>
                            </div>
                            <div class="col-2">
                                <a href="index.php?Page=Barang" class="btn btn-md btn-rounded btn-dark w-100">
                                    <i class="bi bi-chevron-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="DetailBarangOnPage">

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12">
                                <b class="card-title">
                                    <i class="bi bi-calendar-check"></i> Batch & Expired
                                </b>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="TabelExpiredDate">

                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>