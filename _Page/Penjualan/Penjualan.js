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


});