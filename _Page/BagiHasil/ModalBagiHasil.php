<div class="modal fade" id="ModalFilter" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesFilter">
                <input type="hidden" name="page" id="page" value="1">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-funnel"></i> Filter Bagi Hasil
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="batas">Data/Halaman</label>
                        </div>
                        <div class="col-md-8">
                            <select name="batas" id="batas" class="form-control">
                                <option value="1">1</option>
                                <option value="5">5</option>
                                <option selected value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="250">250</option>
                                <option value="500">500</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="OrderBy">Mode Urutan</label>
                        </div>
                        <div class="col-md-8">
                            <select name="OrderBy" id="OrderBy" class="form-control">
                                <option value="">Pilih</option>
                                <option value="sesi_shu">Sesi SHU</option>
                                <option value="periode_hitung1">Periode Awal</option>
                                <option value="periode_hitung2">Periode Akhir</option>
                                <option value="modal_anggota">Modal Anggota</option>
                                <option value="penjualan">Penjualan</option>
                                <option value="persen_usaha">Persen Usaha</option>
                                <option value="persen_modal">Persen Modal</option>
                                <option value="status">Status</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="ShortBy">Tipe Urutan</label>
                        </div>
                        <div class="col-md-8">
                            <select name="ShortBy" id="ShortBy" class="form-control">
                                <option value="ASC">A To Z</option>
                                <option value="DESC">Z To A</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="keyword_by">Tipe Urutan</label>
                        </div>
                        <div class="col-md-8">
                            <select name="keyword_by" id="keyword_by" class="form-control">
                                <option value="">Pilih</option>
                                <option value="sesi_shu">Sesi SHU</option>
                                <option value="periode_hitung1">Periode Awal</option>
                                <option value="periode_hitung2">Periode Akhir</option>
                                <option value="modal_anggota">Modal Anggota</option>
                                <option value="penjualan">Penjualan</option>
                                <option value="persen_usaha">Persen Usaha</option>
                                <option value="persen_modal">Persen Modal</option>
                                <option value="status">Status</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="keyword">Kata Kunci</label>
                        </div>
                        <div class="col-md-8" id="FormFilter">
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
<div class="modal fade" id="ModalExportBagiHasil" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-light"><i class="bi bi-download"></i> Export Bagi Hasil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="FormExportBagiHasil">
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalTambahBagiHasil" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesTambahBagiHasil">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-plus"></i> Tambah Bagi Hasil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12" id="FormTambahBagiHasil">
                            <!-- Form Tambah Bagi Hasil Akan Muncul Disini -->
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"></div>
                        <div class="col-md-8" id="NotifikasiTambahBagiHasil">
                            <!-- Notifikasi Tambah Bagi Hasil Akan Muncul Disini -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalDetailBagiHasil" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark">
                    <i class="bi bi-info-circle"></i> Detail Bagi Hasil (SHU)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="FormDetailBagiHasil">
                <!-- Informasi Detail Akan Ditampilkan Disini -->
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalEditBagiHasil" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesEditBagiHasil">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-pencil-square"></i> Edit Bagi Hasil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12" id="FormEditBagiHasil">
                            <!-- Menampilkan Form Edit Bagi Hasil -->
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"></div>
                        <div class="col-md-8" id="NotifikasiEditBagiHasil">
                            <!-- Menampilkan Notifikasi Edit Bagi Hasil -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalHapusBagiHasil" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesHapusBagiHasil">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-trash"></i> Hapus Sesi Bagi Hasil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="FormHapusBagiHasil">
                            <!-- Form Hapus Bagi Hasil Akan Muncul Disini -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center" id="NotifikasiHapusSesiBagiHasil">
                            <!-- Notifikasi Hapus Bagi Hasil Akan Muncul Disini -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-check"></i> Ya, Hapus
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tidak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalDetailRincian" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-light"><i class="bi bi-pencil-square"></i> Detail Rincian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="FormDetailRincian">
                
            </div>
            <div class="modal-footer bg-info">
                <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalEditRincian" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesEditRincian">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-light"><i class="bi bi-pencil-square"></i> Edit Rincian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="FormEditRincian">
                    
                </div>
                <div class="modal-footer bg-success">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalDeleteRincian" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-light"><i class="bi bi-trash"></i> Hapus Rincian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="FormDeleteRincian">
                
            </div>
            <div class="modal-footer bg-danger">
                <button type="button" class="btn btn-success btn-rounded" id="KonfirmasiHapusRincian">
                    <i class="bi bi-check"></i> Ya
                </button>
                <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tidak
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalTambahJurnal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesTambahJurnal">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-plus"></i> Tambah Jurnal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12" id="FormTambahJurnal">
                            <!-- Form Tambah Jurnal -->
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"></div>
                        <div class="col-md-8" id="NotifikasiTambahJurnal">
                            <!-- Notifikasi Tambah Jurnal -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalEditJurnal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesEditJurnal">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-pencil"></i> Edit Jurnal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12" id="FormEditJurnal">
                            <!-- Form Edit Jurnal -->
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"></div>
                        <div class="col-md-8" id="NotifikasiEditJurnal">
                            <!-- Notifikasi Edit Jurnal -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalHapusJurnal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesHapusJurnal">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-trash"></i> Hapus Jurnal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12" id="FormHapusJurnal">
                            <!-- Form Hapus Jurnal -->
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12" id="NotifikasiHapusJurnal">
                            <!-- Notifikasi Hapus Jurnal -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>