//Fungsi Menampilkan Data Transaksi
function ShowData() {
    var ProsesFilter = $('#ProsesFilter').serialize();
    $('#TabelPenjualan').html('<tr><td class="text-center">Loading...</td></tr>');
    $.ajax({
        type    : 'POST',
        url     : '_Page/Penjualan/TabelPenjualan.php',
        data    : ProsesFilter,
        success: function(data) {
            $('#TabelPenjualan').html(data);
        }
    });
}
//Fungsi Menampilkan Data Rincian Bulk
function ShowDataBulk(get_kategori_transaksi) {
    $('#TabelPenjualanBulk').html('<tr><td class="text-center">Loading...</td></tr>');
    $.ajax({
        type    : 'POST',
        url     : '_Page/Penjualan/TabelPenjualanBulk.php',
        data    : {kategori_transaksi: get_kategori_transaksi},
        success: function(data) {
            $('#TabelPenjualanBulk').html(data);
        }
    });
}

//Fungsi Menampilkan Data Barang
function ShowDataBarang() {
    var ProsesCariBarang = $('#ProsesCariBarang').serialize();
    
    // Efek fade out sebelum loading
    $('#TabelBarang').fadeOut(200, function() {
        $(this).html('<tr><td class="text-center" colspan="5">Loading...</td></tr>').fadeIn(200);
    });

    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Penjualan/TabelBarang.php',
        data        : ProsesCariBarang,
        success     : function(data) {
            // Ganti isi tabel dengan data hasil AJAX dengan efek
            $('#TabelBarang').fadeOut(200, function() {
                $(this).html(data).fadeIn(300);
            });
        }
    });
}

//Fungsi Untuk Format Rupiah
function formatRupiah(angka) {
    return 'Rp ' + parseFloat(angka).toLocaleString('id-ID', { minimumFractionDigits: 0 });
}

// Fungsi untuk memproses input pada elemen dengan class form-money
function processInput(event) {
    let input = event.target;
    let originalValue = input.value;

    // Hilangkan titik dari nilai asli untuk penghitungan
    let rawValue = originalValue.replace(/\./g, "");

    // Format nilai input
    let formattedValue = formatMoney(rawValue);

    // Update nilai input dengan nilai yang telah diformat
    input.value = formattedValue;
}

// Fungsi untuk memformat angka menjadi format ribuan
function formatMoney(value) {
    if (!value) return ""; // Jika kosong, kembalikan string kosong
    // Hilangkan karakter selain angka
    value = value.toString().replace(/[^0-9]/g, "");
    // Tambahkan pemisah ribuan (titik)
    return value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Fungsi untuk menginisialisasi elemen form-money
function initializeMoneyInputs() {
    const moneyInputs = document.querySelectorAll(".form-money");
    moneyInputs.forEach(function (input) {
        // Format nilai awal jika sudah ada
        input.value = formatMoney(input.value);

        // Pastikan input diformat dengan benar
        input.removeEventListener("input", processInput); // Menghapus event listener sebelumnya
        input.addEventListener("input", processInput);
    });
}

//Fungsi Menghitung Simulasi Rincian
function HitungSimulasiRincian() {
    var ProsesTambahBarang = $('#ProsesTambahBarang').serialize();
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Penjualan/ProsesSimulasiRincian.php',
        data        : ProsesTambahBarang,
        success     : function(data) {
            // Ganti isi tabel dengan data hasil AJAX dengan efek
            $('#SimulasiRincian').html(data);
        }
    });
}
function hitungTotal() {
    let qty = parseFloat($('#qty_edit').val()) || 0;
    let harga = parseFloat($('#harga_edit').val().replace(/\D/g, '')) || 0;
    let ppn = parseFloat($('#ppn_edit').val().replace(/\D/g, '')) || 0;
    let diskon = parseFloat($('#diskon_edit').val().replace(/\D/g, '')) || 0;

    // Hitung Subtotal
    let subtotal = qty * harga;

    // Hitung PPN (jika ada)
    let rp_ppn = (ppn / 100) * subtotal;

    // Hitung Diskon (jika ada)
    let rp_diskon = (diskon / 100) * subtotal;

    // Hitung Total
    let total = subtotal + rp_ppn - rp_diskon;

    // Format ke Rupiah
    let total_rp = total.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });

    // Tampilkan hasil
    $('#jumlah_rincian_edit').html(`<h4 class="text text-grayish">${total_rp}</h4>`);
}

$(document).ready(function() {
    //Menampilkan Data Pertama Kali
    if ($("#TabelPenjualan").length) {
        ShowData();
    }

    //Ketika kembali
    $(".button_kembali").on("click", function () {
        window.location.href = "index.php?Page=Penjualan";
    });

    //Ketika Tambah Transaksi Penjualan
    if ($("#TabelPenjualanBulk").length) {
        var get_kategori_transaksi=$('#get_kategori_transaksi').html();
        ShowDataBulk(get_kategori_transaksi);
    }

    //Modal Cari Barang
    $('#ModalCariBarang').on('show.bs.modal', function (e) {
        var kategori_transaksi=$('#get_kategori_transaksi').html();
        //Reset Halaman
        $('#put_page_cari_barang').val(1);

        //Tampilkan Data
        ShowDataBarang();
    });

    //Proses Cari Barang
    $("#ProsesCariBarang").on("submit", function (e) {
        //Reset Halaman
        $('#put_page_cari_barang').val(1);

        //Tampilkan Data
        ShowDataBarang();
    });

    //Pagging Barang
    $(document).on('click', '#next_button_barang', function() {
        var page_now = parseInt($('#put_page_cari_barang').val(), 10); // Pastikan nilai diambil sebagai angka
        var next_page = page_now + 1;
        $('#put_page_cari_barang').val(next_page);
        ShowDataBarang();
    });
    $(document).on('click', '#prev_button_barang', function() {
        var page_now = parseInt($('#put_page_cari_barang').val(), 10); // Pastikan nilai diambil sebagai angka
        var next_page = page_now - 1;
        $('#put_page_cari_barang').val(next_page);
        ShowDataBarang();
    });

    //Modal Tambah Barang
    $('#ModalTambahBarang').on('show.bs.modal', function (e) {
        var id_barang= $(e.relatedTarget).data('id');
        var kategori_transaksi=$('#get_kategori_transaksi').html();
        //Tampilkan Form
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Penjualan/FormTambahBarang.php',
            data        : {id_barang: id_barang, kategori_transaksi: kategori_transaksi},
            success     : function(data) {
                // Ganti isi tabel dengan data hasil AJAX dengan efek
                $('#FormTambahBarang').fadeOut(200, function() {
                    $(this).html(data).fadeIn(300);
                    
                    //Kosongkan Notifikasi
                    $('#NotifikasiTambahBarang').html("");
                    
                });
            }
        });
    });

    //Proses Tambah Rincian Barang
    $('#ProsesTambahBarang').submit(function(){
        var ProsesTambahBarang = $('#ProsesTambahBarang').serialize();
        $('#NotifikasiTambahBarang').html('Loading...');
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Penjualan/ProsesTambahBarang.php',
            data 	    :  ProsesTambahBarang,
            success     : function(data){
                $('#NotifikasiTambahBarang').html(data);
                var NotifikasiTambahBarangBerhasil=$('#NotifikasiTambahBarangBerhasil').html();
                if(NotifikasiTambahBarangBerhasil=="Success"){
                    //Bersihkan Notifikasi
                    $('#NotifikasiTambahBarang').html("");

                    //tutup modal
                    $('#ModalTambahBarang').modal('hide');

                    //Tampilkan Data
                    var kategori_transaksi=$('#get_kategori_transaksi').html();
                    ShowDataBulk(kategori_transaksi);

                    //Tidak Perlu Menampilkan Swal
                }
            }
        });
    });

    //Modal Scan Barang
    $('#ModalScanBarang').on('shown.bs.modal', function () {
        var kategori_transaksi=$('#get_kategori_transaksi').html();

        //Focus Ke form keyword code
        $('#keyword_code').focus();

        //Tempelkan kategori_transaksi ke form
        $('#put_kategori_transaksi_scan').val(kategori_transaksi);
    });

    // Menampilkan alert success saat input keyword_code mendapatkan fokus
    $('#keyword_code').on('focus', function(){
        $('#preview_hasil_sacan').html(`
            <div class="alert alert-success text-center">
                <i class="bi bi-check-circle"></i> Siap Scan Kode Barang
            </div>
        `);
    });

    //Proses Scan Barang
    $('#ProsesScanCode').submit(function(){
        var ProsesScanCode = $('#ProsesScanCode').serialize();
        $('#NotifikasiScanCode').html('Loading...');
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Penjualan/ProsesScanCode.php',
            data 	    :  ProsesScanCode,
            success     : function(data){
                $('#NotifikasiScanCode').html(data);
                var NotifikasiScanCodeBerhasil=$('#NotifikasiScanCodeBerhasil').html();
                if(NotifikasiScanCodeBerhasil=="Success"){
                    //Tampilkan Data
                    var kategori_transaksi=$('#get_kategori_transaksi').html();
                    ShowDataBulk(kategori_transaksi);


                    //Kosongkan kode
                    $('#keyword_code').val("");

                    //Focus ulang
                    $('#keyword_code').focus();
                }
            }
        });
    });

    //Modal Edit Barang Bulk
    $('#ModalEditBulk').on('show.bs.modal', function (e) {
        var id_transaksi_bulk= $(e.relatedTarget).data('id');
        //Tampilkan Form
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Penjualan/FormEditBulk.php',
            data        : {id_transaksi_bulk: id_transaksi_bulk},
            success     : function(data) {
                // Ganti isi tabel dengan data hasil AJAX dengan efek
                $('#FormEditBulk').fadeOut(200, function() {
                    $(this).html(data).fadeIn(300);
                    
                    //Kosongkan Notifikasi
                    $('#NotifikasiEditBulk').html("");
                    initializeMoneyInputs();
                });
            }
        });
    });

    //Proses Edit Bulk
    $('#ProsesEditBulk').submit(function(){
        var ProsesEditBulk = $('#ProsesEditBulk').serialize();
        $('#NotifikasiEditBulk').html('Loading...');
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Penjualan/ProsesEditBulk.php',
            data 	    :  ProsesEditBulk,
            success     : function(data){
                $('#NotifikasiEditBulk').html(data);
                var NotifikasiEditBulkBerhasil=$('#NotifikasiEditBulkBerhasil').html();
                if(NotifikasiEditBulkBerhasil=="Success"){
                    //Tampilkan Data
                    var kategori_transaksi=$('#get_kategori_transaksi').html();
                    ShowDataBulk(kategori_transaksi);

                    // Tampilkan swal notifikasi
                    Swal.fire(
                        'Success!',
                        'Rincian Transaksi Berhasil Diperbaharui!',
                        'success'
                    );
                    //Tutup Modal
                    $('#ModalEditBulk').modal('hide');
                }
            }
        });
    });

    //Modal Hapus Barang Bulk
    $('#ModalHapusBulk').on('show.bs.modal', function (e) {
        var id_transaksi_bulk= $(e.relatedTarget).data('id');

        //Disable Tombol Pertama Kali
        $('#ButtonHapusBulk').prop("disabled", true);

        //Tampilkan Form
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Penjualan/detail_bulk.php',
            data        : {id_transaksi_bulk: id_transaksi_bulk},
            dataType    : "json",
            success: function (response) {
                //Apabila Proses Berhasil
                if (response.status === "Success") {
                    
                    //Kosongkan Notifikasi
                    $('#NotifikasiHapusBulk').html("");

                    //Tampilkan Form
                    $('#FormHapusBulk').html(`
                        <input type="hidden" name="id_transaksi_bulk" value="${id_transaksi_bulk}">
                        <div class="row mb-2">
                            <div class="col-4"><small>Nama Barang</small></div>
                            <div class="col-8"><small class="text text-grayish">${response.dataset.nama_barang}</small></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>QTY/Satuan</small></div>
                            <div class="col-8"><small class="text text-grayish">${response.dataset.qty} ${response.dataset.satuan}</small></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Harga</small></div>
                            <div class="col-8"><small class="text text-grayish">${response.dataset.harga_rp}</small></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>PPN</small></div>
                            <div class="col-8"><small class="text text-grayish">${response.dataset.ppn_rp}</small></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Diskon</small></div>
                            <div class="col-8"><small class="text text-grayish">${response.dataset.diskon_rp}</small></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Jumlah</small></div>
                            <div class="col-8"><small class="text text-grayish">${response.dataset.subtotal_rp}</small></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">Apakah Anda Yakin Akan Menghapus Data Tersebut</div>
                        </div>
                    `);
                    
                    //Hidupkan Tombol
                    $('#ButtonHapusBulk').prop("disabled", false);
                } else {
                    // Tampilkan pesan error
                    $('#NotifikasiHapusBulk').html(
                        `<div class="alert alert-danger" role="alert">${response.message}</div>`
                    );

                    //Disable Tombol
                    $('#ButtonHapusBulk').prop("disabled", true);
                }
            },
            error: function () {
                $('#NotifikasiHapusBulk').html(
                    '<div class="alert alert-danger" role="alert">Terjadi kesalahan pada sistem. Silakan coba lagi.</div>'
                );
                //Disable Tombol
                $('#ButtonHapusBulk').prop("disabled", true);
            },
        });
    });

    //Proses Hapus Bulk
    $("#ProsesHapusBulk").on("submit", function (e) {
        e.preventDefault();
        // Tombol loading
        let $FormElement = $("#ProsesHapusBulk");
        let $ModalElement = $("#ModalHapusBulk");
        let $Notifikasi = $("#NotifikasiHapusBulk");
        let $ButtonProses = $("#ButtonHapusBulk");
        let ButtonElement = '<i class="bi bi-check"></i> Ya, Hapus';
        $ButtonProses.html('Loading..');
        $ButtonProses.prop("disabled", true);

        // Ambil data form
        let formData = new FormData(this);

        // Kirim data ke server
        $.ajax({
            url         : "_Page/Penjualan/ProsesHapusBulk.php",
            type        : "POST",
            data        : formData,
            contentType : false,
            processData : false,
            dataType    : "json",
            success: function (response) {
                //Apabila Proses Berhasil
                if (response.status === "Success") {

                    //Tampilkan Ulang Data
                    var kategori_transaksi=$('#get_kategori_transaksi').html();
                    ShowDataBulk(kategori_transaksi);
                    
                    // Tampilkan swal notifikasi
                    Swal.fire(
                        'Success!',
                        'Hapus Rincian Barang Berhasil!',
                        'success'
                    )

                    // Reset tombol
                    $ButtonProses.html(ButtonElement);
                    $ButtonProses.prop("disabled", false);

                    //Kosongkan Notifikasi
                    $Notifikasi.html('');

                    //Tutup Modal
                    $ModalElement.modal('hide');
                } else {
                    // Tampilkan pesan error
                    $Notifikasi.html(
                        `<div class="alert alert-danger" role="alert">${response.message}</div>`
                    );
                    $ButtonProses.html(ButtonElement).prop("disabled", false);
                }
            },
            error: function () {
                $Notifikasi.html(
                    '<div class="alert alert-danger" role="alert">Terjadi kesalahan pada sistem. Silakan coba lagi.</div>'
                );
                $ButtonProses.html(ButtonElement).prop("disabled", false);
            },
        });
    });

    //Modal Reset Transaksi
    $('#ModalResetTransaksi').on('show.bs.modal', function (e) {
        //Tangkap Kategori Transaksi
        var kategori_transaksi=$('#get_kategori_transaksi').html();

        //Kosongkan Notifikasi
        $('#NotifikasiResetTransaksi').html();

        //Hidupkan Tombol
        $('#ButtonResetTransaksi').html('<i class="bi bi-check"></i> Reset').prop("disabled", false);

        //tempelkan kategori transaksi
        $('#put_kategori_transaksi_for_reset').val(kategori_transaksi);
    });

    //Proses Reset Transaksi
    $("#ProsesResetTransaksi").on("submit", function (e) {
        e.preventDefault();
        // Tombol loading
        let $FormElement = $("#ProsesResetTransaksi");
        let $ModalElement = $("#ModalResetTransaksi");
        let $Notifikasi = $("#NotifikasiResetTransaksi");
        let $ButtonProses = $("#ButtonResetTransaksi");
        let ButtonElement = '<i class="bi bi-check"></i> Reset';
        $ButtonProses.html('Loading..');
        $ButtonProses.prop("disabled", true);

        // Ambil data form
        let formData = new FormData(this);

        // Kirim data ke server
        $.ajax({
            url         : "_Page/Penjualan/ProsesResetTransaksi.php",
            type        : "POST",
            data        : formData,
            contentType : false,
            processData : false,
            dataType    : "json",
            success: function (response) {
                //Apabila Proses Berhasil
                if (response.status === "Success") {

                    //Tampilkan Ulang Data
                    var kategori_transaksi=$('#get_kategori_transaksi').html();
                    ShowDataBulk(kategori_transaksi);
                    
                    // Tampilkan swal notifikasi
                    Swal.fire(
                        'Success!',
                        'Reset Transaksi Berhasil!',
                        'success'
                    )

                    // Reset tombol
                    $ButtonProses.html(ButtonElement);
                    $ButtonProses.prop("disabled", false);

                    //Kosongkan Notifikasi
                    $Notifikasi.html('');

                    //Tutup Modal
                    $ModalElement.modal('hide');
                } else {
                    // Tampilkan pesan error
                    $Notifikasi.html(
                        `<div class="alert alert-danger" role="alert">${response.message}</div>`
                    );
                    $ButtonProses.html(ButtonElement).prop("disabled", false);
                }
            },
            error: function () {
                $Notifikasi.html(
                    '<div class="alert alert-danger" role="alert">Terjadi kesalahan pada sistem. Silakan coba lagi.</div>'
                );
                $ButtonProses.html(ButtonElement).prop("disabled", false);
            },
        });
    });
});