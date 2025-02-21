//Fungsi Menampilkan Data Akses
function ShowData() {
    var ProsesFilter = $('#ProsesFilter').serialize();
    $('#TabelBarang').html('<tr><td class="text-center">Loading...</td></tr>');
    $.ajax({
        type    : 'POST',
        url     : '_Page/Barang/TabelBarang.php',
        data    : ProsesFilter,
        success: function(data) {
            $('#TabelBarang').html(data);
        }
    });
}
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

$(document).ready(function() {
    //Menampilkan Data Pertama Kali
    ShowData();

    //Ketika Batas Diubah
    $('#batas').change(function(){
        ShowData();
    });
    
    //Ketika keyword By Diubah
    $('#keyword_by').change(function(){
        var keyword_by = $('#keyword_by').val();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Barang/FormFilterKeyword.php',
            data 	    :  {keyword_by: keyword_by},
            success     : function(data){
                $('#FormFilterKeyword').html(data);
            }
        });
    });

    //Ketika Submit Filter
    $('#ProsesFilter').submit(function(){
        //Kembalikan ke halaman 1
        $('#page').val(1);
        ShowData();
        //Tutup Modal
        $('#ModalFilter').modal('hide');
    });

    //Pagging
    $(document).on('click', '#next_button', function() {
        var page_now = parseInt($('#page').val(), 10); // Pastikan nilai diambil sebagai angka
        var next_page = page_now + 1;
        $('#page').val(next_page);
        ShowData(0);
    });
    $(document).on('click', '#prev_button', function() {
        var page_now = parseInt($('#page').val(), 10); // Pastikan nilai diambil sebagai angka
        var next_page = page_now - 1;
        $('#page').val(next_page);
        ShowData(0);
    });

    //Modal Kategori Harga
    $('#ModalKategoriHarga').on('show.bs.modal', function (e) {
        $('#TabelKategoriHarga').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Barang/TabelKategoriHarga.php',
            success     : function(data){
                $('#TabelKategoriHarga').html(data);
            }
        });
    });

    //Proses Tambah Kategori Harga
    $("#ProsesTambahKategoriHarga").on("submit", function (e) {
        e.preventDefault();
        // Tombol loading
        let $FormElement = $("#ProsesTambahKategoriHarga");
        let $ModalElement = $("#ModalTambahKategoriHarga");
        let $Notifikasi = $("#NotifikasiTambahKategoriHarga");
        let $ButtonProses = $("#ButtonTambahKategoriHarga");
        let ButtonElement = '<i class="bi bi-save"></i> Simpan';
        $ButtonProses.html('Loading..');
        $ButtonProses.prop("disabled", true);
    
        // Ambil data form
        let formData = new FormData(this);
    
        // Kirim data ke server
        $.ajax({
            url         : "_Page/Barang/ProsesTambahKategoriHarga.php",
            type        : "POST",
            data        : formData,
            contentType : false,
            processData : false,
            dataType    : "json",
            success: function (response) {
                //Apabila Proses Berhasil
                if (response.status === "Success") {
                    
                    //reset form
                    $FormElement[0].reset();
    
                    //Tampilkan Ulang Data
                    $('#ModalKategoriHarga').modal('show');
                    $('#TabelKategoriHarga').html("Loading...");
                    $.ajax({
                        type 	    : 'POST',
                        url 	    : '_Page/Barang/TabelKategoriHarga.php',
                        success     : function(data){
                            $('#TabelKategoriHarga').html(data);
                        }
                    });
                    
                    // Tampilkan swal notifikasi
                    Swal.fire(
                        'Success!',
                        'Tambah Kategori Harga Berhasil!',
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

    //Modal Edit Kategori Harga
    $('#ModalEditKategoriHarga').on('show.bs.modal', function (e) {
        var id_barang_kategori_harga= $(e.relatedTarget).data('id');
        $('#ButtonEditKategoriHarga').prop("disabled", true);
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Barang/detail_kategori_harga.php',
            data        : {id_barang_kategori_harga: id_barang_kategori_harga},
            dataType    : "json",
            success     : function(response){
                if (response.status === "Success") {
                    $('#FormEditKategoriHarga').show();
                    var kategori_harga=response.dataset.kategori_harga;
                    var keterangan=response.dataset.keterangan;
                    
                    //Tempelkan Ke Form
                    $('#put_id_barang_kategori_harga_edit').val(id_barang_kategori_harga);
                    $('#nama_kategori_harga_edit').val(kategori_harga);
                    $('#keterangan_kategori_harga_edit').val(keterangan);
                    //Aktivkan Tombol
                    $('#ButtonEditKategoriHarga').prop("disabled", false);

                    //Kosongkan Notifikasi
                    $('#NotifikasiEditKategoriHarga').html("");
                }else{
                    $('#ButtonEditKategoriHarga').prop("disabled", true);
                    $('#NotifikasiEditKategoriHarga').html(
                        `<div class="alert alert-danger" role="alert">${response.message}</div>`
                    );
                    $('#FormEditKategoriHarga').hide();
                }
            },
            error: function () {
                $('#NotifikasiEditKategoriHarga').html(
                    '<div class="alert alert-danger" role="alert">Terjadi kesalahan pada sistem. Silakan coba lagi.</div>'
                );
                $('#ButtonEditKategoriHarga').prop("disabled", true);
                $('#FormEditKategoriHarga').hide();
            },
        });
    });

    //Proses Edit Kategori Harga
    $("#ProsesEditKategoriHarga").on("submit", function (e) {
        e.preventDefault();
        // Tombol loading
        let $FormElement = $("#ProsesEditKategoriHarga");
        let $ModalElement = $("#ModalEditKategoriHarga");
        let $Notifikasi = $("#NotifikasiEditKategoriHarga");
        let $ButtonProses = $("#ButtonEditKategoriHarga");
        let ButtonElement = '<i class="bi bi-save"></i> Simpan';
        $ButtonProses.html('Loading..');
        $ButtonProses.prop("disabled", true);
    
        // Ambil data form
        let formData = new FormData(this);
    
        // Kirim data ke server
        $.ajax({
            url         : "_Page/Barang/ProsesEditKategoriHarga.php",
            type        : "POST",
            data        : formData,
            contentType : false,
            processData : false,
            dataType    : "json",
            success: function (response) {
                //Apabila Proses Berhasil
                if (response.status === "Success") {
                    
                    //reset form
                    $FormElement[0].reset();
    
                    //Tampilkan Ulang Data
                    $('#ModalKategoriHarga').modal('show');
                    $('#TabelKategoriHarga').html("Loading...");
                    $.ajax({
                        type 	    : 'POST',
                        url 	    : '_Page/Barang/TabelKategoriHarga.php',
                        success     : function(data){
                            $('#TabelKategoriHarga').html(data);
                        }
                    });
                    
                    // Tampilkan swal notifikasi
                    Swal.fire(
                        'Success!',
                        'Edit Kategori Harga Berhasil!',
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
    //Modal Hapus Kategori Harga
    $('#ModalHapusKategoriHarga').on('show.bs.modal', function (e) {
        var id_barang_kategori_harga= $(e.relatedTarget).data('id');
        $('#ButtonHapusKategoriHarga').prop("disabled", true);
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Barang/detail_kategori_harga.php',
            data        : {id_barang_kategori_harga: id_barang_kategori_harga},
            dataType    : "json",
            success     : function(response){
                if (response.status === "Success") {
                    $('#FormHapusKategoriHarga').show();
                    var kategori_harga=response.dataset.kategori_harga;
                    var keterangan=response.dataset.keterangan;
                    var jumlah_item=response.dataset.jumlah_item;
                    
                    //Tempelkan Ke Form
                    $('#put_id_barang_kategori_harga_hapus').val(id_barang_kategori_harga);
                    $('#put_kategori_harga').html(kategori_harga);
                    $('#put_keterangan').html(keterangan);
                    $('#put_jumlah_item').html(jumlah_item);
                    //Aktivkan Tombol
                    $('#ButtonHapusKategoriHarga').prop("disabled", false);

                    //Kosongkan Notifikasi
                    $('#NotifikasiHapusKategoriHarga').html("");
                }else{
                    $('#ButtonHapusKategoriHarga').prop("disabled", true);
                    $('#NotifikasiHapusKategoriHarga').html(
                        `<div class="alert alert-danger" role="alert">${response.message}</div>`
                    );
                    $('#FormHapusKategoriHarga').hide();
                }
            },
            error: function () {
                $('#NotifikasiHapusKategoriHarga').html(
                    '<div class="alert alert-danger" role="alert">Terjadi kesalahan pada sistem. Silakan coba lagi.</div>'
                );
                $('#ButtonHapusKategoriHarga').prop("disabled", true);
                $('#FormHapusKategoriHarga').hide();
            },
        });
    });

    //Proses Hapus Kategori Harga
    $("#ProsesHapusKategoriHarga").on("submit", function (e) {
        e.preventDefault();
        // Tombol loading
        let $FormElement = $("#ProsesHapusKategoriHarga");
        let $ModalElement = $("#ModalHapusKategoriHarga");
        let $Notifikasi = $("#NotifikasiHapusKategoriHarga");
        let $ButtonProses = $("#ButtonHapusKategoriHarga");
        let ButtonElement = '<i class="bi bi-check"></i> Ya, Hapus';
        $ButtonProses.html('Loading..');
        $ButtonProses.prop("disabled", true);

        // Ambil data form
        let formData = new FormData(this);

        // Kirim data ke server
        $.ajax({
            url         : "_Page/Barang/ProsesHapusKategoriHarga.php",
            type        : "POST",
            data        : formData,
            contentType : false,
            processData : false,
            dataType    : "json",
            success: function (response) {
                //Apabila Proses Berhasil
                if (response.status === "Success") {
                    
                    //reset form
                    $FormElement[0].reset();

                    //Tampilkan Ulang Data
                    $('#ModalKategoriHarga').modal('show');
                    $('#TabelKategoriHarga').html("Loading...");
                    $.ajax({
                        type 	    : 'POST',
                        url 	    : '_Page/Barang/TabelKategoriHarga.php',
                        success     : function(data){
                            $('#TabelKategoriHarga').html(data);
                        }
                    });
                    
                    // Tampilkan swal notifikasi
                    Swal.fire(
                        'Success!',
                        'Hapus Kategori Harga Berhasil!',
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

    // Metika Modal Tambah Barang
    $('#ModalTambahBarang').on('show.bs.modal', function (e) {
        // Menampilkan list_kategori
        $.ajax({
            type    : 'POST',
            url     : '_Page/Barang/list_kategori.php',
            success: function(data) {
                $('#list_kategori').html(data);
            }
        });

        // Menampilkan list_satuan
        $.ajax({
            type    : 'POST',
            url     : '_Page/Barang/list_satuan.php',
            success: function(data) {
                $('#list_satuan').html(data);
            }
        });

        // Menampilkan form multi harga
        $.ajax({
            type    : 'POST',
            url     : '_Page/Barang/form_multi_harga.php',
            success: function(data) {
                $('#form_multi_harga').html(data);

                // Setelah form multi harga dimuat, inisialisasi input form-money
                initializeMoneyInputs();  // Memastikan elemen baru diformat
            }
        });

        // Inisialisasi elemen form-money untuk input yang sudah ada
        initializeMoneyInputs();
    });

    //Proses Tambah Barang
    $("#ProsesTambahBarang").on("submit", function (e) {
        e.preventDefault();
        // Tombol loading
        let $FormElement = $("#ProsesTambahBarang");
        let $ModalElement = $("#ModalTambahBarang");
        let $Notifikasi = $("#NotifikasiTambahBarang");
        let $ButtonProses = $("#ButtonTambahBarang");
        let ButtonElement = '<i class="bi bi-save"></i> Simpan';
        $ButtonProses.html('Loading..');
        $ButtonProses.prop("disabled", true);

        // Ambil data form
        let formData = new FormData(this);

        // Kirim data ke server
        $.ajax({
            url         : "_Page/Barang/ProsesTambahBarang.php",
            type        : "POST",
            data        : formData,
            contentType : false,
            processData : false,
            dataType    : "json",
            success: function (response) {
                //Apabila Proses Berhasil
                if (response.status === "Success") {
                    
                    //reset form
                    $FormElement[0].reset();

                    //Tampilkan Ulang Data
                    $('#ProsesFilter')[0].reset();
                    ShowData();
                    
                    // Tampilkan swal notifikasi
                    Swal.fire(
                        'Success!',
                        'Tambah Barang Berhasil!',
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

    //Modal Detail Barang
    $('#ModalDetailBarang').on('show.bs.modal', function (e) {
        var id_barang= $(e.relatedTarget).data('id');
        $('#FormDetailBarang').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Barang/detail_barang.php',
            data        : {id_barang: id_barang},
            dataType    : "json",
            success     : function(response){
                if(response.status=="Success"){
                    var data = response.dataset;
                    var multi_harga = response.multi_harga || [];

                    // Tempelkan Data ke Elemen yang Sesuai
                    $('.kode_barang').html(`<code class="text-grayish">${data.kode_barang}</code>`);
                    $('.nama_barang').html(`<code class="text-grayish">${data.nama_barang}</code>`);
                    $('.kategori_barang').html(`<code class="text-grayish">${data.kategori_barang}</code>`);
                    $('.satuan_barang').html(`<code class="text-grayish">${data.konversi} / ${data.satuan_barang}</code>`);
                    $('.stok_barang').html(`<code class="text-grayish">${parseFloat(data.stok_barang).toLocaleString('id-ID')} ${data.satuan_barang}</code>`);
                    $('.stok_minimum').html(`<code class="text-grayish">${parseFloat(data.stok_minimum).toLocaleString('id-ID')} ${data.satuan_barang} </code>`);
                    $('.harga_beli').html(`<code class="text-grayish">${data.harga_beli_format}</code>`);

                    // Pastikan informasi_multi_harga kosong sebelum menambahkan data baru
                    $("#informasi_multi_harga").empty();

                    // Looping multi_harga dan tambahkan ke tampilan
                    $.each(multi_harga, function (index, item) {
                        var hargaFormatted = item.harga_format.replace(",", "."); // Sesuaikan pemisah desimal
                        var row = `<div class="row mb-2">
                                    <div class="col-4"><small>${item.kategori_harga}</small></div>
                                    <div class="col-8"><small><code class="text-grayish">Rp ${hargaFormatted}</code></small></div>
                                </div>`;
                        $("#informasi_multi_harga").append(row);
                    });
                }else{
                    $('#form_detail_barang').html(
                        `<div class="alert alert-danger" role="alert">${response.message}</div>`
                    );
                }
            },
            error: function () {
                $('#form_detail_barang').html(
                    '<div class="alert alert-danger" role="alert">Terjadi kesalahan pada sistem. Silakan coba lagi.</div>'
                );
            },
        });
    });

});







//Detail Barang
$('#ModalDetailBarang').on('show.bs.modal', function (e) {
    var id_barang= $(e.relatedTarget).data('id');
    $('#FormDetailBarang').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Barang/FormDetailBarang.php',
        data        : {id_barang: id_barang},
        success     : function(data){
            $('#FormDetailBarang').html(data);
        }
    });
});
//Edit Barang
$('#ModalEditBarang').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    var pecah = GetData.split(",");
    var id_barang = pecah[0];
    var keyword = pecah[1];
    var batas = pecah[2];
    var ShortBy = pecah[3];
    var OrderBy = pecah[4];
    var page = pecah[5];
    var posisi = pecah[6];
    var keyword_by = pecah[7];
    $('#FormEditBarang').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Barang/FormEditBarang.php',
        data        : {id_barang: id_barang},
        success     : function(data){
            $('#FormEditBarang').html(data);
            //menambahkan field
            $('#ButtonTambahKategoriHargaEdit').click(function(){
                var form = $('#ProsesEditBarang')[0];
                var data = new FormData(form);
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Barang/ListKategoriHarga.php',
                    data 	    :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#ListKategoriHargaEdit').html(data);
                    }
                });
            });
            $('#HapusKategoriHargaEdit').click(function(){
                var form = $('#ProsesEditBarang')[0];
                var data = new FormData(form);
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Barang/ListKategoriHargaHapus.php',
                    data 	    :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#ListKategoriHargaEdit').html(data);
                    }
                });
            });
            //Proses Edit Barang
            $('#ProsesEditBarang').submit(function(){
                $('#NotifikasiEditBarang').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                var form = $('#ProsesEditBarang')[0];
                var data = new FormData(form);
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Barang/ProsesEditBarang.php',
                    data 	    :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#NotifikasiEditBarang').html(data);
                        var NotifikasiEditBarangBerhasil=$('#NotifikasiEditBarangBerhasil').html();
                        if(NotifikasiEditBarangBerhasil=="Success"){
                            $('#ModalEditBarang').modal('toggle');
                            $('#MenampilkanTabelBarang').html('Loading...');
                            $.ajax({
                                type 	    : 'POST',
                                url 	    : '_Page/Barang/TabelBarang.php',
                                data 	    :  {keyword: keyword, batas: batas, ShortBy: ShortBy, OrderBy: OrderBy, page: page, posisi: posisi, keyword_by: keyword_by},
                                success     : function(data){
                                    $('#MenampilkanTabelBarang').html(data);
                                    swal("Good Job!", "Edit Barang Berhasil!", "success");
                                }
                            });
                        }
                    }
                });
            });
        }
    });
});
//Edit Barang
$('#ModalEditBarang2').on('show.bs.modal', function (e) {
    var id_barang = $(e.relatedTarget).data('id');
    $('#FormEditBarang2').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Barang/FormEditBarang.php',
        data        : {id_barang: id_barang},
        success     : function(data){
            $('#FormEditBarang2').html(data);
            //menambahkan field
            $('#ButtonTambahKategoriHargaEdit').click(function(){
                var form = $('#ProsesEditBarang2')[0];
                var data = new FormData(form);
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Barang/ListKategoriHarga.php',
                    data 	    :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#ListKategoriHargaEdit').html(data);
                    }
                });
            });
            $('#HapusKategoriHargaEdit').click(function(){
                var form = $('#ProsesEditBarang2')[0];
                var data = new FormData(form);
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Barang/ListKategoriHargaHapus.php',
                    data 	    :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#ListKategoriHargaEdit').html(data);
                    }
                });
            });
            //Proses Edit Barang
            $('#ProsesEditBarang2').submit(function(){
                $('#NotifikasiEditBarang').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                var form = $('#ProsesEditBarang2')[0];
                var data = new FormData(form);
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Barang/ProsesEditBarang.php',
                    data 	    :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#NotifikasiEditBarang').html(data);
                        var NotifikasiEditBarangBerhasil=$('#NotifikasiEditBarangBerhasil').html();
                        if(NotifikasiEditBarangBerhasil=="Success"){
                            location.reload();
                        }
                    }
                });
            });
        }
    });
});
//Hapus Barang
$('#ModalDeleteBarang').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    var pecah = GetData.split(",");
    var id_barang = pecah[0];
    var keyword = pecah[1];
    var batas = pecah[2];
    var ShortBy = pecah[3];
    var OrderBy = pecah[4];
    var page = pecah[5];
    var posisi = pecah[6];
    var keyword_by = pecah[7];
    $('#FormDeleteBarang').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Barang/FormDeleteBarang.php',
        data        : {id_barang: id_barang},
        success     : function(data){
            $('#FormDeleteBarang').html(data);
            //Konfirmasi Hapus Barang
            $('#KonfirmasiHapusBarang').click(function(){
                $('#NotifikasiHapusBarang').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Barang/ProsesHapusBarang.php',
                    data        : {id_barang: id_barang},
                    success     : function(data){
                        $('#NotifikasiHapusBarang').html(data);
                        var NotifikasiHapusBarangBerhasil=$('#NotifikasiHapusBarangBerhasil').html();
                        if(NotifikasiHapusBarangBerhasil=="Success"){
                            $.ajax({
                                type 	    : 'POST',
                                url 	    : '_Page/Barang/TabelBarang.php',
                                data 	    :  {keyword: keyword, batas: batas, ShortBy: ShortBy, OrderBy: OrderBy, page: page, posisi: posisi, keyword_by: keyword_by},
                                success     : function(data){
                                    $('#MenampilkanTabelBarang').html(data);
                                    $('#ModalDeleteBarang').modal('hide');
                                    swal("Good Job!", "Hapus Barang Berhasil!", "success");
                                }
                            });
                        }
                    }
                });
            });
        }
    });
});

//Tambah satuan
$('#ModalTambahSatuan').on('show.bs.modal', function (e) {
    var id_barang = $(e.relatedTarget).data('id');
    $('#FormTambahSatuan').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Barang/FormTambahSatuan.php',
        data        : {id_barang: id_barang},
        success     : function(data){
            $('#FormTambahSatuan').html(data);
            $('#konversi_satuan_multi').keyup(function(){
                var konversi_satuan_multi=$('#konversi_satuan_multi').val();
                var id_barang=$('#id_barang').val();
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Barang/HitungStokSatuanMulti.php',
                    data 	    :  {id_barang: id_barang, konversi: konversi_satuan_multi},
                    success     : function(data){
                        $('#stok_multi').val(data);
                    }
                });
            });
            //Proses Tambah Satuan
            $('#ProsesTambahSatuan').submit(function(){
                $('#NotifikasiTambahSatuan').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                var form = $('#ProsesTambahSatuan')[0];
                var data = new FormData(form);
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Barang/ProsesTambahSatuan.php',
                    data 	    :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#NotifikasiTambahSatuan').html(data);
                        var NotifikasiTambahSatuanBerhasil=$('#NotifikasiTambahSatuanBerhasil').html();
                        if(NotifikasiTambahSatuanBerhasil=="Success"){
                            location.reload();
                        }
                    }
                });
            });
        }
    });
});
//Edit satuan
$('#ModalEditSatuan').on('show.bs.modal', function (e) {
    var id_barang_satuan = $(e.relatedTarget).data('id');
    $('#FormEditSatuan').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Barang/FormEditSatuan.php',
        data        : {id_barang_satuan: id_barang_satuan},
        success     : function(data){
            $('#FormEditSatuan').html(data);
            $('#konversi_satuan_multi').keyup(function(){
                var konversi_satuan_multi=$('#konversi_satuan_multi').val();
                var id_barang=$('#id_barang').val();
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Barang/HitungStokSatuanMulti.php',
                    data 	    :  {id_barang: id_barang, konversi: konversi_satuan_multi},
                    success     : function(data){
                        $('#stok_multi_edit').val(data);
                    }
                });
            });
            //Proses Edit Satuan
            $('#ProsesEditSatuan').submit(function(){
                $('#NotifikasiEditSatuan').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                var form = $('#ProsesEditSatuan')[0];
                var data = new FormData(form);
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Barang/ProsesEditSatuan.php',
                    data 	    :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#NotifikasiEditSatuan').html(data);
                        var NotifikasiEditSatuanBerhasil=$('#NotifikasiEditSatuanBerhasil').html();
                        if(NotifikasiEditSatuanBerhasil=="Success"){
                            location.reload();
                        }
                    }
                });
            });
        }
    });
});
//Hapus Satuan Barang
$('#ModalDeleteSatuan').on('show.bs.modal', function (e) {
    var id_barang_satuan = $(e.relatedTarget).data('id');
    $('#FormHapusSatuan').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Barang/FormHapusSatuan.php',
        data        : {id_barang_satuan: id_barang_satuan},
        success     : function(data){
            $('#FormHapusSatuan').html(data);
            //Konfirmasi Hapus Barang
            $('#KonfirmasiHapusSatuan').click(function(){
                $('#NotifikasiHapusSatuan').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Barang/ProsesHapusSatuan.php',
                    data        : {id_barang_satuan: id_barang_satuan},
                    success     : function(data){
                        $('#NotifikasiHapusSatuan').html(data);
                        var NotifikasiHapusSatuanBerhasil=$('#NotifikasiHapusSatuanBerhasil').html();
                        if(NotifikasiHapusSatuanBerhasil=="Success"){
                            location.reload();
                        }
                    }
                });
            });
        }
    });
});


//Hapus Satuan Barang
$('#ModalDeleteKategoriHarga').on('show.bs.modal', function (e) {
    var id_barang_harga = $(e.relatedTarget).data('id');
    $('#FormHapusKategoriHarga').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Barang/FormHapusKategoriHarga.php',
        data        : {id_barang_harga: id_barang_harga},
        success     : function(data){
            $('#FormHapusKategoriHarga').html(data);
            //Konfirmasi Hapus Barang
            $('#KonfirmasiHapusKaegoriHarga').click(function(){
                $('#NotifikasiHapusKategoriHarga').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Barang/ProsesHapusKategoriHarga.php',
                    data        : {id_barang_harga: id_barang_harga},
                    success     : function(data){
                        $('#NotifikasiHapusKategoriHarga').html(data);
                        var NotifikasiHapusKategoriHargaBerhasil=$('#NotifikasiHapusKategoriHargaBerhasil').html();
                        if(NotifikasiHapusKategoriHargaBerhasil=="Success"){
                            location.reload();
                        }
                    }
                });
            });
        }
    });
});

//Tambah Batch & Expired
$('#ModalTambahExpiredDate').on('show.bs.modal', function (e) {
    var id_barang = $(e.relatedTarget).data('id');
    $('#FormTambahExpiredDate').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Barang/FormTambahExpiredDate.php',
        data        : {id_barang: id_barang},
        success     : function(data){
            $('#FormTambahExpiredDate').html(data);
            //Proses Tambah kategori harga
            $('#ProsesTambahExpiredDate').submit(function(){
                $('#NotifikasiTambahExpiredDate').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                var form = $('#ProsesTambahExpiredDate')[0];
                var data = new FormData(form);
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Barang/ProsesTambahExpiredDate.php',
                    data 	    :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#NotifikasiTambahExpiredDate').html(data);
                        var NotifikasiTambahExpiredDateBerhasil=$('#NotifikasiTambahExpiredDateBerhasil').html();
                        if(NotifikasiTambahExpiredDateBerhasil=="Success"){
                            location.reload();
                        }
                    }
                });
            });
        }
    });
});
//Edit Expired Date
$('#ModalEditExpiredDate').on('show.bs.modal', function (e) {
    var id_barang_bacth = $(e.relatedTarget).data('id');
    $('#FormEditExpiredDate').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Barang/FormEditExpiredDate.php',
        data        : {id_barang_bacth: id_barang_bacth},
        success     : function(data){
            $('#FormEditExpiredDate').html(data);
            //Proses Edit kategori harga
            $('#ProsesEditExpiredDate').submit(function(){
                $('#NotifikasiEditExpiredDate').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                var form = $('#ProsesEditExpiredDate')[0];
                var data = new FormData(form);
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Barang/ProsesEditExpiredDate.php',
                    data 	    :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#NotifikasiEditExpiredDate').html(data);
                        var NotifikasiEditExpiredDateBerhasil=$('#NotifikasiEditExpiredDateBerhasil').html();
                        if(NotifikasiEditExpiredDateBerhasil=="Success"){
                            location.reload();
                        }
                    }
                });
            });
        }
    });
});
//Hapus Satuan Barang
$('#ModalDeleteExpiredDate').on('show.bs.modal', function (e) {
    var id_barang_bacth = $(e.relatedTarget).data('id');
    $('#FormHapusExpiredDate').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Barang/FormHapusExpiredDate.php',
        data        : {id_barang_bacth: id_barang_bacth},
        success     : function(data){
            $('#FormHapusExpiredDate').html(data);
            //Konfirmasi Hapus Barang
            $('#KonfirmasiHapusExpiredDate').click(function(){
                $('#NotifikasiHapusExpiredDate').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Barang/ProsesHapusExpiredDate.php',
                    data        : {id_barang_bacth: id_barang_bacth},
                    success     : function(data){
                        $('#NotifikasiHapusExpiredDate').html(data);
                        var NotifikasiHapusExpiredDateBerhasil=$('#NotifikasiHapusExpiredDateBerhasil').html();
                        if(NotifikasiHapusExpiredDateBerhasil=="Success"){
                            location.reload();
                        }
                    }
                });
            });
        }
    });
});
//Proses Import Data Barang
$('#ProsesImportDataBarang').submit(function(){
    $('#NotifikasiLogProsesImport').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesImportDataBarang')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Barang/ProsesImportDataBarang.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiLogProsesImport').html(data);
            swal("Import Selesai!", "Silahakan Cek Kembali Proses Import Melalui Log!", "success");
        }
    });
});
var ProsesCariRiwayatTransaksi = $('#ProsesCariRiwayatTransaksi').serialize();
$('#TampilkanRiwayatTransaksi').html('Loading...');
$.ajax({
    type 	    : 'POST',
    url 	    : '_Page/Barang/TabelRiwayatTransaksi.php',
    data 	    :  ProsesCariRiwayatTransaksi,
    success     : function(data){
        $('#TampilkanRiwayatTransaksi').html(data);
    }
});
$('#ProsesCariRiwayatTransaksi').submit(function(){
    var ProsesCariRiwayatTransaksi = $('#ProsesCariRiwayatTransaksi').serialize();
    $('#TampilkanRiwayatTransaksi').html('Loading...');
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Barang/TabelRiwayatTransaksi.php',
        data 	    :  ProsesCariRiwayatTransaksi,
        success     : function(data){
            $('#TampilkanRiwayatTransaksi').html(data);
        }
    });
});
//Hapus Satuan Barang
$('#ModalExportRiwayatTransaksi').on('show.bs.modal', function (e) {
    var ProsesCariRiwayatTransaksi = $('#ProsesCariRiwayatTransaksi').serialize();
    $('#FormExportRiwayatTransaksi').html('Loading...');
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Barang/FormExportRiwayatTransaksi.php',
        data 	    :  ProsesCariRiwayatTransaksi,
        success     : function(data){
            $('#FormExportRiwayatTransaksi').html(data);
        }
    });
});