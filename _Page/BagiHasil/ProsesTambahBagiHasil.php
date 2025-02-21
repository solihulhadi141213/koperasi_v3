<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");
    //Time Now Tmp
    $now=date('Y-m-d H:i:s');
    if(empty($SessionIdAkses)){
        echo '<small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang</small>';
    }else{
        //Validasi sesi_shu tidak boleh kosong
        if(empty($_POST['sesi_shu'])){
            echo '<small class="text-danger">Nama Sesi Tidak Boleh Kosong!</small>';
        }else{
            //Validasi periode_hitung1 tidak boleh kosong
            if(empty($_POST['periode_hitung1'])){
                echo '<small class="text-danger">Periode Hitung Awal Tidak Boleh Kosong!</small>';
            }else{
                //Validasi periode_hitung2 tidak boleh kosong
                if(empty($_POST['periode_hitung2'])){
                    echo '<small class="text-danger">Periode Hitung Akhir Tidak Boleh Kosong!</small>';
                }else{
                    //Validasi alokasi_nyata tidak boleh kosong
                    if(empty($_POST['alokasi_nyata'])){
                        echo '<small class="text-danger">Nilai Alokasi Bagi Hasil Tidak Boleh Kosong!</small>';
                    }else{
                        //Validasi status tidak boleh kosong
                        if(empty($_POST['status'])){
                            echo '<small class="text-danger">Status Bagi Hasil Tidak Boleh Kosong!</small>';
                        }else{
                            if(empty($_POST['persen_usaha'])){
                                $persen_usaha=0;
                            }else{
                                $persen_usaha=$_POST['persen_usaha'];
                            }
                            if(empty($_POST['persen_modal'])){
                                $persen_modal=0;
                            }else{
                                $persen_modal=$_POST['persen_modal'];
                            }
                            if(empty($_POST['persen_pinjaman'])){
                                $persen_pinjaman=0;
                            }else{
                                $persen_pinjaman=$_POST['persen_pinjaman'];
                            }
                            $sesi_shu=$_POST['sesi_shu'];
                            $periode_hitung1=$_POST['periode_hitung1'];
                            $periode_hitung2=$_POST['periode_hitung2'];
                            $alokasi_nyata=$_POST['alokasi_nyata'];
                            $status=$_POST['status'];
                            //Membersihkan Variabel
                            $sesi_shu=validateAndSanitizeInput($sesi_shu);
                            $periode_hitung1=validateAndSanitizeInput($periode_hitung1);
                            $periode_hitung2=validateAndSanitizeInput($periode_hitung2);
                            $alokasi_nyata=validateAndSanitizeInput($alokasi_nyata);
                            $persen_modal=validateAndSanitizeInput($persen_modal);
                            $persen_pinjaman=validateAndSanitizeInput($persen_pinjaman);
                            $persen_usaha=validateAndSanitizeInput($persen_usaha);
                            $status=validateAndSanitizeInput($status);
                            //Mengubah Format Nominal
                            $alokasi_nyata= str_replace(".", "", $alokasi_nyata);
                            if(!preg_match("/^[0-9]*$/", $alokasi_nyata)){
                                echo '<small class="text-danger">Jumlah Alokasi Hanya Boleh Angka!</small>'; 
                            }else{
                                $JumlahKarakterNamaSessi=strlen($_POST['sesi_shu']);
                                if($JumlahKarakterNamaSessi>25){
                                    echo '<small class="text-danger">Nama Sesi Terlalu Panjang! (Maksimal 25 karakter)</small>';
                                }else{
                                    $ValidasiDuplikat=mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM shu_session WHERE sesi_shu='$sesi_shu' AND periode_hitung1='$periode_hitung1' AND periode_hitung2='$periode_hitung2' AND persen_usaha='$persen_usaha' AND persen_modal='$persen_modal'"));
                                    if(!empty($ValidasiDuplikat)){
                                        echo '<small class="text-danger">Data Yang Anda Input Sudah Ada!</small>';
                                    }else{
                                        //Menghitung SIMPANAN TOTAL
                                        $SumTotalSimpanan = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS jumlah FROM simpanan WHERE tanggal<='$periode_hitung2' AND kategori!='Penarikan'"));
                                        $SimpananTotalBruto = $SumTotalSimpanan['jumlah'];
                                        //Hitung Total Penarikan Anggota TOTAL
                                        $SumTotalPenarikan = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS jumlah FROM simpanan WHERE tanggal<='$periode_hitung2' AND kategori='Penarikan'"));
                                        $JumlahTotalPenarikan = $SumTotalPenarikan['jumlah'];
                                        //Simpanan Netto TOTAL
                                        $SimpananTotalNetto=$SimpananTotalBruto-$JumlahTotalPenarikan;
                                        //Jumlah Jasa Modal Anggota TOTAL
                                        $SumJasaPinjamanAnggotaTotal= mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jasa) AS jasa FROM pinjaman_angsuran WHERE tanggal_angsuran>='$periode_hitung1' AND tanggal_angsuran<='$periode_hitung2'"));
                                        if(!empty($SumJasaPinjamanAnggotaTotal['jasa'])){
                                            $JumlahJasaPinjamanAnggotaTotal = $SumJasaPinjamanAnggotaTotal['jasa'];
                                        }else{
                                            $JumlahJasaPinjamanAnggotaTotal =0;
                                        }
                                        //Jumlah Pembelanjaan Anggota TOTAL
                                        $JumlahBelanjaAnggotaTotal =0;
                                        //Jumlah Anggota
                                        $JumlahAnggota = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM anggota WHERE status='Aktif'"));
                                        //Arraykan data anggota
                                        $JumlahAnggotaProses=0;
                                        $GetSimpananAnggota=0;
                                        $GetPinjamanAnggota=0;
                                        $GetPenjualanAnggota=0;
                                        $QryAnggota = mysqli_query($Conn, "SELECT*FROM anggota WHERE status='Aktif'");
                                        while ($DataAnggota = mysqli_fetch_array($QryAnggota)) {
                                            $id_anggota= $DataAnggota['id_anggota'];
                                            $nama= $DataAnggota['nama'];
                                            //Hitung Total Simpanan Anggota
                                            $SumSimpanan = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS jumlah FROM simpanan WHERE id_anggota='$id_anggota' AND tanggal<='$periode_hitung2' AND kategori!='Penarikan'"));
                                            if(empty($SumSimpanan['jumlah'])){
                                                $SimpananBruto =0;
                                            }else{
                                                $SimpananBruto = $SumSimpanan['jumlah'];
                                            }
                                            //Hitung Total Penarikan Anggota
                                            $SumPenarikan = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS jumlah FROM simpanan WHERE id_anggota='$id_anggota' AND tanggal<='$periode_hitung2' AND kategori='Penarikan'"));
                                            if(empty($SumPenarikan['jumlah'])){
                                                $JumlahPenarikan =0;
                                            }else{
                                                $JumlahPenarikan = $SumPenarikan['jumlah'];
                                            }
                                            //Simpanan Netto
                                            $SimpananNetto=$SimpananBruto-$JumlahPenarikan;
                                            //Jasa Simpanan
                                            if(empty($SimpananTotalNetto)){
                                                $JasaSimpananAnggota=0;
                                            }else{
                                                $JasaSimpananAnggota=($SimpananNetto/$SimpananTotalNetto)*($persen_modal/100)*$alokasi_nyata;
                                            }
                                            //Jumlah Jasa Pinjaman Anggota
                                            $SumJasaPinjamanAnggota= mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jasa) AS jasa FROM pinjaman_angsuran WHERE id_anggota='$id_anggota' AND tanggal_angsuran>='$periode_hitung1' AND tanggal_angsuran<='$periode_hitung2'"));
                                            if(!empty($SumJasaPinjamanAnggota['jasa'])){
                                                $JumlahJasaPinjamanAnggota = $SumJasaPinjamanAnggota['jasa'];
                                            }else{
                                                $JumlahJasaPinjamanAnggota =0;
                                            }
                                            if(empty($JumlahJasaPinjamanAnggotaTotal)){
                                                $JasaPinjamanAnggota=0;
                                            }else{
                                                $JasaPinjamanAnggota=($JumlahJasaPinjamanAnggota/$JumlahJasaPinjamanAnggotaTotal)*($persen_pinjaman/100)*$alokasi_nyata;
                                            }
                                            //Jumlah Pembelanjaan
                                            $JumlahBelanjaAnggota=0;
                                            if(empty($JumlahBelanjaAnggotaTotal)){
                                                $JasaPenjualanAnggota=0;
                                            }else{
                                                $JasaPenjualanAnggota=($JumlahBelanjaAnggota/$JumlahBelanjaAnggotaTotal)*($persen_usaha/100)*$alokasi_nyata;
                                            }
                                            //Hitung Jumlah SHU
                                            $shu=$JasaSimpananAnggota+$JasaPinjamanAnggota+$JasaPenjualanAnggota;
                                            //Simpan Ke Data Rincian
                                            $SimpananNetto=round($SimpananNetto);
                                            $JumlahJasaPinjamanAnggota=round($JumlahJasaPinjamanAnggota);
                                            $JumlahBelanjaAnggota=round($JumlahBelanjaAnggota);
                                            $JasaSimpananAnggota=round($JasaSimpananAnggota);
                                            $JasaPinjamanAnggota=round($JasaPinjamanAnggota);
                                            $JasaPenjualanAnggota=round($JasaPenjualanAnggota);
                                            $shu=round($shu);
                                            $EntryshuRincian="INSERT INTO shu_rincian (
                                                id_shu_session,
                                                id_anggota,
                                                nama_anggota,
                                                simpanan,
                                                pinjaman,
                                                penjualan,
                                                jasa_simpanan,
                                                jasa_pinjaman,
                                                jasa_penjualan,
                                                shu
                                            ) VALUES (
                                                '0',
                                                '$id_anggota',
                                                '$nama',
                                                '$SimpananNetto',
                                                '$JumlahJasaPinjamanAnggota',
                                                '$JumlahBelanjaAnggota',
                                                '$JasaSimpananAnggota',
                                                '$JasaPinjamanAnggota',
                                                '$JasaPenjualanAnggota',
                                                '$shu'
                                            )";
                                            $InputRincianShu=mysqli_query($Conn, $EntryshuRincian);
                                            if($InputRincianShu){
                                                $JumlahAnggotaProses2=1;
                                                $GetSimpananAnggota=$GetSimpananAnggota+$JasaSimpananAnggota;
                                                $GetPinjamanAnggota=$GetPinjamanAnggota+$JasaPinjamanAnggota;
                                                $GetPenjualanAnggota=$GetPenjualanAnggota+$JasaPenjualanAnggota;
                                            }else{
                                                $Error=mysqli_error($Conn);
                                                echo '<p>';
                                                echo "ID Anggota : $id_anggota<br>";
                                                echo "Nama : $nama<br>";
                                                echo "Simpanan Neto : $SimpananNetto<br>";
                                                echo "Jumlah Jasa Pinjaman Anggota : $JumlahJasaPinjamanAnggota<br>";
                                                echo "Belanja Anggota : $JumlahBelanjaAnggota<br>";
                                                echo "Jasa Simpanan Anggota : $JasaSimpananAnggota<br>";
                                                echo "Pinjaman Anggota : $JasaPinjamanAnggota<br>";
                                                echo "Jasa Penjualan Anggota : $JasaPenjualanAnggota<br>";
                                                echo "SHU : $shu<br>";
                                                echo "Error : $Error<br>";
                                                echo '</p>';
                                                $JumlahAnggotaProses2=0;
                                                $GetSimpananAnggota=$GetSimpananAnggota+0;
                                                $GetPinjamanAnggota=$GetPinjamanAnggota+0;
                                                $GetPenjualanAnggota=$GetPenjualanAnggota+0;
                                            }
                                            $JumlahAnggotaProses=$JumlahAnggotaProses+$JumlahAnggotaProses2;
                                        }
                                        if($JumlahAnggotaProses==$JumlahAnggota){
                                            $uuid=GenerateToken(36);
                                            //Input Data Session SHU
                                            $EntrySession="INSERT INTO shu_session (
                                                uuid_shu_session,
                                                sesi_shu,
                                                periode_hitung1,
                                                periode_hitung2,
                                                modal_anggota,
                                                penjualan,
                                                pinjaman,
                                                jasa_modal_anggota,
                                                laba_penjualan,
                                                jasa_pinjaman,
                                                persen_usaha,
                                                persen_modal,
                                                persen_pinjaman,
                                                alokasi_hitung,
                                                alokasi_nyata,
                                                status
                                            ) VALUES (
                                                '$uuid',
                                                '$sesi_shu',
                                                '$periode_hitung1',
                                                '$periode_hitung2',
                                                '$SimpananTotalNetto',
                                                '$JumlahBelanjaAnggotaTotal',
                                                '$JumlahJasaPinjamanAnggotaTotal',
                                                '$GetSimpananAnggota',
                                                '$GetPenjualanAnggota',
                                                '$GetPinjamanAnggota',
                                                '$persen_usaha',
                                                '$persen_modal',
                                                '$persen_pinjaman',
                                                '$alokasi_nyata',
                                                '$alokasi_nyata',
                                                '$status'
                                            )";
                                            $InputSession=mysqli_query($Conn, $EntrySession);
                                            if($InputSession){
                                                //Cari ID Primary Keynya
                                                $id_shu_session=GetDetailData($Conn,'shu_session','uuid_shu_session',$uuid,'id_shu_session');
                                                if(empty($id_shu_session)){
                                                    //Hapus data sessi dan rincian
                                                    $HapusShuSession = mysqli_query($Conn, "DELETE FROM shu_session WHERE id_shu_session='$id_shu_session'") or die(mysqli_error($Conn));
                                                    $HapusSessiRincian = mysqli_query($Conn, "DELETE FROM shu_rincian WHERE id_shu_session='0'") or die(mysqli_error($Conn));
                                                    echo '<small class="text-danger">Terjadi kesalahan pada saat membentuk id_shu_session dimana id_shu_session tidak ditemukan</small>';
                                                }else{
                                                    //Update Rincian
                                                    $UpdateRincian = mysqli_query($Conn,"UPDATE shu_rincian SET 
                                                        id_shu_session='$id_shu_session'
                                                    WHERE id_shu_session='0'") or die(mysqli_error($Conn)); 
                                                    if($UpdateRincian){
                                                        $KategoriLog="Bagi Hasil";
                                                        $KeteranganLog="Tambah Sesi Bagi Hasil Berhasil";
                                                        include "../../_Config/InputLog.php";
                                                        echo '<small class="text-success" id="NotifikasiTambahBagiHasilBerhasil">Success</small>';
                                                    }else{
                                                        //Hapus data sessi dan rincian
                                                        $HapusShuSession = mysqli_query($Conn, "DELETE FROM shu_session WHERE id_shu_session='$id_shu_session'") or die(mysqli_error($Conn));
                                                        $HapusSessiRincian = mysqli_query($Conn, "DELETE FROM shu_rincian WHERE id_shu_session='0'") or die(mysqli_error($Conn));
                                                        echo '<small class="text-danger">Terjadi kesalahan pada saat update data rincian</small>';
                                                    }
                                                }
                                            }else{
                                                //Hapus data sessi dan rincian
                                                $HapusSessiRincian = mysqli_query($Conn, "DELETE FROM shu_rincian WHERE id_shu_session='0'") or die(mysqli_error($Conn));
                                                echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data sesi</small>';
                                            }
                                        }else{
                                            //Hapus data rincian
                                            $HapusSessiRincian = mysqli_query($Conn, "DELETE FROM shu_rincian WHERE id_shu_session='0'") or die(mysqli_error($Conn));
                                            echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data rincian sesi ('.$JumlahAnggotaProses.':'.$JumlahAnggota.')</small>';
                                        }
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