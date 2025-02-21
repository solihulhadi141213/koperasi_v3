<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'eQhEWIf1fV6xwMNr8J9');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
        //Membuka data auto jurnal Simpanan
        $QrySimpanan = mysqli_query($Conn,"SELECT * FROM auto_jurnal WHERE kategori_transaksi='Simpanan'")or die(mysqli_error($Conn));
        $DataSimpanan = mysqli_fetch_array($QrySimpanan);
        if(!empty($DataSimpanan['id_auto_jurnal'])){
            $id_auto_jurnal1= $DataSimpanan['id_auto_jurnal'];
            $kategori_transaksi1= $DataSimpanan['kategori_transaksi'];
            $debet_id1= $DataSimpanan['debet_id'];
            $debet_name1= $DataSimpanan['debet_name'];
            $kredit_id1= $DataSimpanan['kredit_id'];
            $kredit_name1= $DataSimpanan['kredit_name'];
        }else{
            $id_auto_jurnal1="";
            $kategori_transaksi1="";
            $debet_id1="";
            $debet_name1="";
            $kredit_id1="";
            $kredit_name1="";
        }
        //Membuka data auto jurnal Penarikan
        $QryPenarikan= mysqli_query($Conn,"SELECT * FROM auto_jurnal WHERE kategori_transaksi='Penarikan'")or die(mysqli_error($Conn));
        $DataPenarikan = mysqli_fetch_array($QryPenarikan);
        if(!empty($DataPenarikan['id_auto_jurnal'])){
            $id_auto_jurnal2= $DataPenarikan['id_auto_jurnal'];
            $kategori_transaksi2= $DataPenarikan['kategori_transaksi'];
            $debet_id2= $DataPenarikan['debet_id'];
            $debet_name2= $DataPenarikan['debet_name'];
            $kredit_id2= $DataPenarikan['kredit_id'];
            $kredit_name2= $DataPenarikan['kredit_name'];
        }else{
            $id_auto_jurnal2="";
            $kategori_transaksi2="";
            $debet_id2="";
            $debet_name2="";
            $kredit_id2="";
            $kredit_name2="";
        }
        //Membuka data auto jurnal pinjaman
        $QryPinjaman= mysqli_query($Conn,"SELECT * FROM auto_jurnal WHERE kategori_transaksi='Pinjaman'")or die(mysqli_error($Conn));
        $DataPinjaman = mysqli_fetch_array($QryPinjaman);
        if(!empty($DataPinjaman['id_auto_jurnal'])){
            $id_auto_jurnal4= $DataPinjaman['id_auto_jurnal'];
            $kategori_transaksi4= $DataPinjaman['kategori_transaksi'];
            $debet_id4= $DataPinjaman['debet_id'];
            $debet_name4= $DataPinjaman['debet_name'];
            $kredit_id4= $DataPinjaman['kredit_id'];
            $kredit_name4= $DataPinjaman['kredit_name'];
        }else{
            $id_auto_jurnal4="";
            $kategori_transaksi4="";
            $debet_id4="";
            $debet_name4="";
            $kredit_id4="";
            $kredit_name4="";
        }
        //Membuka data auto jurnal angsuran
        $QryAngsuran= mysqli_query($Conn,"SELECT * FROM auto_jurnal WHERE kategori_transaksi='Angsuran'")or die(mysqli_error($Conn));
        $DataAngsuran = mysqli_fetch_array($QryAngsuran);
        if(!empty($DataAngsuran['id_auto_jurnal'])){
            $id_auto_jurnal5= $DataAngsuran['id_auto_jurnal'];
            $kategori_transaksi5= $DataAngsuran['kategori_transaksi'];
            $debet_id5= $DataAngsuran['debet_id'];
            $debet_name5= $DataAngsuran['debet_name'];
            $kredit_id5= $DataAngsuran['kredit_id'];
            $kredit_name5= $DataAngsuran['kredit_name'];
        }else{
            $id_auto_jurnal5="";
            $kategori_transaksi5="";
            $debet_id5="";
            $debet_name5="";
            $kredit_id5="";
            $kredit_name5="";
        }
?>
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <?php
                    echo '<div class="alert alert-info alert-dismissible fade show" role="alert">';
                    echo '  Berikut ini adalah halaman pengaturan <i>Auto Jurnal</i>.';
                    echo '  Parameter berikut ini digunakan untuk mengatur alur pembukuan jurnal keuangan secara otomatis berdasarkan transaksi yang berlangsung.';
                    echo '  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '</div>';
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <form action="javascript:void(0);" id="ProsesAutoJurnal">
                    <div class="card">
                        <div class="card-header">
                            <b class="card-title">Pengaturan Auto Jurnal</b>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3 border-1 border-bottom">
                                    <div class="row">
                                        <div class="col-md-12 mb-3"><b>A. Pengaturan Transaksi Simpanan Anggota</b></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="debet_id_simpanan">1. Akun Debet Simpanan</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select name="debet_id_simpanan" id="debet_id_simpanan" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = mysqli_query($Conn, "SELECT * FROM akun_perkiraan WHERE level='1' ORDER BY nama");
                                                    while ($GroupUtama = mysqli_fetch_array($QryGroupUtama)) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = mysqli_query($Conn, "SELECT * FROM akun_perkiraan WHERE kode LIKE '$kode_utama%' AND level != '1' ORDER BY nama");
                                                        while ($AnakGroup = mysqli_fetch_array($QryAnakGroup)) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM akun_perkiraan WHERE kd$level='$kode'"));
                                                            // Tampilkan anak group
                                                            if($LevelTerbawah=="1"){
                                                                if($debet_id1==$id_perkiraan){
                                                                    echo '<option selected value="'.$id_perkiraan.'">'.$nama_perkiraan.' ('.$saldo_normal.')</option>';
                                                                }else{
                                                                    echo '<option value="'.$id_perkiraan.'">'.$nama_perkiraan.' ('.$saldo_normal.')</option>';
                                                                }
                                                            }
                                                        }
                                                        echo '</optgroup>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="kredit_id_simpanan">2. Akun Kredit Simpanan</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select name="kredit_id_simpanan" id="kredit_id_simpanan" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = mysqli_query($Conn, "SELECT * FROM akun_perkiraan WHERE level='1' ORDER BY nama");
                                                    while ($GroupUtama = mysqli_fetch_array($QryGroupUtama)) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = mysqli_query($Conn, "SELECT * FROM akun_perkiraan WHERE kode LIKE '$kode_utama%' AND level != '1' ORDER BY nama");
                                                        while ($AnakGroup = mysqli_fetch_array($QryAnakGroup)) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM akun_perkiraan WHERE kd$level='$kode'"));
                                                            // Tampilkan anak group
                                                            if($LevelTerbawah=="1"){
                                                                if($kredit_id1==$id_perkiraan){
                                                                    echo '<option selected value="'.$id_perkiraan.'">'.$nama_perkiraan.' ('.$saldo_normal.')</option>';
                                                                }else{
                                                                    echo '<option value="'.$id_perkiraan.'">'.$nama_perkiraan.' ('.$saldo_normal.')</option>';
                                                                }
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3 border-1 border-bottom">
                                    <div class="row">
                                        <div class="col-md-12 mb-3"><b>B. Pengaturan Transaksi Penarikan Dana Simpanan</b></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="debet_id_penarikan">1. Akun Debet Penarikan</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select name="debet_id_penarikan" id="debet_id_penarikan" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = mysqli_query($Conn, "SELECT * FROM akun_perkiraan WHERE level='1' ORDER BY nama");
                                                    while ($GroupUtama = mysqli_fetch_array($QryGroupUtama)) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = mysqli_query($Conn, "SELECT * FROM akun_perkiraan WHERE kode LIKE '$kode_utama%' AND level != '1' ORDER BY nama");
                                                        while ($AnakGroup = mysqli_fetch_array($QryAnakGroup)) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM akun_perkiraan WHERE kd$level='$kode'"));
                                                            // Tampilkan anak group
                                                            if($LevelTerbawah=="1"){
                                                                if($debet_id2==$id_perkiraan){
                                                                    echo '<option selected value="'.$id_perkiraan.'">'.$nama_perkiraan.' ('.$saldo_normal.')</option>';
                                                                }else{
                                                                    echo '<option value="'.$id_perkiraan.'">'.$nama_perkiraan.' ('.$saldo_normal.')</option>';
                                                                }
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="kredit_id_simpanan">2. Akun Kredit Penarikan</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select name="kredit_id_penarikan" id="kredit_id_penarikan" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = mysqli_query($Conn, "SELECT * FROM akun_perkiraan WHERE level='1' ORDER BY nama");
                                                    while ($GroupUtama = mysqli_fetch_array($QryGroupUtama)) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = mysqli_query($Conn, "SELECT * FROM akun_perkiraan WHERE kode LIKE '$kode_utama%' AND level != '1' ORDER BY nama");
                                                        while ($AnakGroup = mysqli_fetch_array($QryAnakGroup)) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM akun_perkiraan WHERE kd$level='$kode'"));
                                                            // Tampilkan anak group
                                                            if($LevelTerbawah=="1"){
                                                                if($kredit_id2==$id_perkiraan){
                                                                    echo '<option selected value="'.$id_perkiraan.'">'.$nama_perkiraan.' ('.$saldo_normal.')</option>';
                                                                }else{
                                                                    echo '<option value="'.$id_perkiraan.'">'.$nama_perkiraan.' ('.$saldo_normal.')</option>';
                                                                }
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3 border-1 border-bottom">
                                    <div class="row">
                                        <div class="col-md-12 mb-3"><b>C. Pengaturan Transaksi Pinjaman Anggota</b></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="debet_id_pinjaman">1. Akun Debet Pinjaman</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select name="debet_id_pinjaman" id="debet_id_pinjaman" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = mysqli_query($Conn, "SELECT * FROM akun_perkiraan WHERE level='1' ORDER BY nama");
                                                    while ($GroupUtama = mysqli_fetch_array($QryGroupUtama)) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = mysqli_query($Conn, "SELECT * FROM akun_perkiraan WHERE kode LIKE '$kode_utama%' AND level != '1' ORDER BY nama");
                                                        while ($AnakGroup = mysqli_fetch_array($QryAnakGroup)) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM akun_perkiraan WHERE kd$level='$kode'"));
                                                            // Tampilkan anak group
                                                            if($LevelTerbawah=="1"){
                                                                if($debet_id4==$id_perkiraan){
                                                                    echo '<option selected value="'.$id_perkiraan.'">'.$nama_perkiraan.' ('.$saldo_normal.')</option>';
                                                                }else{
                                                                    echo '<option value="'.$id_perkiraan.'">'.$nama_perkiraan.' ('.$saldo_normal.')</option>';
                                                                }
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="kredit_id_pinjaman">2. Akun Kredit Pinjaman</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select name="kredit_id_pinjaman" id="kredit_id_pinjaman" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = mysqli_query($Conn, "SELECT * FROM akun_perkiraan WHERE level='1' ORDER BY nama");
                                                    while ($GroupUtama = mysqli_fetch_array($QryGroupUtama)) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = mysqli_query($Conn, "SELECT * FROM akun_perkiraan WHERE kode LIKE '$kode_utama%' AND level != '1' ORDER BY nama");
                                                        while ($AnakGroup = mysqli_fetch_array($QryAnakGroup)) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM akun_perkiraan WHERE kd$level='$kode'"));
                                                            // Tampilkan anak group
                                                            if($LevelTerbawah=="1"){
                                                                if($kredit_id4==$id_perkiraan){
                                                                    echo '<option selected value="'.$id_perkiraan.'">'.$nama_perkiraan.' ('.$saldo_normal.')</option>';
                                                                }else{
                                                                    echo '<option value="'.$id_perkiraan.'">'.$nama_perkiraan.' ('.$saldo_normal.')</option>';
                                                                }
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3 border-1 border-bottom">
                                    <div class="row">
                                        <div class="col-md-12 mb-3"><b>D. Pengaturan Transaksi Angsuran</b></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="debet_id_angsuran">1. Akun Debet Angsuran</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select name="debet_id_angsuran" id="debet_id_angsuran" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = mysqli_query($Conn, "SELECT * FROM akun_perkiraan WHERE level='1' ORDER BY nama");
                                                    while ($GroupUtama = mysqli_fetch_array($QryGroupUtama)) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = mysqli_query($Conn, "SELECT * FROM akun_perkiraan WHERE kode LIKE '$kode_utama%' AND level != '1' ORDER BY nama");
                                                        while ($AnakGroup = mysqli_fetch_array($QryAnakGroup)) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM akun_perkiraan WHERE kd$level='$kode'"));
                                                            // Tampilkan anak group
                                                            if($LevelTerbawah=="1"){
                                                                if($debet_id5==$id_perkiraan){
                                                                    echo '<option selected value="'.$id_perkiraan.'">'.$nama_perkiraan.' ('.$saldo_normal.')</option>';
                                                                }else{
                                                                    echo '<option value="'.$id_perkiraan.'">'.$nama_perkiraan.' ('.$saldo_normal.')</option>';
                                                                }
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="kredit_id_angsuran">2. Akun Kredit Angsuran</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select name="kredit_id_angsuran" id="kredit_id_angsuran" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = mysqli_query($Conn, "SELECT * FROM akun_perkiraan WHERE level='1' ORDER BY nama");
                                                    while ($GroupUtama = mysqli_fetch_array($QryGroupUtama)) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = mysqli_query($Conn, "SELECT * FROM akun_perkiraan WHERE kode LIKE '$kode_utama%' AND level != '1' ORDER BY nama");
                                                        while ($AnakGroup = mysqli_fetch_array($QryAnakGroup)) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM akun_perkiraan WHERE kd$level='$kode'"));
                                                            // Tampilkan anak group
                                                            if($LevelTerbawah=="1"){
                                                                if($kredit_id5==$id_perkiraan){
                                                                    echo '<option selected value="'.$id_perkiraan.'">'.$nama_perkiraan.' ('.$saldo_normal.')</option>';
                                                                }else{
                                                                    echo '<option value="'.$id_perkiraan.'">'.$nama_perkiraan.' ('.$saldo_normal.')</option>';
                                                                }
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12 text-right" id="NotifikasiSimpanAutoJurnal">
                                    <small class="text-primary">Pastikan Setting Auto Jurnal Sudah Benar.</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-md btn-primary btn-rounded">
                                <i class="bi bi-save"></i> Simpan Auto Jurnal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
<?php } ?>