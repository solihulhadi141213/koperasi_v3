//Fungsi Menampilkan Data
function filterAndLoadTable() {
    var ProsesFilter = $('#ProsesFilter').serialize();
    $.ajax({
        type: 'POST',
        url: '_Page/RekapSimpanan/TabelRekapSimpanan.php',
        data: ProsesFilter,
        success: function(data) {
            $('#MenampilkanTabelRekapSimpanan').html(data);
        }
    });
}
function filterAndLoadTable2() {
    var ProsesFilterRank = $('#ProsesFilterRank').serialize();
    $.ajax({
        type: 'POST',
        url: '_Page/RekapSimpanan/TabelRekapSimpananRank.php',
        data: ProsesFilterRank,
        success: function(data) {
            $('#MenampilkanTabelRekapSimpananRank').html(data);
        }
    });
}
function toggleFields() {
    var periode = $('#periode').val();
    if (periode === 'Semua') {
        $('#FormTahun').hide();
        $('#FormBulan').hide();
    } else if (periode === 'Tahunan') {
        $('#FormTahun').show();
        $('#FormBulan').hide();
    } else if (periode === 'Bulanan') {
        $('#FormTahun').show();
        $('#FormBulan').show();
    }
}
function toggleFields2() {
    var periode = $('#periode_rank').val();
    if (periode === 'Semua') {
        $('#FormTahunRank').hide();
        $('#FormBulanRank').hide();
    } else if (periode === 'Tahunan') {
        $('#FormTahunRank').show();
        $('#FormBulanRank').hide();
    } else if (periode === 'Bulanan') {
        $('#FormTahunRank').show();
        $('#FormBulanRank').show();
    }
}
$(document).ready(function() {
    filterAndLoadTable();
    filterAndLoadTable2();
    // Panggil fungsi toggleFields saat halaman dimuat
    toggleFields();
    toggleFields2();
    // Panggil fungsi toggleFields saat periode diubah
    $('#periode').change(function() {
        toggleFields();
    });
    $('#periode_rank').change(function() {
        toggleFields2();
    });
});
//Proses Filter Lembaga
$('#ProsesFilter').submit(function(){
    $('#MenampilkanTabelRekapSimpanan').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesFilter')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/RekapSimpanan/TabelRekapSimpanan.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#MenampilkanTabelRekapSimpanan').html(data);
            $('#ModalFilter').modal('hide');
        }
    });
});
//Proses FilterRank
$('#ProsesFilterRank').submit(function(){
    $('#MenampilkanTabelRekapSimpananRank').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesFilterRank')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/RekapSimpanan/TabelRekapSimpananRank.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#MenampilkanTabelRekapSimpananRank').html(data);
            $('#ModalFilterRank').modal('hide');
        }
    });
});
//Modal Cetak Rekap Simpanan
$('#ModalCetak').on('show.bs.modal', function (e) {
    var ProsesFilter = $('#ProsesFilter').serialize();
    $.ajax({
        type: 'POST',
        url: '_Page/RekapSimpanan/FormCetakDataRekap.php',
        data: ProsesFilter,
        success: function(data) {
            $('#FormCetakDataRekap').html(data);
        }
    });
});

//Modal Cetak Rekap Simpanan Rank
$('#ModalCetak2').on('show.bs.modal', function (e) {
    var ProsesFilter = $('#ProsesFilterRank').serialize();
    $.ajax({
        type: 'POST',
        url: '_Page/RekapSimpanan/FormCetakDataRekap2.php',
        data: ProsesFilter,
        success: function(data) {
            $('#FormCetakDataRekap2').html(data);
        }
    });
});