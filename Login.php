<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            //Koneksi
            session_start();
            include "_Config/Connection.php";
            include "_Config/GlobalFunction.php";
            include "_Config/SettingGeneral.php";
            include "_Partial/Head.php";
        ?>
        <style>
            .landing_background {
                background: linear-gradient(rgba(0, 191, 165, 0.75), rgba(0, 191, 165, 0.75)),
                    url("assets/img/Ui/texture.svg?v=2") 0 0 / 32px 32px repeat;
            }

            .login-logo img {
                display: block;
                width: 100%;
                max-width: 280px;
                height: auto;
            }
        </style>
    </head>
    <body>
        <main class="landing_background">
            <div class="container">
                <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                                <div class="login-logo d-flex justify-content-center w-100 py-2">
                                    <img src="assets/img/Ui/logo.png" alt="Koperasi">
                                </div>
                                <div class="card mb-3">
                                    <?php
                                        if(empty($_GET['Page'])){
                                            include "_Page/Login/Login.php";
                                        }else{
                                            $Page=$_GET['Page'];
                                            if($Page=="LupaPassword"){
                                                include "_Page/ResetPassword/FormLupaPassword.php";
                                            }else{
                                                if($Page=="ResetPassword"){
                                                    include "_Page/ResetPassword/FormResetPassword.php";
                                                }
                                            }
                                        }
                                    ?>
                                </div>
                                <div class="credits text-center">
                                    <small>
                                        <div class="copyright text-white">
                                            &copy; Copyright <strong><span><?php echo "$title_page"; ?></span></strong>. All Rights Reserved 2023
                                        </div>
                                        <div class="credits text-white">
                                            Designed by <span class="text text-decoration-underline"><?php echo "$AuthorAplikasi"; ?></span>
                                        </div>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
    </main>
        <?php
            include "_Partial/FooterJs.php";
        ?>
        <script>
            //Kondisi saat tampilkan password
            $('#TampilkanPassword2').click(function(){
                if($(this).is(':checked')){
                    $('#password').attr('type','text');
                }else{
                    $('#password').attr('type','password');
                }
            });

            //Submit Login
            $('#ProsesLogin').submit(function(){
                var ProsesLogin = $('#ProsesLogin').serialize();
                var Loading='<div class="spinner-border text-info" role="status"><span class="visually-hidden">Loading...</span></div>';
                $('#NotifikasiLogin').html("Loading...");
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Login/ProsesLogin.php',
                    data 	    :  ProsesLogin,
                    success     : function(data){
                        $('#NotifikasiLogin').html(data);
                        var NotifikasiProsesLoginBerhasil=$('#NotifikasiProsesLoginBerhasil').html();
                        if(NotifikasiProsesLoginBerhasil=="Success"){
                            window.location.href = "index.php";
                        }
                    }
                });
            });

            //Proses Kirim Tautan Lupa Password
            $('#ProsesLupaPassword').submit(function(){
                var ProsesLupaPassword = $('#ProsesLupaPassword').serialize();
                var Loading='<div class="spinner-border text-info" role="status"><span class="visually-hidden">Loading...</span></div>';
                $('#NotifikasiLupaPassword').html("Loading...");
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/ResetPassword/ProsesLupaPassword.php',
                    data 	    :  ProsesLupaPassword,
                    success     : function(data){
                        $('#NotifikasiLupaPassword').html(data);
                        var NotifikasiLupaPasswordBerhasil=$('#NotifikasiLupaPasswordBerhasil').html();
                        if(NotifikasiLupaPasswordBerhasil=="Success"){
                            //Tampilkan Swal Bahwa Proses Berhasil
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Kami telah mengirim tautan ke email anda',
                                icon: 'success',
                                confirmButtonText: 'Tutup'
                            }).then((result) => {
                                if (result.isConfirmed || result.dismiss === Swal.DismissReason.close) {
                                    window.location.href = 'Login.php';
                                }
                            });
                        }
                    }
                });
            });

            //Kondisi saat tampilkan password
            $('.form-check-input').click(function(){
                if($(this).is(':checked')){
                    $('#PasswordBaru1').attr('type','text');
                    $('#PasswordBaru2').attr('type','text');
                }else{
                    $('#PasswordBaru1').attr('type','password');
                    $('#PasswordBaru2').attr('type','password');
                }
            });
            $('#ProsesResetPassword').submit(function(){
                var ProsesResetPassword = $('#ProsesResetPassword').serialize();
                var Loading='<div class="spinner-border text-info" role="status"><span class="visually-hidden">Loading...</span></div>';
                $('#NotifikasiResetPassword').html("Loading...");
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/ResetPassword/ProsesResetPassword.php',
                    data 	    :  ProsesResetPassword,
                    success     : function(data){
                        $('#NotifikasiResetPassword').html(data);
                        var NotifikasiResetPasswordBerhasil=$('#NotifikasiResetPasswordBerhasil').html();
                        if(NotifikasiResetPasswordBerhasil=="Success"){
                            //Tampilkan Swal Bahwa Proses Berhasil
                            Swal.fire({
                                title: 'Ubah Password Berhasil',
                                text: 'Silahkan Login Menggunakan Password Baru Anda',
                                icon: 'success',
                                confirmButtonText: 'Tutup'
                            }).then((result) => {
                                if (result.isConfirmed || result.dismiss === Swal.DismissReason.close) {
                                    window.location.href = 'Login.php';
                                }
                            });
                        }
                    }
                });
            });
        </script>
    </body>
</html>