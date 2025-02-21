function filterAndLoadTable() {
    $.ajax({
        type: 'POST',
        url: '_Page/JenisSimpanan/TabelJenisSimpanan.php',
        success: function(data) {
            $('#MenampilkanTabelJenisSimpanan').html(data);
        }
    });
}
$(document).ready(function() {
    filterAndLoadTable();
    $('#form_nominal').hide();
});
//Ketika form rutin di ubah
$('#rutin').change(function(){
    var rutin = $('#rutin').val();
    if(rutin==1){
        $('#form_nominal').show();
    }else{
        $('#form_nominal').hide();
    }
});
$('#nominal').on('keypress', function(e) {
    // Hanya mengizinkan angka (0-9)
    if (e.which < 48 || e.which > 57) {
        e.preventDefault();
    }
});
//Proses Tambah Jenis Simpanan
$('#ProsesTambahJenisSimpanan').submit(function(){
    $('#NotifikasiTambahJenisSimpanan').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesTambahJenisSimpanan')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/JenisSimpanan/ProsesTambahJenisSimpanan.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiTambahJenisSimpanan').html(data);
            var NotifikasiTambahJenisSimpananBerhasil=$('#NotifikasiTambahJenisSimpananBerhasil').html();
            if(NotifikasiTambahJenisSimpananBerhasil=="Success"){
                $('#NotifikasiTambahJenisSimpanan').html('');
                $("#ProsesTambahJenisSimpanan")[0].reset();
                $('#ModalTambahJenisSimpanan').modal('hide');
                Swal.fire(
                    'Success!',
                    'Tambah Jenis Simpanan Berhasil!',
                    'success'
                )
                //Menampilkan Data
                filterAndLoadTable();
            }
        }
    });
});
//Detail Jenis Simpanan
$('#ModalDetailJenisSimpanan').on('show.bs.modal', function (e) {
    var id_simpanan_jenis= $(e.relatedTarget).data('id');
    $('#FormDetailJenisSimpanan').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/JenisSimpanan/FormDetailJenisSimpanan.php',
        data        : {id_simpanan_jenis: id_simpanan_jenis},
        success     : function(data){
            $('#FormDetailJenisSimpanan').html(data);
        }
    });
});
//Edit Jenis Simpanan
$('#ModalEditJenisSimpanan').on('show.bs.modal', function (e) {
    var id_simpanan_jenis= $(e.relatedTarget).data('id');
    $('#FormEditJenisSimpanan').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/JenisSimpanan/FormEditJenisSimpanan.php',
        data        : {id_simpanan_jenis: id_simpanan_jenis},
        success     : function(data){
            $('#FormEditJenisSimpanan').html(data);
            $('#NotifikasiEditJenisSimpanan').html('');
        }
    });
});
//Proses Edit Jenis Simpanan
$('#ProsesEditJenisSimpanan').submit(function(){
    $('#NotifikasiEditJenisSimpanan').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesEditJenisSimpanan')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/JenisSimpanan/ProsesEditJenisSimpanan.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiEditJenisSimpanan').html(data);
            var NotifikasiEditJenisSimpananBerhasil=$('#NotifikasiEditJenisSimpananBerhasil').html();
            if(NotifikasiEditJenisSimpananBerhasil=="Success"){
                $('#NotifikasiEditJenisSimpanan').html('');
                $('#ModalEditJenisSimpanan').modal('hide');
                Swal.fire(
                    'Success!',
                    'Edit Jenis Simpanan Berhasil!',
                    'success'
                )
                //Menampilkan Data
                filterAndLoadTable();
            }
        }
    });
});
//Hapus Jenis Simpanan
$('#ModalHapusJenisSimpanan').on('show.bs.modal', function (e) {
    var id_simpanan_jenis= $(e.relatedTarget).data('id');
    $('#FormHapusJenisSimpanan').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/JenisSimpanan/FormHapusJenisSimpanan.php',
        data        : {id_simpanan_jenis: id_simpanan_jenis},
        success     : function(data){
            $('#FormHapusJenisSimpanan').html(data);
            $('#NotifikasiHapusJenisSimpanan').html('');
        }
    });
});
//Proses Hapus Jenis Simpanan
$('#ProsesHapusJenisSimpanan').submit(function(){
    $('#NotifikasiHapusJenisSimpanan').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesHapusJenisSimpanan')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/JenisSimpanan/ProsesHapusJenisSimpanan.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiHapusJenisSimpanan').html(data);
            var NotifikasiHapusJenisSimpananBerhasil=$('#NotifikasiHapusJenisSimpananBerhasil').html();
            if(NotifikasiHapusJenisSimpananBerhasil=="Success"){
                $('#NotifikasiHapusJenisSimpanan').html('');
                $('#ModalHapusJenisSimpanan').modal('hide');
                Swal.fire(
                    'Success!',
                    'Hapus Jenis Simpanan Berhasil!',
                    'success'
                )
                //Menampilkan Data
                filterAndLoadTable();
            }
        }
    });
});