<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'rfLn8WEkAqzC1gu5z45');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
        include "_Config/SettingEmail.php";
?>
    <div class="pagetitle">
        <h1>
            <a href="">
                <i class="bi bi-gear"></i> Email Gateway</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active"> Email Gateway</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row mb-3">
            <div class="col-md-12">
                <?php
                    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
                    echo '  <small>';
                    echo '      Berikut ini adalah halaman pengaturan email gateway.';
                    echo '      Apabila pengaturan ini berfungsi dengan baik maka sistem akan menggunakan parameter ini untuk melakukan pengirian data dalam beberapa fitur.';
                    echo '      Lakukan pengujian (Test) untuk memastikan bahwa parameter yang digunakan sudah sesuai.';
                    echo '      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '  </small>';
                    echo '</div>';
                ?>
            </div>
        </div>
        <div class="row mb-3 d-flex align-items-stretch">
            <div class="col-md-6 d-flex">
                <form action="javascript:void(0);" id="ProsesSettingEmail" class="w-100">
                    <div class="card h-100">
                        <div class="card-header">
                            <b class="card-title">
                                <i class="bi bi-gear"></i> Form Pengaturan Email Gateway
                            </b>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label" for="url_service">
                                        URL Service Gateway
                                    </label>
                                    <input type="text" name="url_service" id="url_service" class="form-control" required value="<?php echo "$url_service"; ?>">
                                    <small class="text text-grayish">
                                        URL yang mengarah pada service gateway.
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label" for="url_provider">URL Provider</i></label>
                                    <input type="text" name="url_provider" id="url_provider" class="form-control" required value="<?php echo "$url_provider"; ?>">
                                    <small class="text text-grayish">
                                        URL yang mengarah pada provider SMTP. Beberapa provider memilikki standar penggunaan URL yang berbeda. 
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label" for="email_gateway">Email Account</label>
                                    <input type="email" name="email_gateway" id="email_gateway" class="form-control" required value="<?php echo "$email_gateway"; ?>">
                                    <small class="text text-grayish">
                                        Akun email dari web mail pada layanan hosting.
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label" for="password_gateway">Password Email</label>
                                    <input type="text" name="password_gateway" id="password_gateway" class="form-control" required value="<?php echo "$password_gateway"; ?>">
                                    <small class="text text-grayish">
                                        Password akun email yang telah di buat pada web mail pada layanan hosting.
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label" for="nama_pengirim">Nama Pengirim</label>
                                    <input type="text" name="nama_pengirim" id="nama_pengirim" class="form-control" required value="<?php echo "$nama_pengirim"; ?>">
                                    <small class="text text-grayish">
                                        Nama pengirim yang disematkan pada saat mengirim email.
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label" for="port_gateway">Port SMTP</label>
                                    <input type="text" name="port_gateway" id="port_gateway" class="form-control" required value="<?php echo "$port_gateway"; ?>">
                                    <small class="text text-grayish">
                                        Port SMTP yang terbuka untuk proses pengiriman email.
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12" id="NotifikasiSimpanSettingEmail">
                                    <!-- Notifikasi Setting Email Akan Muncul Disini -->
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-md btn-primary">
                                <i class="bi bi-save"></i> Simpan Pengaturan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6 d-flex">
                <form action="javascript:void(0);" id="ProsesTestKirimEmail" class="w-100">
                    <div class="card h-100">
                        <div class="card-header">
                            <b class="card-title">
                                <i class="bi bi-airplane"></i> Uji Coba Kirim Email
                            </b>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="nama_tujuan">Dikirim Kepada</label>
                                    <input type="text" name="nama_tujuan" id="nama_tujuan" class="form-control" placeholder="Contoh : Bapak Syamsul Maarif">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="email_tujuan">Email Tujuan</label>
                                    <input type="email" name="email_tujuan" id="email_tujuan" class="form-control" placeholder="email@domain.com">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="subjek">Subjek Pesan</label>
                                    <input type="text" name="subjek" id="subjek" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="pesan">Isi Pesan</label>
                                    <textarea name="pesan" id="pesan" cols="30" rows="3" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4" id="NotifikasiTestKirimEmail">
                                    <!-- Notifikasi Test Kirim Email Akan Muncul Disini -->
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-md btn-primary">
                                <i class="bi bi-send"></i> Kirim Pesan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
<?php } ?>