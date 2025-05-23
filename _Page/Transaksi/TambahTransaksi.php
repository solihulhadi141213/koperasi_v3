<form action="javascript:void(0);" id="ProsesTambahTransaksi" autocomplete="off">
    <div class="pagetitle">
        <h1>
            <a href="">
                <i class="bi bi-cart-check"></i> Data Transaksi</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active"> Data Transaksi</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <?php
                    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
                    echo '  <small>';
                    echo '      Berikut ini adalah halaman untuk menambah transaksi.';
                    echo '      Jenis transaksi diisi sesuai referensi jenis transaksi yang sudah ada.';
                    echo '      Pastikan kembali bahwa proses posting jurnal sudah sesuai.';
                    echo '      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '  </small>';
                    echo '</div>';
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10 mt-3">
                                <b class="card-title">Form Tambah Data Transaksi</b>
                            </div>
                            <div class="col-md-2 mt-3">
                                <a href="index.php?Page=Transaksi" class="btn btn-md btn-dark btn-rounded btn-block">
                                    <i class="bi bi-chevron-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row border-1 border-bottom mb-3">
                            <div class="col-md-12 mb-3">
                                <b>Informasi Transaksi</b>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="row mb-3">
                                    <div class="col col-md-3">
                                        <label for="id_transaksi_jenis">Jenis Transaksi</label>
                                    </div>
                                    <div class="col col-md-9">
                                        <select name="id_transaksi_jenis" id="id_transaksi_jenis" class="form-control" data-bs-toggle="modal" data-bs-target="#ModalPilihJenisTransaksi">
                                            <option value="">Pilih</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col col-md-3">
                                        <label for="kategori">Kategori Transaksi</label>
                                    </div>
                                    <div class="col col-md-9">
                                        <input type="text" readonly name="kategori" id="kategori" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col col-md-3">
                                        <label for="tanggal">Tanggal & Jam</label>
                                    </div>
                                    <div class="col col-md-5">
                                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="col col-md-4">
                                        <input type="time" name="jam" id="jam" class="form-control" value="<?php echo date('H:i:s'); ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col col-md-3">
                                        <label for="JumlahTotal">Jumlah (Rp)</label>
                                    </div>
                                    <div class="col col-md-9">
                                        <input type="text" name="JumlahTotal" id="JumlahTotal" class="form-control nominal_angka">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col col-md-3">
                                        <label for="JumlahPembayaran">Pembayaran (Rp)</label>
                                    </div>
                                    <div class="col col-md-9">
                                        <input type="text" name="JumlahPembayaran" id="JumlahPembayaran" class="form-control nominal_angka">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col col-md-3">
                                        <label for="status">Status Transaksi</label>
                                    </div>
                                    <div class="col col-md-9">
                                        <select name="status" id="status" class="form-control">
                                            <option value="">Pilih</option>
                                            <option value="Lunas">Lunas</option>
                                            <option value="Utang">Utang</option>
                                            <option value="Piutang">Piutang</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row border-1 border-bottom mb-3">
                            <div class="col-md-12 mb-3">
                                <b>Uraian/Rincian Transaksi</b>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="row mb-3">
                                    <div class="col col-md-3">
                                        <button type="button" class="btn btn-md btn-outline-grayish" id="TambahUraian">
                                            <i class="bi bi-plus"></i> Tambah Uraian
                                        </button>
                                    </div>
                                    <div class="col col-md-9">

                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12 table table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <td align="center"><b>Uraian/Keterangan</b></td>
                                                    <td align="center"><b>Harga</b></td>
                                                    <td align="center"><b>QTY</b></td>
                                                    <td align="center"><b>Satuan</b></td>
                                                    <td align="center"><b>Jumlah</b></td>
                                                    <td align="center"><b>Opsi</b></td>
                                                </tr>
                                            </thead>
                                            <tbody id="UraianTransaksi">
                                                <tr>
                                                    <td align="right" colspan="4">
                                                        <b>SUBTOTAL</b>
                                                    </td>
                                                    <td align="right" id="JumlahTotal2">0</td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <small class="credit">
                                            <b>Keterangan :</b> Apabila anda keluar/memuat ulang halaman maka uraian yang sudah anda buat di tabel atas akan hilang.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="alert alert-info text-center" role="alert">
                                    Pastikan bahwa data transaksi sudah terisi dengan benar!
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3" id="NotifikasiTambahTransaksi">
                                <!-- Notifikasi Tambah Transaksi Akan Muncul Disini -->
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-3 mt-3">
                                <button type="submit" class="btn btn-md btn-block btn-rounded btn-primary">
                                    <i class="bi bi-save"></i> Simpan Transaksi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>