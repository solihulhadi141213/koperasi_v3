<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            //Koneksi
            session_start();
            include "_Config/Connection.php";
            include "_Config/SettingGeneral.php";
            include "_Partial/Head.php";
        ?>
    </head>
    <body>
        <main class="landing_background">
            <div class="container">
                <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                                <img src="assets/img/<?php echo $logo;?>" alt="<?php echo $title_page;?>" width="100px">
                                <div class="d-flex justify-content-center py-2">
                                    <p>
                                        <a href="" class="logo d-flex align-items-center w-auto">
                                            <span class="d-none d-lg-block text-light"><?php echo $title_page;?></span>
                                        </a>
                                    </p>
                                </div>
                                <div class="card mb-3">
                                    <?php
                                        if(empty($_GET['Page'])){
                                            include "_Page/Login/Login.php";
                                        }else{
                                            $Page=$_GET['Page'];
                                            if($Page=="LupaPassword"){
                                                include "_Page/ResetPassword/ResetPassword.php";
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
                            window.location.href = "LupaPassword.php?Page=KirimKodeBerhasil";
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
            $('#ProsesUbahPassword').submit(function(){
                var ProsesUbahPassword = $('#ProsesUbahPassword').serialize();
                var Loading='<div class="spinner-border text-info" role="status"><span class="visually-hidden">Loading...</span></div>';
                $('#NotifikasiUbahPassword').html("Loading...");
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/ResetPassword/ProsesUbahPassword.php',
                    data 	    :  ProsesUbahPassword,
                    success     : function(data){
                        $('#NotifikasiUbahPassword').html(data);
                        var NotifikasiUbahPasswordBerhasil=$('#NotifikasiUbahPasswordBerhasil').html();
                        if(NotifikasiUbahPasswordBerhasil=="Success"){
                            window.location.href = "LupaPassword.php?Page=Berhasil";
                        }
                    }
                });
            });
        </script>
    </body>
</html>