<?php
    //Atur Tanggal Sekarang Dan Satu Bulan Ke belakang
    $tanggal_sekarang=date('Y-m-d');

    // Tanggal satu bulan ke belakang
    $tanggal_sebulan_kebelakang = date('Y-m-d', strtotime('-1 month'));
?>

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
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="batas">
                                <small>
                                    Batas/Limit
                                    <a href="javascript:void(0);" 
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        data-bs-custom-class="custom-tooltip" 
                                        data-bs-title="Jumlah / Limit Baris Data Yang Ditampilkan"
                                    >
                                        <i class="bi bi-question-circle"></i>
                                    </a>
                                </small>
                            </label>
                        </div>
                        <div class="col-1"><small>:</small></div>
                        <div class="col-7">
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
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="OrderBy">
                                <small>
                                    Mode Urutan 
                                    <a href="javascript:void(0);" 
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        data-bs-custom-class="custom-tooltip" 
                                        data-bs-title="Dasar Atribut Urutan Data"
                                    >
                                        <i class="bi bi-question-circle"></i>
                                    </a>
                                </small>
                            </label>
                        </div>
                        <div class="col-1"><small>:</small></div>
                        <div class="col-7">
                            <select name="OrderBy" id="OrderBy" class="form-control">
                                <option value="">Pilih</option>
                                <option value="nip">Nomor Induk</option>
                                <option value="nama">Nama Anggota</option>
                                <option value="status">Status Anggota</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="ShortBy">
                                <small>
                                    Tipe Urutan 
                                    <a href="javascript:void(0);" 
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        data-bs-custom-class="custom-tooltip" 
                                        data-bs-title="Metode Urutan Data Yang Digunakan. A to Z (Secara Ascending) atau Z to A (Descanding)"
                                    >
                                        <i class="bi bi-question-circle"></i>
                                    </a>
                                </small>
                            </label>
                        </div>
                        <div class="col-1"><small>:</small></div>
                        <div class="col-7">
                            <select name="ShortBy" id="ShortBy" class="form-control">
                                <option value="ASC">A To Z</option>
                                <option selected value="DESC">Z To A</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="keyword_by">
                                <small>
                                    Dasar Pencarian 
                                    <a href="javascript:void(0);" 
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        data-bs-custom-class="custom-tooltip" 
                                        data-bs-title="Dasar Atribut Untuk Pencarian Data"
                                    >
                                        <i class="bi bi-question-circle"></i>
                                    </a>
                                </small>
                            </label>
                        </div>
                        <div class="col-1"><small>:</small></div>
                        <div class="col-7">
                            <select name="keyword_by" id="keyword_by" class="form-control">
                                <option value="">Pilih</option>
                                <option value="nip">Nomor Induk</option>
                                <option value="nama">Nama Anggota</option>
                                <option value="status">Status Anggota</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="keyword">
                                <small>
                                    Kata Kunci 
                                    <a href="javascript:void(0);" 
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        data-bs-custom-class="custom-tooltip" 
                                        data-bs-title="Kata Kunci Untuk Pencarian Data"
                                    >
                                        <i class="bi bi-question-circle"></i>
                                    </a>
                                </small>
                            </label>
                        </div>
                        <div class="col-1"><small>:</small></div>
                        <div class="col-7">
                            <input type="text" name="keyword" id="keyword" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="periode_1">
                                <small>
                                    Periode Awal
                                </small>
                            </label>
                        </div>
                        <div class="col-1"><small>:</small></div>
                        <div class="col-7">
                            <input type="date" name="periode_1" id="periode_1" class="form-control" value="<?php echo $tanggal_sebulan_kebelakang; ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="periode_2">
                                <small>
                                    Periode Akhir
                                </small>
                            </label>
                        </div>
                        <div class="col-1"><small>:</small></div>
                        <div class="col-7">
                            <input type="date" name="periode_2" id="periode_2" class="form-control" value="<?php echo $tanggal_sekarang; ?>">
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

<div class="modal fade" id="ModalDetailPotongan" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesCetakDetailPotongan">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-info-circle"></i> Detail Potongan Anggota
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12" id="FormDetailPotongan">
                            <!-- Detail Potongan Akan Ditampilkan Disini -->
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12" id="NotifikasiCetak">
                            <!-- Notifikasi Cetak Akan Ditampilkan Disini -->
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="tipe_cetak">
                                <small>Format/Type</small>
                            </label>
                        </div>
                        <div class="col-1"><small>:</small></div>
                        <div class="col-7">
                            <select name="tipe_cetak" id="tipe_cetak" class="form-control">
                                <option value="Direct">Direct (HTML)</option>
                                <option value="PDF">PDF</option>
                                <option value="Image">Image</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-rounded" id="ButtonCetak">
                        <i class="bi bi-printer"></i> Cetak Rincian
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>