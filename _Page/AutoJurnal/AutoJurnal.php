<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'eQhEWIf1fV6xwMNr8J9');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
        // Menjalankan SELECT terparameterisasi dan menutup statement setelah hasil dibaca.
        $runPreparedQuery = function($sql, $types = '', $parameters = array()) use ($Conn) {
            $statement = $Conn->prepare($sql);
            if ($statement === false) {
                die(mysqli_error($Conn));
            }
            if ($types !== '') {
                $bindParameters = array($types);
                foreach ($parameters as $index => $parameter) {
                    $parameters[$index] = $parameter;
                    $bindParameters[] = &$parameters[$index];
                }
                if (!call_user_func_array(array($statement, 'bind_param'), $bindParameters)) {
                    $error = $statement->error;
                    $statement->close();
                    die($error);
                }
            }
            if (!$statement->execute()) {
                $error = $statement->error;
                $statement->close();
                die($error);
            }
            $result = $statement->get_result();
            if ($result === false) {
                $error = $statement->error;
                $statement->close();
                die($error);
            }
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            $result->free();
            $statement->close();
            return $rows;
        };

        // Kolom level merupakan identifier; gunakan whitelist sebelum menyusunnya ke SQL.
        $getLeafAccountCount = function($level, $code) use ($runPreparedQuery) {
            $levelColumn = 'kd' . (int)$level;
            if (!in_array($levelColumn, array('kd1', 'kd2', 'kd3', 'kd4', 'kd5'), true)) {
                die('Level akun perkiraan tidak valid.');
            }
            $rows = $runPreparedQuery(
                "SELECT COUNT(*) AS total FROM akun_perkiraan WHERE `" . $levelColumn . "` = ?",
                "s",
                array($code)
            );
            return (int)$rows[0]['total'];
        };

        //Membuka data auto jurnal Simpanan
        $QrySimpanan = $runPreparedQuery("SELECT * FROM auto_jurnal WHERE kategori_transaksi = ?", "s", array("Simpanan"));
        $DataSimpanan = $QrySimpanan[0] ?? array();
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
        $QryPenarikan = $runPreparedQuery("SELECT * FROM auto_jurnal WHERE kategori_transaksi = ?", "s", array("Penarikan"));
        $DataPenarikan = $QryPenarikan[0] ?? array();
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
        $QryPinjaman = $runPreparedQuery("SELECT * FROM auto_jurnal WHERE kategori_transaksi = ?", "s", array("Pinjaman"));
        $DataPinjaman = $QryPinjaman[0] ?? array();
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
        $QryAngsuran = $runPreparedQuery("SELECT * FROM auto_jurnal WHERE kategori_transaksi = ?", "s", array("Angsuran"));
        $DataAngsuran = $QryAngsuran[0] ?? array();
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

        // Mendapatkan data Pembagian SHU
        $pembagianShu = getAutoJurnalSHU($Conn, 'Pembagian');
        $id_perkiraan_debet_pembagian = $pembagianShu['id_perkiraan_debet'];
        $id_perkiraan_kredit_pembagian = $pembagianShu['id_perkiraan_kredit'];

        // Mendapatkan data Pembayaran SHU
        $pembayaranShu = getAutoJurnalSHU($Conn, 'Pembayaran');
        $id_perkiraan_debet_pembayaran = $pembayaranShu['id_perkiraan_debet'];
        $id_perkiraan_kredit_pembayaran = $pembayaranShu['id_perkiraan_kredit'];
?>
    <div class="pagetitle">
        <h1>
            <a href="">
                <i class="bi bi-gear"></i> Auto Jurnal</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active"> Auto Jurnal</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <?php
                    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
                    echo '  <small>';
                    echo '      Berikut ini adalah halaman pengaturan <i>Auto Jurnal</i>.';
                    echo '      Parameter berikut ini digunakan untuk mengatur alur pembukuan jurnal keuangan secara otomatis berdasarkan transaksi yang berlangsung.';
                    echo '      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '  </small>';
                    echo '</div>';
                ?>
            </div>
        </div>
        <!-- Baris kartu Auto Jurnal dibuat setinggi sama pada setiap breakpoint. -->
        <div class="row autojurnal-card-row">
            <div class="col-lg-4">
                <form action="javascript:void(0);" id="ProsesAutoJurnal">
                    <div class="card">
                        <div class="card-header">
                            <b class="card-title"># Simpan Pinjam</b>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3 border-1 border-bottom">
                                    <div class="row">
                                        <div class="col-md-12 mb-3"><b>A. Pengaturan Transaksi Simpanan Anggota</b></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="debet_id_simpanan">Akun Debet Simpanan</label>
                                            <select name="debet_id_simpanan" id="debet_id_simpanan" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE level = ? ORDER BY nama", "s", array("1"));
                                                    foreach ($QryGroupUtama as $GroupUtama) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE kode LIKE ? AND level <> ? ORDER BY nama", "ss", array($kode_utama . "%", "1"));
                                                        foreach ($QryAnakGroup as $AnakGroup) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = $getLeafAccountCount($level, $kode);
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
                                        <div class="col-md-12">
                                            <label for="kredit_id_simpanan">Akun Kredit Simpanan</label>
                                            <select name="kredit_id_simpanan" id="kredit_id_simpanan" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE level = ? ORDER BY nama", "s", array("1"));
                                                    foreach ($QryGroupUtama as $GroupUtama) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE kode LIKE ? AND level <> ? ORDER BY nama", "ss", array($kode_utama . "%", "1"));
                                                        foreach ($QryAnakGroup as $AnakGroup) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = $getLeafAccountCount($level, $kode);
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
                                        <div class="col-md-12">
                                            <label for="debet_id_penarikan">Akun Debet Penarikan</label>
                                            <select name="debet_id_penarikan" id="debet_id_penarikan" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE level = ? ORDER BY nama", "s", array("1"));
                                                    foreach ($QryGroupUtama as $GroupUtama) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE kode LIKE ? AND level <> ? ORDER BY nama", "ss", array($kode_utama . "%", "1"));
                                                        foreach ($QryAnakGroup as $AnakGroup) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = $getLeafAccountCount($level, $kode);
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
                                        <div class="col-md-12">
                                            <label for="kredit_id_simpanan">Akun Kredit Penarikan</label>
                                            <select name="kredit_id_penarikan" id="kredit_id_penarikan" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE level = ? ORDER BY nama", "s", array("1"));
                                                    foreach ($QryGroupUtama as $GroupUtama) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE kode LIKE ? AND level <> ? ORDER BY nama", "ss", array($kode_utama . "%", "1"));
                                                        foreach ($QryAnakGroup as $AnakGroup) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = $getLeafAccountCount($level, $kode);
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
                                        <div class="col-md-12">
                                            <label for="debet_id_pinjaman">Akun Debet Pinjaman</label>
                                            <select name="debet_id_pinjaman" id="debet_id_pinjaman" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE level = ? ORDER BY nama", "s", array("1"));
                                                    foreach ($QryGroupUtama as $GroupUtama) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE kode LIKE ? AND level <> ? ORDER BY nama", "ss", array($kode_utama . "%", "1"));
                                                        foreach ($QryAnakGroup as $AnakGroup) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = $getLeafAccountCount($level, $kode);
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
                                        <div class="col-md-12">
                                            <label for="kredit_id_pinjaman">Akun Kredit Pinjaman</label>
                                            <select name="kredit_id_pinjaman" id="kredit_id_pinjaman" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE level = ? ORDER BY nama", "s", array("1"));
                                                    foreach ($QryGroupUtama as $GroupUtama) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE kode LIKE ? AND level <> ? ORDER BY nama", "ss", array($kode_utama . "%", "1"));
                                                        foreach ($QryAnakGroup as $AnakGroup) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = $getLeafAccountCount($level, $kode);
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
                                        <div class="col-md-12">
                                            <label for="debet_id_angsuran">Akun Debet Angsuran</label>
                                            <select name="debet_id_angsuran" id="debet_id_angsuran" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE level = ? ORDER BY nama", "s", array("1"));
                                                    foreach ($QryGroupUtama as $GroupUtama) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE kode LIKE ? AND level <> ? ORDER BY nama", "ss", array($kode_utama . "%", "1"));
                                                        foreach ($QryAnakGroup as $AnakGroup) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = $getLeafAccountCount($level, $kode);
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
                                        <div class="col-md-12">
                                            <label for="kredit_id_angsuran">Akun Kredit Angsuran</label>
                                            <select name="kredit_id_angsuran" id="kredit_id_angsuran" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE level = ? ORDER BY nama", "s", array("1"));
                                                    foreach ($QryGroupUtama as $GroupUtama) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE kode LIKE ? AND level <> ? ORDER BY nama", "ss", array($kode_utama . "%", "1"));
                                                        foreach ($QryAnakGroup as $AnakGroup) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = $getLeafAccountCount($level, $kode);
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
                            <button type="submit" class="btn btn-md btn-primary">
                                <i class="bi bi-save"></i> Simpan Auto Jurnal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <?php
                //Penjualan
                $QryAutoJurnalPenjualan = $runPreparedQuery("SELECT * FROM setting_autojurnal_jual_beli WHERE kategori = ?", "s", array("Penjualan"));
                $DataAutoJurnalPenjualan = $QryAutoJurnalPenjualan[0] ?? array();
                if(!empty($DataAutoJurnalPenjualan['id_autojurnal_jual_beli'])){
                    $id_autojurnal_penjualan= $DataAutoJurnalPenjualan['id_autojurnal_jual_beli'];
                    $DebetPenjualan= $DataAutoJurnalPenjualan['debet'];
                    $KreditPenjualan= $DataAutoJurnalPenjualan['kredit'];
                    $HppPenjualan= $DataAutoJurnalPenjualan['hpp'];
                    $PersediaanPenjualan= $DataAutoJurnalPenjualan['persediaan'];
                    $UtangPiutangPenjualan= $DataAutoJurnalPenjualan['utang_piutang'];
                }else{
                    $id_autojurnal_penjualan="";
                    $DebetPenjualan="";
                    $KreditPenjualan="";
                    $HppPenjualan="";
                    $PersediaanPenjualan="";
                    $UtangPiutangPenjualan="";
                }

                //Pembelian
                $QryAutoJurnalPembelian = $runPreparedQuery("SELECT * FROM setting_autojurnal_jual_beli WHERE kategori = ?", "s", array("Pembelian"));
                $DataAutoJurnalPembelian = $QryAutoJurnalPembelian[0] ?? array();
                if(!empty($DataAutoJurnalPembelian['id_autojurnal_jual_beli'])){
                    $id_autojurnal_pembelian= $DataAutoJurnalPembelian['id_autojurnal_jual_beli'];
                    $DebetPembelian= $DataAutoJurnalPembelian['debet'];
                    $KreditPembelian= $DataAutoJurnalPembelian['kredit'];
                    $HppPembelian= $DataAutoJurnalPembelian['hpp'];
                    $PersediaanPembelian= $DataAutoJurnalPembelian['persediaan'];
                    $UtangPiutangPembelian= $DataAutoJurnalPembelian['utang_piutang'];
                }else{
                    $id_autojurnal_pembelian="";
                    $DebetPembelian="";
                    $KreditPembelian="";
                    $HppPembelian="";
                    $PersediaanPembelian="";
                    $UtangPiutangPembelian="";
                }
            ?>
            <div class="col-md-4">
                <form action="javascript:void(0);" id="ProssesSimpanAutoJurnalJualBeli">
                    <div class="card">
                        <div class="card-header">
                            <b class="card-title"># Transaksi Jual/Beli</b>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12 mb-3">
                                    <div class="row mb-3">
                                        <div class="col-12">
                                           <b>A. Transaksi Penjualan</b>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="debet_penjualan">
                                                <small>Akun Kas</small>
                                            </label>
                                            <select name="debet_penjualan" id="debet_penjualan" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE level = ? ORDER BY nama", "s", array("1"));
                                                    foreach ($QryGroupUtama as $GroupUtama) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE kode LIKE ? AND level <> ? ORDER BY nama", "ss", array($kode_utama . "%", "1"));
                                                        foreach ($QryAnakGroup as $AnakGroup) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = $getLeafAccountCount($level, $kode);
                                                            // Tampilkan anak group
                                                            if($LevelTerbawah=="1"){
                                                                if($DebetPenjualan==$id_perkiraan){
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
                                        <div class="col-12">
                                            <label for="kredit_penjualan">
                                                <small>Akun Pendapatan Penjualan</small>
                                            </label>
                                            <select name="kredit_penjualan" id="kredit_penjualan" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE level = ? ORDER BY nama", "s", array("1"));
                                                    foreach ($QryGroupUtama as $GroupUtama) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE kode LIKE ? AND level <> ? ORDER BY nama", "ss", array($kode_utama . "%", "1"));
                                                        foreach ($QryAnakGroup as $AnakGroup) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = $getLeafAccountCount($level, $kode);
                                                            // Tampilkan anak group
                                                            if($LevelTerbawah=="1"){
                                                                if($KreditPenjualan==$id_perkiraan){
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
                                        <div class="col-12">
                                            <label for="hpp_penjualan">
                                                <small>Akun HPP (Harga Pokok Penjualan)</small>
                                            </label>
                                            <select name="hpp_penjualan" id="hpp_penjualan" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE level = ? ORDER BY nama", "s", array("1"));
                                                    foreach ($QryGroupUtama as $GroupUtama) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE kode LIKE ? AND level <> ? ORDER BY nama", "ss", array($kode_utama . "%", "1"));
                                                        foreach ($QryAnakGroup as $AnakGroup) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = $getLeafAccountCount($level, $kode);
                                                            // Tampilkan anak group
                                                            if($LevelTerbawah=="1"){
                                                                if($HppPenjualan==$id_perkiraan){
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
                                        <div class="col-12">
                                            <label for="persediaan_penjualan">
                                                <small>Akun Persediaan</small>
                                            </label>
                                            <select name="persediaan_penjualan" id="persediaan_penjualan" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE level = ? ORDER BY nama", "s", array("1"));
                                                    foreach ($QryGroupUtama as $GroupUtama) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE kode LIKE ? AND level <> ? ORDER BY nama", "ss", array($kode_utama . "%", "1"));
                                                        foreach ($QryAnakGroup as $AnakGroup) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = $getLeafAccountCount($level, $kode);
                                                            // Tampilkan anak group
                                                            if($LevelTerbawah=="1"){
                                                                if($PersediaanPenjualan==$id_perkiraan){
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
                                        <div class="col-12">
                                            <label for="utang_piutang_penjualan">
                                                <small>* Akun Piutang</small>
                                            </label>
                                            <select name="utang_piutang_penjualan" id="utang_piutang_penjualan" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE level = ? ORDER BY nama", "s", array("1"));
                                                    foreach ($QryGroupUtama as $GroupUtama) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE kode LIKE ? AND level <> ? ORDER BY nama", "ss", array($kode_utama . "%", "1"));
                                                        foreach ($QryAnakGroup as $AnakGroup) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = $getLeafAccountCount($level, $kode);
                                                            // Tampilkan anak group
                                                            if($LevelTerbawah=="1"){
                                                                if($UtangPiutangPenjualan==$id_perkiraan){
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
                                <div class="col-12">
                                    <div class="row mb-3 mt-3">
                                        <div class="col-12">
                                            <b>B. Transaksi Pembelian</b>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="kredit_pembelian">
                                                <small>Akun Kas</small>
                                            </label>
                                            <select name="kredit_pembelian" id="kredit_pembelian" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE level = ? ORDER BY nama", "s", array("1"));
                                                    foreach ($QryGroupUtama as $GroupUtama) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE kode LIKE ? AND level <> ? ORDER BY nama", "ss", array($kode_utama . "%", "1"));
                                                        foreach ($QryAnakGroup as $AnakGroup) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = $getLeafAccountCount($level, $kode);
                                                            // Tampilkan anak group
                                                            if($LevelTerbawah=="1"){
                                                                if($KreditPembelian==$id_perkiraan){
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
                                        <div class="col-12">
                                            <label for="debet_pembelian">
                                                <small>Akun Persediaan</small>
                                            </label>
                                            <select name="debet_pembelian" id="debet_pembelian" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE level = ? ORDER BY nama", "s", array("1"));
                                                    foreach ($QryGroupUtama as $GroupUtama) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE kode LIKE ? AND level <> ? ORDER BY nama", "ss", array($kode_utama . "%", "1"));
                                                        foreach ($QryAnakGroup as $AnakGroup) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = $getLeafAccountCount($level, $kode);
                                                            // Tampilkan anak group
                                                            if($LevelTerbawah=="1"){
                                                                if($DebetPembelian==$id_perkiraan){
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
                                        <div class="col-12">
                                            <label for="utang_piutang_pembelian">
                                                <small>* Akun Utang</small>
                                            </label>
                                            <select name="utang_piutang_pembelian" id="utang_piutang_pembelian" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE level = ? ORDER BY nama", "s", array("1"));
                                                    foreach ($QryGroupUtama as $GroupUtama) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE kode LIKE ? AND level <> ? ORDER BY nama", "ss", array($kode_utama . "%", "1"));
                                                        foreach ($QryAnakGroup as $AnakGroup) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = $getLeafAccountCount($level, $kode);
                                                            // Tampilkan anak group
                                                            if($LevelTerbawah=="1"){
                                                                if($UtangPiutangPembelian==$id_perkiraan){
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
                                <div class="col-12" id="NotifikasiSimpanAutoJurnalJualBeli">
                                    <small></small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-md btn-primary">
                                <i class="bi bi-save"></i> Simpan Auto Jurnal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <form action="javascript:void(0);" id="ProssesSimpanAutoJurnalShu">
                    <div class="card">
                        <div class="card-header">
                            <b class="card-title"># Auto Jurnal Bagi Hasil (SHU)</b>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12 mb-3">
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <b>A. Transaksi Pembagian SHU </b>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="debet_pembagian">
                                                <small>Akun Debet</small>
                                            </label>
                                            <select name="debet_pembagian" id="debet_pembagian" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE level = ? ORDER BY nama", "s", array("1"));
                                                    foreach ($QryGroupUtama as $GroupUtama) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE kode LIKE ? AND level <> ? ORDER BY nama", "ss", array($kode_utama . "%", "1"));
                                                        foreach ($QryAnakGroup as $AnakGroup) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = $getLeafAccountCount($level, $kode);
                                                            // Tampilkan anak group
                                                            if($LevelTerbawah=="1"){
                                                                if($id_perkiraan_debet_pembagian==$id_perkiraan){
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
                                        <div class="col-12">
                                            <label for="kredit_pembagian">
                                                <small>Akun Kredit</small>
                                            </label>
                                            <select name="kredit_pembagian" id="kredit_pembagian" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE level = ? ORDER BY nama", "s", array("1"));
                                                    foreach ($QryGroupUtama as $GroupUtama) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE kode LIKE ? AND level <> ? ORDER BY nama", "ss", array($kode_utama . "%", "1"));
                                                        foreach ($QryAnakGroup as $AnakGroup) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = $getLeafAccountCount($level, $kode);
                                                            // Tampilkan anak group
                                                            if($LevelTerbawah=="1"){
                                                                if($id_perkiraan_kredit_pembagian==$id_perkiraan){
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
                                <div class="col-12">
                                    <div class="row mb-3 mt-3">
                                        <div class="col-12">
                                            <b>Transaksi Pembayaran SHU </b>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="debet_pembayaran">
                                                <small>Akun Debet</small>
                                            </label>
                                            <select name="debet_pembayaran" id="debet_pembayaran" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE level = ? ORDER BY nama", "s", array("1"));
                                                    foreach ($QryGroupUtama as $GroupUtama) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE kode LIKE ? AND level <> ? ORDER BY nama", "ss", array($kode_utama . "%", "1"));
                                                        foreach ($QryAnakGroup as $AnakGroup) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = $getLeafAccountCount($level, $kode);
                                                            // Tampilkan anak group
                                                            if($LevelTerbawah=="1"){
                                                                if($id_perkiraan_debet_pembayaran==$id_perkiraan){
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
                                        <div class="col-12">
                                            <label for="kredit_pembayaran">
                                                <small>Akun Kredit</small>
                                            </label>
                                            <select name="kredit_pembayaran" id="kredit_pembayaran" class="form-control">
                                                <?php
                                                    echo '<option value="">Pilih</option>';
                                                    // Query untuk mengambil akun level 1 (group utama)
                                                    $QryGroupUtama = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE level = ? ORDER BY nama", "s", array("1"));
                                                    foreach ($QryGroupUtama as $GroupUtama) {
                                                        $id_perkiraan_utama = $GroupUtama['id_perkiraan'];
                                                        $kode_utama = $GroupUtama['kode'];
                                                        $nama_utama = $GroupUtama['nama'];
                                                        $saldo_normal_utama = $GroupUtama['saldo_normal'];
                                                        // Tampilkan group utama
                                                        echo '<optgroup label="'.$nama_utama.' ('.$saldo_normal_utama.')">';
                                                        // Query untuk mengambil anak group dari group utama berdasarkan kode
                                                        $QryAnakGroup = $runPreparedQuery("SELECT * FROM akun_perkiraan WHERE kode LIKE ? AND level <> ? ORDER BY nama", "ss", array($kode_utama . "%", "1"));
                                                        foreach ($QryAnakGroup as $AnakGroup) {
                                                            $id_perkiraan = $AnakGroup['id_perkiraan'];
                                                            $nama_perkiraan = $AnakGroup['nama'];
                                                            $saldo_normal = $AnakGroup['saldo_normal'];
                                                            $kode = $AnakGroup['kode'];
                                                            $level = $AnakGroup['level'];
                                                            $LevelTerbawah = $getLeafAccountCount($level, $kode);
                                                            // Tampilkan anak group
                                                            if($LevelTerbawah=="1"){
                                                                if($id_perkiraan_kredit_pembayaran==$id_perkiraan){
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
                                <div class="col-12" id="NotifikasiSimpanAutoJurnalShu">
                                    <small></small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-md btn-primary">
                                <i class="bi bi-save"></i> Simpan Auto Jurnal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
<?php } ?>