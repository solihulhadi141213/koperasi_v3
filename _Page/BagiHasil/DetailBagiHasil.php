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
<?php
    if(empty($_GET['id'])){
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
        echo '  Tidak ada ID Sesi Bagi Hasil Yang Ditampilkan.';
        echo '</div>';
    }else{
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
        echo '  <small>';
        echo '      Berikut ini adalah detail informasi bagi hasil (SHU) yang sudah anda buat.';
        echo '      Pada halaman ini anda bisa melihat uraian lengkap pembagian hasil (SHU) untuk masing-masing anggota.';
        echo '      Jangan lupa juga untuk melengkapi informasi jurnal keuangan untuk mencatat transaksi pada neraca saldo.';
        echo '      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '  </small>';
        echo '</div>';
        $id_shu_session=$_GET['id'];
        //Buka Data Bagi Hasil
        $id_shu_session=GetDetailData($Conn,'shu_session','id_shu_session',$id_shu_session,'id_shu_session');
        $uuid_shu_session=GetDetailData($Conn,'shu_session','id_shu_session',$id_shu_session,'uuid_shu_session');
        $sesi_shu=GetDetailData($Conn,'shu_session','id_shu_session',$id_shu_session,'sesi_shu');
        $periode_hitung1=GetDetailData($Conn,'shu_session','id_shu_session',$id_shu_session,'periode_hitung1');
        $periode_hitung2=GetDetailData($Conn,'shu_session','id_shu_session',$id_shu_session,'periode_hitung2');
        $modal_anggota=GetDetailData($Conn,'shu_session','id_shu_session',$id_shu_session,'modal_anggota');
        $penjualan=0;
        $pinjaman=GetDetailData($Conn,'shu_session','id_shu_session',$id_shu_session,'pinjaman');
        $jasa_modal_anggota=GetDetailData($Conn,'shu_session','id_shu_session',$id_shu_session,'jasa_modal_anggota');
        $laba_penjualan=0;
        $jasa_pinjaman=GetDetailData($Conn,'shu_session','id_shu_session',$id_shu_session,'jasa_pinjaman');
        $persen_usaha=GetDetailData($Conn,'shu_session','id_shu_session',$id_shu_session,'persen_usaha');
        $persen_modal=GetDetailData($Conn,'shu_session','id_shu_session',$id_shu_session,'persen_modal');
        $persen_pinjaman=GetDetailData($Conn,'shu_session','id_shu_session',$id_shu_session,'persen_pinjaman');
        $alokasi_hitung=GetDetailData($Conn,'shu_session','id_shu_session',$id_shu_session,'alokasi_hitung');
        $alokasi_nyata=GetDetailData($Conn,'shu_session','id_shu_session',$id_shu_session,'alokasi_nyata');
        $status=GetDetailData($Conn,'shu_session','id_shu_session',$id_shu_session,'status');
        //Format Data
        if(!empty($modal_anggota)){
            $modal_anggota = "" . number_format($modal_anggota,0,',','.');
        }else{
            $modal_anggota =0;
        }
        if(!empty($penjualan)){
            $penjualan = "" . number_format($penjualan,0,',','.');
        }else{
            $penjualan =0;
        }
        if(!empty($pinjaman)){
            $pinjaman_rp = "" . number_format($pinjaman,0,',','.');
        }else{
            $pinjaman_rp = 0;
        }
        if(!empty($jasa_modal_anggota)){
            $jasa_modal_anggota_rp = "" . number_format($jasa_modal_anggota,0,',','.');
        }else{
            $jasa_modal_anggota_rp = 0;
        }
        if(!empty($laba_penjualan)){
            $laba_penjualan_rp = "" . number_format($laba_penjualan,0,',','.');
        }else{
            $laba_penjualan_rp = 0;
        }
        if(!empty($jasa_pinjaman)){
            $jasa_pinjaman_rp = "" . number_format($jasa_pinjaman,0,',','.');
        }else{
            $jasa_pinjaman_rp = 0;
        }
        if(!empty($persen_usaha)){
            $persen_usaha_rp = "" . number_format($persen_usaha,0,',','.');
        }else{
            $persen_usaha_rp = 0;
        }
        if(!empty($alokasi_hitung)){
            $alokasi_hitung_rp = "" . number_format($alokasi_hitung,0,',','.');
        }else{
            $alokasi_hitung_rp = 0;
        }
        if(!empty($alokasi_nyata)){
            $alokasi_nyata_rp = "" . number_format($alokasi_nyata,0,',','.');
        }else{
            $alokasi_nyata_rp = 0;
        }
        $strtotime1=strtotime($periode_hitung1);
        $strtotime2=strtotime($periode_hitung2);
        $periode_hitung1=date('d/m/Y',$strtotime1);
        $periode_hitung2=date('d/m/Y',$strtotime2);
        //Cek Status Jurnal
        $JumlahJurnal = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM jurnal WHERE uuid='$uuid_shu_session'"));
        //Jumlah Anggota
        $JumlahRincian = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM shu_rincian WHERE id_shu_session='$id_shu_session'"));
        if(!empty($JumlahRincian)){
            $JumlahRincian = "" . number_format($JumlahRincian,0,',','.');
        }else{
            $JumlahRincian =0;
        }
        
        //Label Jurnal Ada/Tidak Ada
        if(empty($JumlahJurnal)){
            $LabelJurnal='<span class="text-grayish">No Jurnal</span>';
        }else{
            $LabelJurnal='<span class="text-sucess"> '.$JumlahJurnal.' Record</span>';
        }
        //Label Status
        if($status=="Pending"){
            $LabelStatus='<span class="badge badge-warning">Pending</span>';
        }else{
            $LabelStatus='<span class="badge badge-success">'.$status.'</span>';
        }
?>
    <input type="hidden" name="id_shu_session" id="GetIdShuSession" value="<?php echo "$id_shu_session"; ?>">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                    <b class="card-title">
                        <i class="bi bi-info-circle"></i> Detail Sesi Bagi Hasil
                    </b>
                </div>
                <div class="col-md-2">
                    <a href="index.php?Page=BagiHasil" class="btn btn-md btn-dark btn-rounded btn-block">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row mb-3">
                            <div class="col col-md-6">Nama Sesi SHU</div>
                            <div class="col col-md-6">
                                <code class="text text-grayish"><?php echo $sesi_shu; ?></code>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col col-md-6">Periode SHU</div>
                            <div class="col col-md-6">
                                <code class="text text-grayish"><?php echo "$periode_hitung1-$periode_hitung2"; ?></code>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col col-md-6">Jumlah Modal Anggota</div>
                            <div class="col col-md-6">
                                <code class="text text-grayish"><?php echo "$modal_anggota"; ?></code>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col col-md-6">Jumlah Pinjaman Anggota</div>
                            <div class="col col-md-6">
                                <code class="text text-grayish"><?php echo "$pinjaman_rp"; ?></code>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col col-md-6">Jasa Modal Simpanan</div>
                            <div class="col col-md-6">
                                <code class="text text-grayish"><?php echo "$jasa_modal_anggota_rp"; ?></code>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col col-md-6">Jasa Pinjaman</div>
                            <div class="col col-md-6">
                                <code class="text text-grayish"><?php echo "$jasa_pinjaman_rp"; ?></code>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mb-3">
                            <div class="col col-md-6">Persentase Usaha</div>
                            <div class="col col-md-6">
                                <code class="text text-grayish"><?php echo "$persen_usaha %"; ?></code>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col col-md-6">Persentase Modal</div>
                            <div class="col col-md-6">
                                <code class="text text-grayish"><?php echo "$persen_usaha %"; ?></code>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col col-md-6">Persentase Pinjaman</div>
                            <div class="col col-md-6">
                                <code class="text text-grayish"><?php echo "$persen_pinjaman %"; ?></code>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col col-md-6">Alokasi SHU</div>
                            <div class="col col-md-6">
                                <code class="text text-grayish"><?php echo "$alokasi_nyata_rp"; ?></code>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col col-md-6">Status</div>
                            <div class="col col-md-6">
                                <?php echo "$LabelStatus"; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link w-100 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-home" type="button" role="tab" aria-controls="home" aria-selected="true">
                                Rincian Anggota
                            </button>
                        </li>
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-profile" type="button" role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">
                                Jurnal
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                        <div class="tab-pane fade active show" id="bordered-justified-home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row">
                                <div class="col-md-12" id="MenampilkanRincianBagiHasil">
                                    <!-- Menampilkan Rincian Bagi Hasil Disini -->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="bordered-justified-profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row">
                                <div class="col-md-12" id="MenampilkanJurnalBagiHasil">
                                    <!-- Menampilkan Jurnal Disini -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>