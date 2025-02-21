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
?>
            <input type="hidden" name="id_shu_session" id="id_shu_session" value="<?php echo "$id_shu_session"; ?>">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="sesi_shu_edit">Nama Sesi Bagi Hasil</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="sesi_shu" id="sesi_shu_edit" class="form-control" placeholder="Contoh: SHU Tahun 2022" value="<?php echo "$sesi_shu"; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="periode_hitung1_edit">Periode Awal Perhitungan</label>
                </div>
                <div class="col-md-8">
                    <input type="date" name="periode_hitung1" id="periode_hitung1_edit" class="form-control" value="<?php echo "$periode_hitung1"; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="periode_hitung2_edit">Periode Akhir Perhitungan</label>
                </div>
                <div class="col-md-8">
                    <input type="date" name="periode_hitung2" id="periode_hitung2_edit" class="form-control" value="<?php echo "$periode_hitung2"; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="persen_usaha_edit">Persen Sisa Usaha (%)</label>
                </div>
                <div class="col-md-8">
                    <input type="number" min="0" max="100" name="persen_usaha" id="persen_usaha_edit" class="form-control" placeholder="[0-100]" value="<?php echo "$persen_usaha"; ?>">
                    <small class="credit">
                        <code class="text text-grayish">Nilai persentase (%) sisa usaha yang disisihkan untuk SHU</code>
                    </small>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="persen_modal_edit">Persen Sisa Modal (%)</label>
                </div>
                <div class="col-md-8">
                    <input type="number" min="0" max="100" name="persen_modal" id="persen_modal_edit" class="form-control" placeholder="[0-100]" value="<?php echo "$persen_modal"; ?>">
                    <small class="credit">
                        <code class="text text-grayish">Nilai persentase (%) sisa modal yang disisihkan untuk SHU</code>
                    </small>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="persen_pinjaman_edit">Persen Sisa Modal (%)</label>
                </div>
                <div class="col-md-8">
                    <input type="number" min="0" max="100" name="persen_pinjaman" id="persen_pinjaman_edit" class="form-control" placeholder="[0-100]" value="<?php echo "$persen_pinjaman"; ?>">
                    <small class="credit">
                        <code class="text text-grayish">Nilai persentase (%) sisa jasa pinjaman yang disisihkan untuk SHU</code>
                    </small>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="alokasi_nyata_edit">Alokasi Sisa Usaha (Rp)</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="alokasi_nyata" id="alokasi_nyata_edit" class="form-control format_uang" value="<?php echo "$alokasi_nyata"; ?>">
                    <small class="credit">
                        <code class="text text-grayish">Nilai nominal kas usaha yang disisihkan untuk SHU</code>
                    </small>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="status_edit">Status Pembagian SHU</label>
                </div>
                <div class="col-md-8">
                    <select name="status" id="status_edit" class="form-control">
                        <option <?php if($status==""){echo "selected";} ?> value="">Pilih</option>
                        <option <?php if($status=="Pending"){echo "selected";} ?> value="Pending">Masih Pending</option>
                        <option <?php if($status=="Realisasi"){echo "selected";} ?> value="Realisasi">Sudah Realisasi</option>
                    </select>
                </div>
            </div>
            <script>
                $('#alokasi_nyata_edit').on('keypress', function(e) {
                    // Hanya mengizinkan angka (0-9)
                    if (e.which < 48 || e.which > 57) {
                        e.preventDefault();
                    }
                });
            </script>
<?php 
        } 
    } 
?>