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
        if(empty($_POST['id_shu_session'])){
            echo '<span class="text-danger">ID Sesi tidak dapat ditangkap oleh sistem</span>';
        }else{
            $id_shu_session=$_POST['id_shu_session'];
            $uuid=GetDetailData($Conn,'shu_session','id_shu_session',$id_shu_session,'uuid_shu_session');
            //Proses hapus Sessi
            $HapusSessi = mysqli_query($Conn, "DELETE FROM shu_session WHERE id_shu_session='$id_shu_session'") or die(mysqli_error($Conn));
            if($HapusSessi) {
                $HapusRincianSessi = mysqli_query($Conn, "DELETE FROM shu_rincian WHERE id_shu_session='$id_shu_session'") or die(mysqli_error($Conn));
                if($HapusRincianSessi) {
                    $HapusJurnal = mysqli_query($Conn, "DELETE FROM jurnal WHERE uuid='$uuid'") or die(mysqli_error($Conn));
                    if($HapusJurnal) {
                        echo '<span class="text-success" id="NotifikasiHapusSesiBagiHasilBerhasil">Success</span>';
                        $KategoriLog="Bagi Hasil";
                        $KeteranganLog="Hapus Bagi Hasil Berhasil";
                        include "../../_Config/InputLog.php";
                    }else{
                        echo '<span class="text-danger">Terjadi kesalahan pada saat menghapus data Rincian Bagi Hasil</span>';
                    }
                }else{
                    echo '<span class="text-danger">Terjadi kesalahan pada saat menghapus data Rincian Bagi Hasil</span>';
                }
            }else{
                echo '<span class="text-danger">Terjadi kesalahan pada saat menghapus data Bagi Hasil</span>';
            }
        }
    }
?>