<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set('Asia/Jakarta');
    //Time Now Tmp
    $now=date('Y-m-d H:i:s');
    if(empty($SessionIdAkses)){
        echo '<small class="text-danger">Sessi Akses Sudah Berakhir, Silahkan Login Ulang!</small>';
    }else{
        //Validasi tanggal tidak boleh kosong
        if(empty($_POST['tanggal'])){
            echo '<small class="text-danger">Tanggal Simpanan Tidak Boleh Kosong!</small>';
        }else{
            //Validasi kategori_simpanan_penarikan tidak boleh kosong
            if(empty($_POST['kategori_simpanan_penarikan'])){
                echo '<small class="text-danger">Kategori Simpanan Tidak Boleh Kosong!</small>';
            }else{
                //Validasi nominal tidak boleh kosong
                if(empty($_POST['nominal'])){
                    echo '<small class="text-danger">Nominal Simpanan Tidak Boleh Kosong!</small>';
                }else{
                    //Validasi id_anggota tidak boleh kosong
                    if(empty($_POST['id_anggota'])){
                        echo '<small class="text-danger">ID Anggota Tidak Boleh Kosong!</small>';
                    }else{
                        $kategori_simpanan_penarikan=$_POST['kategori_simpanan_penarikan'];
                        //Validasi id_simpanan_jenis tidak boleh kosong
                        if(empty($_POST['id_simpanan_jenis'])){
                            if($kategori_simpanan_penarikan=="Penarikan"){
                                echo '<small class="text-danger">Informasi Sumber Dana Tidak Boleh Kosong!</small>';
                            }else{
                                echo '<small class="text-danger">Jenis Simpanan Tidak Boleh Kosong!</small>';
                            }
                        }else{
                            $id_simpanan_jenis=$_POST['id_simpanan_jenis'];
                        }
                        //Membuat Variabel
                        if(empty($_POST['keterangan'])){
                            $keterangan="";
                        }else{
                            $keterangan=$_POST['keterangan'];
                        }
                        $id_anggota=$_POST['id_anggota'];
                        $tanggal_simpanan=$_POST['tanggal'];
                        
                        $nominal=$_POST['nominal'];
                        //Bersihkan Variabel
                        $id_anggota=validateAndSanitizeInput($id_anggota);
                        $tanggal_simpanan=validateAndSanitizeInput($tanggal_simpanan);
                        $id_simpanan_jenis=validateAndSanitizeInput($id_simpanan_jenis);
                        $kategori_simpanan_penarikan=validateAndSanitizeInput($kategori_simpanan_penarikan);
                        $nominal=validateAndSanitizeInput($nominal);
                        //Re Format nominal
                        $nominal = str_replace('.', '', $nominal);
                        //Buka Anggota
                        $nip=GetDetailData($Conn,'anggota','id_anggota',$id_anggota,'nip');
                        $nama=GetDetailData($Conn,'anggota','id_anggota',$id_anggota,'nama');
                        $lembaga=GetDetailData($Conn,'anggota','id_anggota',$id_anggota,'lembaga');
                        $ranking=GetDetailData($Conn,'anggota','id_anggota',$id_anggota,'ranking');
                        //Ciptakan UUID
                        $uuid_simpanan=generateUuidV1();
                        //Antara Penarikan dan Simpanan
                        if($kategori_simpanan_penarikan=="Penarikan"){
                            
                            //Validasi Jumlah Penarikan Tidak Boleh Lebih Besar Dari Nominal Simpanan
                            //Menghitung jumlah simpanan
                            $SumSimpananKotor = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS jumlah FROM simpanan WHERE id_simpanan_jenis='$id_simpanan_jenis' AND id_anggota='$id_anggota' AND kategori!='Penarikan'"));
                            $JumlahSimpananKotor = $SumSimpananKotor['jumlah'];
                            //Hitung Jumlah Penarikan
                            $SumPenarikan = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS jumlah FROM simpanan WHERE id_simpanan_jenis='$id_simpanan_jenis' AND id_anggota='$id_anggota' AND kategori='Penarikan'"));
                            $JumlahPenarikan = $SumPenarikan['jumlah'];
                            //Hitung Jumlah Simpanan Bersih
                            $simpanan_bersih=$JumlahSimpananKotor-$JumlahPenarikan;

                            if($simpanan_bersih<$nominal){
                                echo '<small class="text-danger">Jumlah Penarikan Tidak Boleh Lebih Besar Dari Simpanan Yang Anda Pilih!</small>';
                            }else{
                                //Buka Setting Auto Jurnal Penarikan
                                $debet_id=GetDetailData($Conn,'auto_jurnal','kategori_transaksi','Penarikan','debet_id');
                                $kredit_id=GetDetailData($Conn,'auto_jurnal','kategori_transaksi','Penarikan','kredit_id');
                                //Buka Kode Akun dan nama Debet Penarikan
                                $KodeAkunDebet=GetDetailData($Conn,'akun_perkiraan','id_perkiraan',$debet_id,'kode');
                                $NamaAkunDebet=GetDetailData($Conn,'akun_perkiraan','id_perkiraan',$debet_id,'nama');
                                //Buka Kode Akun dan nama Kredit Penarikan
                                $KodeAkunKredit=GetDetailData($Conn,'akun_perkiraan','id_perkiraan',$kredit_id,'kode');
                                $NamaAkunKredit=GetDetailData($Conn,'akun_perkiraan','id_perkiraan',$kredit_id,'nama');
                                if(empty($KodeAkunDebet)||empty($KodeAkunKredit)){
                                    echo '<small class="text-danger">Pengaturan Auto Jurnal Trransaksi Penarikan Tidak Boleh Kosong</small>';
                                }else{
                                    $rutin=0;
                                    $nama_simpanan="Penarikan";
                                    //Insert
                                    $EntrySimpanan="INSERT INTO simpanan (
                                        uuid_simpanan,
                                        id_anggota,
                                        id_akses,
                                        id_simpanan_jenis,
                                        rutin,
                                        nip,
                                        nama,
                                        lembaga,
                                        ranking,
                                        tanggal,
                                        kategori,
                                        keterangan,
                                        jumlah
                                    ) VALUES (
                                        '$uuid_simpanan',
                                        '$id_anggota',
                                        '$SessionIdAkses',
                                        '$id_simpanan_jenis',
                                        '$rutin',
                                        '$nip',
                                        '$nama',
                                        '$lembaga',
                                        '$ranking',
                                        '$tanggal_simpanan',
                                        '$nama_simpanan',
                                        '$keterangan',
                                        '$nominal'
                                    )";
                                    $InputSimpanan=mysqli_query($Conn, $EntrySimpanan);
                                    if($InputSimpanan){
                                        //Simpan Jurnal Debet
                                        $EntryJurnalDebet="INSERT INTO jurnal (
                                            kategori,
                                            uuid,
                                            tanggal,
                                            kode_perkiraan,
                                            nama_perkiraan,
                                            d_k,
                                            nilai
                                        ) VALUES (
                                            'Simpanan',
                                            '$uuid_simpanan',
                                            '$tanggal_simpanan',
                                            '$KodeAkunDebet',
                                            '$NamaAkunDebet',
                                            'D',
                                            '$nominal'
                                        )";
                                        $InputJurnalDebet=mysqli_query($Conn, $EntryJurnalDebet);
                                        if($InputJurnalDebet){
                                        //Simpan Jurnal Kredit
                                            $EntryJurnalKredit="INSERT INTO jurnal (
                                                kategori,
                                                uuid,
                                                tanggal,
                                                kode_perkiraan,
                                                nama_perkiraan,
                                                d_k,
                                                nilai
                                            ) VALUES (
                                                'Simpanan',
                                                '$uuid_simpanan',
                                                '$tanggal_simpanan',
                                                '$KodeAkunKredit',
                                                '$NamaAkunKredit',
                                                'K',
                                                '$nominal'
                                            )";
                                            $InputJurnalKredit=mysqli_query($Conn, $EntryJurnalKredit);
                                            if($InputJurnalKredit){
                                                $kategori_log="Log Simpanan";
                                                $deskripsi_log="Tambah Penarikan";
                                                $InputLog=addLog($Conn,$SessionIdAkses,$now,$kategori_log,$deskripsi_log);
                                                if($InputLog=="Success"){
                                                    echo '<small class="text-success" id="NotifikkasiTambahSimpananBerhasil">Success</small>';
                                                }else{
                                                    echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan log</small>';
                                                }
                                            }else{
                                                echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data pada jurnal kredit</small>';
                                            }
                                        }else{
                                            echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data pada jurnal debet</small>';
                                        }
                                    }else{
                                        echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data pada database simpanan.</small>';
                                    }
                                }
                            }
                        }else{
                            //Buka jenis simpanan
                            $nama_simpanan=GetDetailData($Conn,'simpanan_jenis','id_simpanan_jenis',$id_simpanan_jenis,'nama_simpanan');
                            $rutin=GetDetailData($Conn,'simpanan_jenis','id_simpanan_jenis',$id_simpanan_jenis,'rutin');
                            $id_perkiraan_debet=GetDetailData($Conn,'simpanan_jenis','id_simpanan_jenis',$id_simpanan_jenis,'id_perkiraan_debet');
                            $id_perkiraan_kredit=GetDetailData($Conn,'simpanan_jenis','id_simpanan_jenis',$id_simpanan_jenis,'id_perkiraan_kredit');
                            //Cek Apakah ID Perkiraan Ada Pada Data Akun Perkiraan
                            $id_perkiraan_debet=GetDetailData($Conn,'akun_perkiraan','id_perkiraan',$id_perkiraan_debet,'id_perkiraan');
                            $id_perkiraan_kredit=GetDetailData($Conn,'akun_perkiraan','id_perkiraan',$id_perkiraan_kredit,'id_perkiraan');
                            if(empty($id_perkiraan_debet)){
                                echo '<small class="text-danger">Periksa pengaturan akun debet pada jenis simpanan yang anda gunakkkan!</small>';
                            }else{
                                if(empty($id_perkiraan_kredit)){
                                    echo '<small class="text-danger">Periksa pengaturan akun kredit pada jenis simpanan yang anda gunakkkan!</small>';
                                }else{
                                    if($rutin==1){
                                        $rutin=1;
                                    }else{
                                        $rutin=0;
                                    }
                                    //Insert
                                    $EntrySimpanan="INSERT INTO simpanan (
                                        uuid_simpanan,
                                        id_anggota,
                                        id_akses,
                                        id_simpanan_jenis,
                                        rutin,
                                        nip,
                                        nama,
                                        lembaga,
                                        ranking,
                                        tanggal,
                                        kategori,
                                        keterangan,
                                        jumlah
                                    ) VALUES (
                                        '$uuid_simpanan',
                                        '$id_anggota',
                                        '$SessionIdAkses',
                                        '$id_simpanan_jenis',
                                        '$rutin',
                                        '$nip',
                                        '$nama',
                                        '$lembaga',
                                        '$ranking',
                                        '$tanggal_simpanan',
                                        '$nama_simpanan',
                                        '$keterangan',
                                        '$nominal'
                                    )";
                                    $InputSimpananWajib=mysqli_query($Conn, $EntrySimpanan);
                                    if($InputSimpananWajib){
                                        //Membuat Jurnal
                                        //Buka Akun Debet
                                        $NamaAkunDebet=GetDetailData($Conn,'akun_perkiraan','id_perkiraan',$id_perkiraan_debet,'nama');
                                        $KodeAkunDebet=GetDetailData($Conn,'akun_perkiraan','id_perkiraan',$id_perkiraan_debet,'kode');
                                        //Buka Akun Kredit
                                        $NamaAkunKredit=GetDetailData($Conn,'akun_perkiraan','id_perkiraan',$id_perkiraan_kredit,'nama');
                                        $KodeAkunKredit=GetDetailData($Conn,'akun_perkiraan','id_perkiraan',$id_perkiraan_kredit,'kode');
                                        //Simpan Jurnal Debet
                                        $EntryJurnalDebet="INSERT INTO jurnal (
                                            kategori,
                                            uuid,
                                            tanggal,
                                            kode_perkiraan,
                                            nama_perkiraan,
                                            d_k,
                                            nilai
                                        ) VALUES (
                                            'Simpanan',
                                            '$uuid_simpanan',
                                            '$tanggal_simpanan',
                                            '$KodeAkunDebet',
                                            '$NamaAkunDebet',
                                            'D',
                                            '$nominal'
                                        )";
                                        $InputJurnalDebet=mysqli_query($Conn, $EntryJurnalDebet);
                                        if($InputJurnalDebet){
                                        //Simpan Jurnal Kredit
                                            $EntryJurnalKredit="INSERT INTO jurnal (
                                                kategori,
                                                uuid,
                                                tanggal,
                                                kode_perkiraan,
                                                nama_perkiraan,
                                                d_k,
                                                nilai
                                            ) VALUES (
                                                'Simpanan',
                                                '$uuid_simpanan',
                                                '$tanggal_simpanan',
                                                '$KodeAkunKredit',
                                                '$NamaAkunKredit',
                                                'K',
                                                '$nominal'
                                            )";
                                            $InputJurnalKredit=mysqli_query($Conn, $EntryJurnalKredit);
                                            if($InputJurnalKredit){
                                                $kategori_log="Log Simpanan";
                                                $deskripsi_log="Tambah Penarikan";
                                                $InputLog=addLog($Conn,$SessionIdAkses,$now,$kategori_log,$deskripsi_log);
                                                if($InputLog=="Success"){
                                                    echo '<small class="text-success" id="NotifikkasiTambahSimpananBerhasil">Success</small>';
                                                }else{
                                                    echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan log</small>';
                                                }
                                            }else{
                                                echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data pada jurnal kredit</small>';
                                            }
                                        }else{
                                            echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data pada jurnal debet</small>';
                                        }
                                    }else{
                                        echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data pada database simpanan.</small>';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
?>