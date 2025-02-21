<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/Session.php";
    if(empty($SessionIdAkses)){
        echo '<div class="row">';
        echo '  <div class="col-md-12 mb-3 text-center">';
        echo '      <small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang</small>';
        echo '  </div>';
        echo '</div>';
    }else{
        if(empty($_POST['id_shu_session'])){
            echo '<div class="row">';
            echo '  <div class="col-md-12 mb-3 text-center">';
            echo '      <small class="text-danger">Tidak ada data yang ditangkap oleh sistem</small>';
            echo '  </div>';
            echo '</div>';
        }else{
            $id_shu_session=$_POST['id_shu_session'];
            //Buka data
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
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col col-md-4">Nama Sesi SHU</div>
                    <div class="col col-md-8">
                        <code class="text text-grayish"><?php echo $sesi_shu; ?></code>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col col-md-4">Periode SHU</div>
                    <div class="col col-md-8">
                        <code class="text text-grayish"><?php echo "$periode_hitung1-$periode_hitung2"; ?></code>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col col-md-4">Jumlah Modal Anggota</div>
                    <div class="col col-md-8">
                        <code class="text text-grayish"><?php echo "$modal_anggota"; ?></code>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col col-md-4">Jumlah Pinjaman Anggota</div>
                    <div class="col col-md-8">
                        <code class="text text-grayish"><?php echo "$pinjaman_rp"; ?></code>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col col-md-4">Jasa Modal Simpanan</div>
                    <div class="col col-md-8">
                        <code class="text text-grayish"><?php echo "$jasa_modal_anggota_rp"; ?></code>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col col-md-4">Jasa Pinjaman</div>
                    <div class="col col-md-8">
                        <code class="text text-grayish"><?php echo "$jasa_pinjaman_rp"; ?></code>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col col-md-4">Persentase Usaha</div>
                    <div class="col col-md-8">
                        <code class="text text-grayish"><?php echo "$persen_usaha %"; ?></code>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col col-md-4">Persentase Modal</div>
                    <div class="col col-md-8">
                        <code class="text text-grayish"><?php echo "$persen_usaha %"; ?></code>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col col-md-4">Persentase Pinjaman</div>
                    <div class="col col-md-8">
                        <code class="text text-grayish"><?php echo "$persen_pinjaman %"; ?></code>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col col-md-4">Alokasi SHU</div>
                    <div class="col col-md-8">
                        <code class="text text-grayish"><?php echo "$alokasi_nyata_rp"; ?></code>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col col-md-4">Status</div>
                    <div class="col col-md-8">
                        <?php echo "$LabelStatus"; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="index.php?Page=BagiHasil&Sub=DetailBagiHasil&id=<?php echo $id_shu_session;?>" class="btn btn-primary btn-rounded">
                    <i class="bi bi-three-dots"></i> Lihat Selengkapnya
                </a>
                <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
<?php 
        } 
    } 
?>