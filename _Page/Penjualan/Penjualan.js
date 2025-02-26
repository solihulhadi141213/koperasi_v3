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
    $('#TabelPenjualanBulk')
        .html('<tr><td colspan="9" class="text-center">Loading...</td></tr>')
        .fadeIn(200); // Efek memudar saat loading muncul

    $.ajax({
        type: 'POST',
        url: '_Page/Penjualan/TabelPenjualanBulk.php',
        data: {kategori_transaksi: get_kategori_transaksi},
        success: function(data) {
            $('#TabelPenjualanBulk').fadeOut(200, function() { 
                $(this).html(data).fadeIn(300); 
            });
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

//Fungsi Menampilkan Data Anggota
function ShowDataAnggota() {
    var ProsesCariAnggota = $('#ProsesCariAnggota').serialize();
    $.ajax({
        type    : 'POST',
        url     : '_Page/Penjualan/TabelAnggota.php',
        data    : ProsesCariAnggota,
        success: function(data) {
            $('#TabelAnggota').html(data);
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

function HitungRekapTransaksi(total, cash) {
    // Hapus titik pemisah ribuan dan konversi ke angka
    var totalNum = parseInt(total.replace(/\./g, ""), 10);
    var cashNum = parseInt(cash.replace(/\./g, ""), 10);

    // Hitung kembalian
    var kembalian = cashNum - totalNum;
    if (kembalian < 0) {
        kembalian = 0;
    }

    // Tentukan status pembayaran
    var status = (cashNum < totalNum) ? "Kredit" : "Lunas";

    // Format kembali ke dalam format ribuan dengan titik
    var formattedKembalian = kembalian.toLocaleString("id-ID");

    // Tempelkan Data Ke Form
    $('#put_kembalian_penjualan').val(formattedKembalian);
    $('#put_status_transaksi_penjualan').val(status);

    // Panggil fungsi untuk memastikan input format uang tetap benar
    initializeMoneyInputs();
}





$(document).ready(function() {

    //Format Uang Pertama kali
    initializeMoneyInputs();

    //Menampilkan Data Pertama Kali
    if ($("#TabelPenjualan").length) {
        ShowData();
    }

    //Detail Transaksi Inline
    if ($("#get_id_transaksi_jual_beli_detail").length) {
        var id_transaksi_jual_beli=$('#get_id_transaksi_jual_beli_detail').html();
        //Menampilkan detail transaksi inline
        //Buka Detail Barang
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Penjualan/detail_penjualan.php',
            data        : {id_transaksi_jual_beli: id_transaksi_jual_beli},
            dataType    : "json",
            success     : function(response){
                if(response.status=="Success"){

                    var data = response.dataset;
                    var list_rincian = response.list_rincian;
                    
                    //Tempelkan Ke Element
                    $('#form_detail_transaksi_inline').html(`
                        <div class="row mb-2">
                            <div class="col-4"><small>Tanggal</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.tanggal}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Anggota</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.nama_anggota}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Kategori</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.kategori}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Subtotal</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.subtotal_rp}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>PPN</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.ppn_rp}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Diskon</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.diskon_rp}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Total</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.total_rp}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Cash</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.cash_rp}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Kembalian</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.kembalian_rp}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Status</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.status}</small>
                            </div>
                        </div>
                    `);
                    var rincianList = response.list_rincian;
                    var html = "";

                    // Inisialisasi total
                    var totalPpn = 0;
                    var totalDiskon = 0;
                    var totalSubtotal = 0;

                    if (rincianList.length > 0) {
                        $.each(rincianList, function(index, item) {
                            html += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.nama_barang}</td>
                                    <td>${item.qty}</td>
                                    <td class="text-left">${item.harga_rp}</td>
                                    <td class="text-left">${item.ppn_rp}</td>
                                    <td class="text-left">${item.diskon_rp}</td>
                                    <td class="text-left">${item.subtotal_rp}</td>
                                </tr>
                            `;

                            // Hitung total
                            totalPpn += parseFloat(item.ppn);
                            totalDiskon += parseFloat(item.diskon);
                            totalSubtotal += parseFloat(item.subtotal);
                        });

                        // Tambahkan baris total di akhir tabel
                        html += `
                            <tr class="fw-bold bg-light">
                                <td colspan="4" class="text-left">Total</td>
                                <td class="text-left">Rp ${totalPpn.toLocaleString("id-ID")}</td>
                                <td class="text-left">Rp ${totalDiskon.toLocaleString("id-ID")}</td>
                                <td class="text-left">Rp ${totalSubtotal.toLocaleString("id-ID")}</td>
                            </tr>
                        `;
                    } else {
                        html = '<tr><td colspan="7" class="text-center">Tidak ada rincian transaksi</td></tr>';
                    }

                    // Masukkan ke dalam tabel
                    $("#ListDetailTransaksiInline").html(html);

                }else{
                    //Tempelkan Notifikasi
                    $('#FormDetail').html(
                        `<div class="alert alert-danger" role="alert">${response.message}</div>`
                    );
                    //Disable tombol
                    $('#ButtonSelengkapnya').prop("disabled", true);
                }
            },
            error: function () {
                //Tempelkan Notifikasi
                $('#FormDetail').html(
                    '<div class="alert alert-danger" role="alert">Terjadi kesalahan pada sistem. Silakan coba lagi.</div>'
                );
                //Disable tombol
                $('#ButtonSelengkapnya').prop("disabled", true);
            },
        });
    }

    //Ketika keyword By Diubah
    $('#keyword_by').change(function(){
        var keyword_by = $('#keyword_by').val();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Penjualan/FormFilterKeyword.php',
            data 	    :  {keyword_by: keyword_by},
            success     : function(data){
                $('#FormFilterKeyword').html(data);
            }
        });
    });

    //Submit pencarian/Filter
    $("#ProsesFilter").on("submit", function (e) {
        //Reset Halaman
        $('#page').val(1);
        
        //Tampilkan Data
        ShowData();

        //Tutup Modal
        $('#ModalFilter').modal('hide');
    });

    //PAGGING TRANSAKSI
    $(document).on('click', '#next_button', function() {
        var page_now = parseInt($('#page').val(), 10); // Pastikan nilai diambil sebagai angka
        var next_page = page_now + 1;
        $('#page').val(next_page);
        ShowData();
    });
    $(document).on('click', '#prev_button', function() {
        var page_now = parseInt($('#page').val(), 10); // Pastikan nilai diambil sebagai angka
        var next_page = page_now - 1;
        $('#page').val(next_page);
        ShowData();
    });

    //Modal Export
    $('#ModalExportTransaksi').on('show.bs.modal', function (e) {
        $('#NotifikasiExportTransaksi').html('<div class="alert alert-warning"><small>Semakin banyak data transaksi yang akan diexport, maka proses sistem membutuhkan waktu lebih lama.</small></div>');
    });
    
    //Proses Export Transaksi
    $("#ProsesExportTransaksi").on("submit", function (e) {
        e.preventDefault(); // Mencegah form submit default
    
        var mode_data = $('#mode_data').val();
    
        // Loading Tombol
        $('#ButtonExportTransaksi').html('Loading...').prop('disabled', true);
    
        // Tentukan URL berdasarkan mode_data
        var url = (mode_data === "Transaksi") 
            ? "_Page/Penjualan/ProsesExportTransaksi.php"
            : "_Page/Penjualan/ProsesExportRincian.php";
    
        // Buka halaman di tab baru
        window.open(url, "_blank");
    
        // Kembalikan Tombol setelah beberapa detik agar user melihat perubahan
        setTimeout(function () {
            $('#ButtonExportTransaksi').html('<i class="bi bi-download"></i> Export').prop('disabled', false);
            $('#NotifikasiExportTransaksi').html('<div class="alert alert-success">Proses Export Berhasil.</div>');
        }, 2000); // Delay 2 detik
    });

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

    //Modal Tampilkan Anggota
    $('#ModalPilihAnggota').on('show.bs.modal', function (e) {
        ShowDataAnggota();
    });

    //Pagging Anggota
    $(document).on('click', '#next_button_anggota', function() {
        var page_now = parseInt($('#page').val(), 10); // Pastikan nilai diambil sebagai angka
        var next_page = page_now + 1;
        $('#page_anggota').val(next_page);
        ShowDataAnggota(0);
    });
    $(document).on('click', '#prev_button_anggota', function() {
        var page_now = parseInt($('#page_anggota').val(), 10); // Pastikan nilai diambil sebagai angka
        var next_page = page_now - 1;
        $('#page_anggota').val(next_page);
        ShowDataAnggota(0);
    });

    //Ketika Submit Filter Anggota
    $('#ProsesCariAnggota').submit(function(){
        //Kembalikan ke halaman 1
        $('#page_anggota').val(1);
        ShowDataAnggota();
    });

    //Ketika Salah Satu Data Anggota Di click
    $(document).on("click", ".pilih_anggota_ke_form_penjualan", function () {
        var idAnggota = $(this).data("id");
        var namaAnggota = $(this).data("nama");
    
        console.log("ID Anggota:", idAnggota);
        console.log("Nama Anggota:", namaAnggota);

        // Set nilai select option
        $("#put_id_anggota_for_add_penjualan").html(`<option value="${idAnggota}">${namaAnggota}</option>`);

        // Tutup modal (jika ada)
        $("#ModalPilihAnggota").modal("hide");
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

    //Ketika Nominal Cash Di ketik
    $("#put_cash_penjualan").on("keyup", function () {
        var put_cash_penjualan=$("#put_cash_penjualan").val();
        var put_total_penjualan=$("#put_total_penjualan").val();
        HitungRekapTransaksi(put_total_penjualan, put_cash_penjualan);
    });

    //Proses Simpan Transaksi Penjualan
    $("#ProsesSimpanTransaksiPenjualan").on("submit", function (e) {
        e.preventDefault();
        // Tombol loading
        $("#ButtonSimpanTransaksiPenjualan").html('Loading..');
        $("#ButtonSimpanTransaksiPenjualan").prop("disabled", true);
        let ButtonElement = '<i class="bi bi-save"></i> Simpan Transaksi';
        // Ambil data form
        let formData = new FormData(this);

        // Kirim data ke server
        $.ajax({
            url         : "_Page/Penjualan/ProsesSimpanTransaksiPenjualan.php",
            type        : "POST",
            data        : formData,
            contentType : false,
            processData : false,
            dataType    : "json",
            success: function (response) {
                //Apabila Proses Berhasil
                if (response.status === "Success") {
                    var id_transaksi_jual_beli=response.id_transaksi_jual_beli;
                    window.location.href = 'index.php?Page=Penjualan&Sub=DetailPenjualan&id='+id_transaksi_jual_beli+'';
                } else {
                    // Tampilkan pesan error
                    $("#NotifikasiSimpanTransaksiPenjualan").html(
                        `<div class="alert alert-danger" role="alert">${response.message}</div>`
                    );
                    $("#ButtonSimpanTransaksiPenjualan").html(ButtonElement).prop("disabled", false);
                }
            },
            error: function () {
                $("#NotifikasiSimpanTransaksiPenjualan").html(
                    '<div class="alert alert-danger" role="alert">Terjadi kesalahan pada sistem. Silakan coba lagi.</div>'
                );
                $("#ButtonSimpanTransaksiPenjualan").html(ButtonElement).prop("disabled", false);
            },
        });
    });

    //Modal Detail
    $('#ModalDetail').on('show.bs.modal', function (e) {
        //Tangkap id_transaksi_jual_beli dari modal detail
        var id_transaksi_jual_beli = $(e.relatedTarget).data('id');
        
        //Buka Detail Barang
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Penjualan/detail_penjualan.php',
            data        : {id_transaksi_jual_beli: id_transaksi_jual_beli},
            dataType    : "json",
            success     : function(response){
                if(response.status=="Success"){

                    var data = response.dataset;
                    var list_rincian = response.list_rincian;
                    
                    //Tempelkan Ke Element
                    $('#FormDetail').html(`
                        <input type="hidden" name="id" value="${id_transaksi_jual_beli}">
                        <div class="row mb-2">
                            <div class="col-4"><small>Tanggal</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.tanggal}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Anggota</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.nama_anggota}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Kategori</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.kategori}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Subtotal</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.subtotal_rp}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>PPN</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.ppn_rp}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Diskon</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.diskon_rp}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Total</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.total_rp}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Cash</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.cash_rp}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Kembalian</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.kembalian_rp}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Status</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.status}</small>
                            </div>
                        </div>
                    `);
                    var rincianList = response.list_rincian;
                    var html = "";

                    // Inisialisasi total
                    var totalPpn = 0;
                    var totalDiskon = 0;
                    var totalSubtotal = 0;

                    if (rincianList.length > 0) {
                        $.each(rincianList, function(index, item) {
                            html += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.nama_barang}</td>
                                    <td>${item.qty}</td>
                                    <td class="text-end">${item.harga_rp}</td>
                                    <td class="text-end">${item.ppn_rp}</td>
                                    <td class="text-end">${item.diskon_rp}</td>
                                    <td class="text-end">${item.subtotal_rp}</td>
                                </tr>
                            `;

                            // Hitung total
                            totalPpn += parseFloat(item.ppn);
                            totalDiskon += parseFloat(item.diskon);
                            totalSubtotal += parseFloat(item.subtotal);
                        });

                        // Tambahkan baris total di akhir tabel
                        html += `
                            <tr class="fw-bold bg-light">
                                <td colspan="4" class="text-center">Total</td>
                                <td class="text-end">Rp ${totalPpn.toLocaleString("id-ID")}</td>
                                <td class="text-end">Rp ${totalDiskon.toLocaleString("id-ID")}</td>
                                <td class="text-end">Rp ${totalSubtotal.toLocaleString("id-ID")}</td>
                            </tr>
                        `;
                    } else {
                        html = '<tr><td colspan="7" class="text-center">Tidak ada rincian transaksi</td></tr>';
                    }

                    // Masukkan ke dalam tabel
                    $("#ListDetail").html(html);

                    //Enable tombol
                    $('#ButtonSelengkapnya').prop("disabled", false);
                }else{
                    //Tempelkan Notifikasi
                    $('#FormDetail').html(
                        `<div class="alert alert-danger" role="alert">${response.message}</div>`
                    );
                    //Disable tombol
                    $('#ButtonSelengkapnya').prop("disabled", true);
                }
            },
            error: function () {
                //Tempelkan Notifikasi
                $('#FormDetail').html(
                    '<div class="alert alert-danger" role="alert">Terjadi kesalahan pada sistem. Silakan coba lagi.</div>'
                );
                //Disable tombol
                $('#ButtonSelengkapnya').prop("disabled", true);
            },
        });
    });

    //Modal Hapus Transaksi
    $('#ModalHapus').on('show.bs.modal', function (e) {
        var id_transaksi_jual_beli= $(e.relatedTarget).data('id');
        $('#ButtonHapus').html('<i class="bi bi-check"></i> Ya, Hapus');
        $('#ButtonHapus').prop("disabled", true);
        $('#FormHapus').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Penjualan/detail_penjualan.php',
            data        : {id_transaksi_jual_beli: id_transaksi_jual_beli},
            dataType    : "json",
            success     : function(response){
                if(response.status=="Success"){
                    var data = response.dataset;
                    //Tempelkan Form Dan Kosongkan Notifikasi
                    $('#FormHapus').html(`
                        <input type="hidden" name="id_transaksi_jual_beli" value="${id_transaksi_jual_beli}">
                        <div class="row mb-2">
                            <div class="col-4"><small>Tanggal</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.tanggal}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Anggota</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.nama_anggota}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Kategori</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.kategori}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Subtotal</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.subtotal_rp}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>PPN</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.ppn_rp}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Diskon</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.diskon_rp}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Total</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.total_rp}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Cash</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.cash_rp}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Kembalian</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.kembalian_rp}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><small>Status</small></div>
                            <div class="col-8">
                                <small class="text text-grayish">${data.status}</small>
                            </div>
                        </div>
                    `);
                    $('#NotifikasiHapus').html('Apakah Anda Yakin Akan Menghapus Data Tersebut?');
                    //Disable tombol
                    $('#ButtonHapus').prop("disabled", false);
                }else{
                    //Tempelkan Form Dan Kosongkan Notifikasi
                    $('#FormHapus').html(
                        `<div class="alert alert-danger" role="alert">${response.message}</div>`
                    );
                    $('#NotifikasiHapus').html('');
                    //Disable tombol
                    $('#ButtonHapus').prop("disabled", true);
                }
            },
            error: function () {
                //Tempelkan Form Dan Kosongkan Notifikasi
                $('#FormHapus').html(
                    '<div class="alert alert-danger" role="alert">Terjadi kesalahan pada sistem. Silakan coba lagi.</div>'
                );
                $('#NotifikasiHapus').html('');
                //Disable tombol
                $('#ButtonHapus').prop("disabled", true);
            },
        });
    });

    //Proses Hapus Transaksi
    $("#ProsesHapus").on("submit", function (e) {
        e.preventDefault();
        // Tombol loading
        $("#ButtonHapus").html('Loading..');
        $("#ButtonHapus").prop("disabled", true);
        let ButtonElement = '<i class="bi bi-check"></i> Ya, Hapus';
        // Ambil data form
        let formData = new FormData(this);

        // Kirim data ke server
        $.ajax({
            url         : "_Page/Penjualan/ProsesHapus.php",
            type        : "POST",
            data        : formData,
            contentType : false,
            processData : false,
            dataType    : "json",
            success: function (response) {
                //Apabila Proses Berhasil
                if (response.status === "Success") {
                    $("#ButtonHapus").html(ButtonElement).prop("disabled", false);
                    $('#NotifikasiHapus').html('');
                    
                    //Tutup Modal
                    $('#ModalHapus').modal('hide');
                    
                    //Reload Data
                    ShowData();
                    
                    //Tampilkan Alert
                    Swal.fire(
                        'Success!',
                        'Hapus Transaksi Berhasil!',
                        'success'
                    )
                } else {
                    // Tampilkan pesan error
                    $("#NotifikasiHapus").html(
                        `<div class="alert alert-danger" role="alert">${response.message}</div>`
                    );
                    $("#ButtonHapus").html(ButtonElement).prop("disabled", true);
                }
            },
            error: function () {
                $("#NotifikasiHapus").html(
                    '<div class="alert alert-danger" role="alert">Terjadi kesalahan pada sistem. Silakan coba lagi.</div>'
                );
                $("#ButtonHapus").html(ButtonElement).prop("disabled", true);
            },
        });
    });

    //Modal Cetak Transaksi
    $('#ModalCetak').on('show.bs.modal', function (e) {
        var id_transaksi_jual_beli= $(e.relatedTarget).data('id');
        $('#FormDetailCetak').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Penjualan/PreviewCetak.php',
            data        : {id_transaksi_jual_beli: id_transaksi_jual_beli},
            success     : function(data){
                $('#FormDetailCetak').html(data);
            }
        });
    });

    $('#ProsesCetak').submit(function(event) {
        event.preventDefault(); // Mencegah reload halaman
        
        var formatCetak = $('input[name="tipe_cetak"]:checked').val(); // Ambil format yang dipilih
        var content = document.getElementById("FormDetailCetak"); // Ambil elemen yang ingin dicetak

        if (!formatCetak) {
            $('#NotifikasiCetak').html('<div class="alert alert-danger">Silakan pilih format cetak!</div>');
            return;
        }

        if (formatCetak === "PDF") {
            html2canvas(content, { scale: 2 }).then(canvas => {
                var imgData = canvas.toDataURL("image/png");
                var { jsPDF } = window.jspdf;
                var doc = new jsPDF("p", "mm", "a4");
                var imgWidth = 190; // Lebar gambar dalam PDF
                var imgHeight = (canvas.height * imgWidth) / canvas.width;

                doc.addImage(imgData, "PNG", 10, 10, imgWidth, imgHeight);
                doc.save("Nota_Transaksi.pdf"); // Simpan sebagai PDF
            });
        } else if (formatCetak === "Image") {
            html2canvas(content, { scale: 2 }).then(canvas => {
                var imgData = canvas.toDataURL("image/png");
                var link = document.createElement("a");
                link.href = imgData;
                link.download = "Nota_Transaksi.png";
                link.click();
            });
        } else if (formatCetak === "Direct") {
            var printWindow = window.open("", "", "width=800,height=600");
            printWindow.document.write('<html><head><title>Cetak Nota</title>');
            printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
            printWindow.document.write('</head><body>');
            printWindow.document.write(content.innerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        }
    });
});