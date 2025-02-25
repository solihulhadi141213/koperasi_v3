<div class="modal fade" id="ModalFilter" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesFilter">
                <input type="hidden" name="page" id="page" value="1">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-funnel"></i> Filter Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label for="batas">Data/Limit</label>
                            <select name="batas" id="batas" class="form-control">
                                <option value="5">5</option>
                                <option selected value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="250">250</option>
                                <option value="500">500</option>
                            </select>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label for="OrderBy">Mode Urutan</label>
                            <select name="OrderBy" id="OrderBy" class="form-control">
                                <option value="">Pilih</option>
                                <option value="kategori">Kategori</option>
                                <option value="tanggal">Tanggal</option>
                                <option value="nama">Anggota</option>
                                <option value="status">Status</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label for="ShortBy">Tipe Urutan</label>
                            <select name="ShortBy" id="ShortBy" class="form-control">
                                <option value="ASC">A To Z</option>
                                <option selected value="DESC">Z To A</option>
                            </select>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label for="keyword_by">Pencarian</label>
                            <select name="keyword_by" id="keyword_by" class="form-control">
                                <option value="">Pilih</option>
                                <option value="kategori">Kategori</option>
                                <option value="tanggal">Tanggal</option>
                                <option value="nama">Anggota</option>
                                <option value="status">Status</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-3" id="FormFilterKeyword">
                            <label for="keyword">Kata Kunci</label>
                            <input type="text" name="keyword" id="keyword" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-save"></i> Filter
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalTambahTransaksiPenjualan" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark"><i class="bi bi-plus"></i> Tambah Transaksi Penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            <small>
                                <h3>
                                    <a href="index.php?Page=Penjualan&Sub=TambahPenjualan&retur=Tidak" class="text-success">
                                        <i class="bi bi-cart-check"></i> Penjualan
                                    </a>
                                </h3>
                                Buat transaksi penjualan baru. Hubungkan penjualan dengan data anggota dan cetak nota transaksi.
                            </small>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-warning">
                            <small>
                                <h3>
                                    <a href="index.php?Page=Penjualan&Sub=TambahPenjualan&retur=Ya" class="text-warning">
                                        <i class="bi bi-arrow-left-right"></i> Retur Penjualan
                                    </a>
                                </h3>
                                Buat faktur transaksi retur penjualan (pengembalian penjualan barang) dari transaksi sebelumnya.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalCariBarang" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark"><i class="bi bi-search"></i> Cari Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <form action="javascript:void(0);" id="ProsesCariBarang">
                            <input type="hidden" name="page" id="put_page_cari_barang" value="1">
                            <div class="input-group">
                                <input type="text" class="form-control" name="keyword" id="keyword_barang" placeholder="Nama Barang / Kode">
                                <button type="submit" class="btn btn-md btn-primary">
                                    <i class="bi bi-search"></i> Cari
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="table table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th><b>No</b></th>
                                        <th><b>Kode</b></th>
                                        <th><b>Nama Barang</b></th>
                                        <th><b>Stok</b></th>
                                        <th><b>Opsi</b></th>
                                    </tr>
                                </thead>
                                <tbody id="TabelBarang">
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <small class="text-danger">Tidak Ada Data Yang Ditampilkan</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <small id="page_info_barang">
                            Page 1 Of 100
                        </small>
                    </div>
                    <div class="col-6 text-end">
                        <button type="button" class="btn btn-sm btn-outline-info btn-floating" id="prev_button_barang">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-info btn-floating" id="next_button_barang">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalScanBarang" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesScanCode">
                <input type="hidden" name="kategori_transaksi" id="put_kategori_transaksi_scan">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-qr-code-scan"></i> Scan Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="keyword_code">Kode Barang</label>
                            <input type="text" class="form-control form-control-lg" name="keyword_code" id="keyword_code" placeholder="Kode Barang">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="qty_scan_code">QTY</label>
                            <input type="number" min="1" class="form-control" name="qty_scan_code" id="qty_scan_code" value="1">
                        </div>
                        <div class="col-6">
                            <label for="id_barang_kategori_harga_scan">Kategori Harga</label>
                            <select name="id_barang_kategori_harga" id="id_barang_kategori_harga_scan" class="form-control">
                                <?php
                                    //Tampilkan Kategori Harga
                                    $query_kategori_harga = mysqli_query($Conn, "SELECT id_barang_kategori_harga, kategori_harga FROM barang_kategori_harga ORDER BY id_barang_kategori_harga ASC");
                                    while ($data_kategori_harga = mysqli_fetch_array($query_kategori_harga)) {
                                        $id_barang_kategori_harga= $data_kategori_harga['id_barang_kategori_harga'];
                                        $kategori_harga= $data_kategori_harga['kategori_harga'];
                                        echo '<option value="'.$id_barang_kategori_harga.'">'.$kategori_harga.'</option>';
                                    }
                                    echo '<option value="">Harga Jual</option>';
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3 mt-3">
                        <div class="col-md-12" id="preview_hasil_sacan">
                            <div class="alert alert-danger">
                                <small>Silahkan Scan Kode Barang Terlebih Dulu</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="NotifikasiScanCode">
                            <!-- Notifikasi Scan Code -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-md btn-primary btn-rounded">
                        <i class="bi bi-plus"></i> Tambahkan
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="ModalTambahBarang" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesTambahBarang">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-plus"></i> Tambah Rincian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12" id="FormTambahBarang">
                            <!-- Form Tambah Bulk -->
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12" id="SimulasiRincian">
                            <!-- Menampilkan Simulasi Rincian Disini -->
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12" id="NotifikasiTambahBarang">
                            <!-- Notifikasi Tambah Bulk Disini -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-md btn-primary btn-rounded">
                        <i class="bi bi-plus"></i> Tambahkan
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-toggle="modal" data-bs-target="#ModalCariBarang">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="ModalEditBulk" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesEditBulk">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-pencil"></i> Edit Rincian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12" id="FormEditBulk">
                            <!-- Form Edit Rincian Bulk -->
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12" id="NotifikasiEditBulk">
                            <!-- Notifikasi Edit Bulk Barang Disini -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-md btn-primary btn-rounded">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="ModalHapusBulk" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesHapusBulk">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-trash"></i> Hapus Rincian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12" id="FormHapusBulk">
                            <!-- Form Hapus Rincian Bulk -->
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12" id="NotifikasiHapusBulk">
                            <!-- Notifikasi Hapus Bulk Disini -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-md btn-success btn-rounded" id="ButtonHapusBulk">
                        <i class="bi bi-check"></i> Ya, Hapus
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tidak
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="ModalResetTransaksi" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesResetTransaksi">
                <input type="hidden" name="kategori_transaksi" id="put_kategori_transaksi_for_reset">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-repeat"></i> Reset Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <b>Keterangan</b>
                                <p>
                                    Dengan melakukan reset maka sistem akan menghapus semua rincian barang pada transaksi ini. 
                                    Pilih tombol reset untuk mulai melakukan reset transaksi.
                                </p>
                                
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12" id="NotifikasiResetTransaksi">
                            <!-- Notifikasi Reset Transaksi Disini -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-md btn-success btn-rounded" id="ButtonResetTransaksi">
                        <i class="bi bi-check"></i> Reset
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>