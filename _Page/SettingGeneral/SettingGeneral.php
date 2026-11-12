<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'qLECdqLVgBMjV0BXHUC');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
?>
    <div class="pagetitle">
        <h1>
            <a href="">
                <i class="bi bi-gear"></i> Pengaturan Umum</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active"> Pengaturan Umum</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        
        <div class="row">
            <div class="col-md-6 mb-3 d-flex">
                <form action="javascript:void(0);" class="h-100 w-100" id="ProsesSettingGeneral">
                    <div class="card h-100">
                        <div class="card-header">
                            <b class="card-title">
                                <i class="bi bi-info-circle"></i> Informasi Koperasi
                            </b>
                        </div>
                        <div class="card-body flex-grow-1">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="title_page">Nama Koperasi</label>
                                    <input type="text" name="title_page" id="title_page" class="form-control" placeholder="Koperasi Andalan Jaya" value="<?php echo "$title_page"; ?>">
                                    <small>Maksimal 20 karakter</small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="deskripsi">Deskripsi</label>
                                    <input type="text" name="deskripsi" id="deskripsi" class="form-control" value="<?php echo "$deskripsi"; ?>">
                                    <small>Deskripsikan Koperasi Anda</small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="alamat_bisnis">Alamat Operasional</label>
                                    <textarea name="alamat_bisnis" id="alamat_bisnis" cols="30" rows="3" class="form-control"><?php echo "$alamat_bisnis"; ?></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="email">Email Perusahaan</label>
                                    <input type="email" name="email_bisnis" id="email_bisnis" class="form-control" placeholder="email@domain.com" value="<?php echo "$email_bisnis"; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="telepon_bisnis">Kontak (WA/Telepon)</label>
                                    <input type="text" name="telepon_bisnis" id="telepon_bisnis" class="form-control" placeholder="62" value="<?php echo "$telepon_bisnis"; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="base_url">Base URL</label>
                                    <input type="text" name="base_url" id="base_url" class="form-control" placeholder="https://" value="<?php echo "$base_url"; ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" id="NotifikasiSimpanSettingGeneral">
                                    <!-- Notifikasi Simpan Setting Akan Muncul Disini -->
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-md btn-primary" id="ButtonSettingGeneral">
                                <i class="bi bi-save"></i> Simpan Pengaturan
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-6 mb-3 d-flex">
                <form action="javascript:void(0);" class="h-100 w-100" id="ProsesSettingImage">
                    <div class="card h-100">
                        <div class="card-header">
                            <b class="card-title">
                                <i class="bi bi-image"></i> Logo Perusahaan
                            </b>
                        </div>
                        <div class="card-body flex-grow-1">
                            <div class="row mb-3">
                                <div class="col-12 text-center">
                                    <!-- Bingkai menyesuaikan tampilan logo pada desktop dan perangkat mobile. -->
                                    <div class="setting-general-logo-frame">
                                        <img src="assets/img/Company/<?php echo htmlspecialchars($logo ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                            class="setting-general-logo" alt="Logo Perusahaan">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="logo">upload File Logo Koperasi</label>
                                    <input type="file" name="logo" id="logo" class="form-control">
                                    <small>
                                        Maksimal File 2 Mb (JPG, JPEG, PNG dan GIF)
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12" id="NotifikasiSettingImage">
                                    <!-- Notifikasi Proses Simpan Image Akan Tampil Disini -->
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-md btn-primary" id="ButtonSettingImage">
                                <i class="bi bi-save"></i> Simpan Logo
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
<?php } ?>