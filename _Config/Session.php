<?php
    //Menangkap seasson kemudian menampilkannya
    session_start();
    date_default_timezone_set('Asia/Jakarta');
    if(empty($_SESSION["id_akses"])){
        $SessionIdAkses="";
        $SessionModeAkses="";
        $SessionLoginToken="";
    }else{
        if(empty($_SESSION["login_token"])){
            $SessionIdAkses="";
            $SessionModeAkses="";
            $SessionLoginToken="";
        }else{
            if(empty($_SESSION["mode_akses"])){
                $SessionIdAkses="";
                $SessionModeAkses="";
                $SessionLoginToken="";
            }else{
                $SessionIdAkses=$_SESSION ["id_akses"];
                $SessionModeAkses=$_SESSION ["mode_akses"];
                $SessionLoginToken=$_SESSION ["login_token"];
                //Membersihkan Variabel
                $SessionIdAkses=validateAndSanitizeInput($SessionIdAkses);
                $SessionModeAkses=validateAndSanitizeInput($SessionModeAkses);
                $SessionLoginToken=validateAndSanitizeInput($SessionLoginToken);
                //Validasi Token Akses
                $stmt = mysqli_prepare($Conn, "SELECT * FROM akses_login WHERE id_akses = ? AND token = ? AND kategori = ?");
                if (!$stmt) {
                    die("Error dalam persiapan statement: " . mysqli_error($Conn));
                }
                mysqli_stmt_bind_param($stmt, "iss", $SessionIdAkses, $SessionLoginToken, $SessionModeAkses);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $DataAksesLogin = mysqli_fetch_array($result);
                mysqli_stmt_close($stmt);
                //Apabila Tidak Ada
                if(empty($DataAksesLogin['id_akses'])){
                    $SessionIdAkses="";
                    $SessionModeAkses="";
                    $SessionLoginToken="";
                }else{
                    //Apabila Ada
                    $SessionDateCreat=$DataAksesLogin['date_creat'];
                    //Validasi Apakah Token Masih Berlaku Atau Tidak
                    $SessionDateExpired=$DataAksesLogin['date_expired'];
                    $DateSekarang=date('Y-m-d H:i:s');
                    if($SessionDateExpired<$DateSekarang){
                        $SessionIdAkses="";
                        $SessionModeAkses="";
                        $SessionLoginToken="";
                    }else{
                        $SessionIdAkses=$DataAksesLogin['id_akses'];
                        $SessionModeAkses=$DataAksesLogin['kategori'];
                        $expired_milisecond=1000*60*60;
                        $now=date('Y-m-d H:i:s');
                        $date_expired_new=calculateExpirationTimeFromDateTime($now, $expired_milisecond);
                        //Update Token Yang Ada
                        $stmt_update = mysqli_prepare($Conn, "UPDATE akses_login SET date_expired = ? WHERE id_akses = ? AND kategori = ?");
                        if (!$stmt_update) {
                            die("Error dalam persiapan statement: " . mysqli_error($Conn));
                        }
                        mysqli_stmt_bind_param($stmt_update, "sis", $date_expired_new, $SessionIdAkses, $SessionModeAkses);
                        $UpdateToken = mysqli_stmt_execute($stmt_update);
                        if($UpdateToken){
                            $SessionIdAkses=$DataAksesLogin['id_akses'];
                            $SessionModeAkses=$DataAksesLogin['kategori'];
                            $SessionLoginToken=$DataAksesLogin['token'];
                        }else{
                            $SessionIdAkses="";
                            $SessionModeAkses="";
                            $SessionLoginToken="";
                        }
                        mysqli_stmt_close($stmt_update);
                    }
                }
            }
        }
    }
?>
