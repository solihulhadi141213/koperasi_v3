function filterAndLoadTable() {
    var ProsesFilter = $('#ProsesFilter').serialize();
    $.ajax({
        type: 'POST',
        url: '_Page/BagiHasil/TabelBagiHasil.php',
        data: ProsesFilter,
        success: function(data) {
            $('#MenampilkanTabelBagiHasil').html(data);
        }
    });
}
function ShowRincianBagiHasil() {
    var GetIdShuSession = $('#GetIdShuSession').val();
    $.ajax({
        type    : 'POST',
        url     : '_Page/BagiHasil/TabelRincianBagiHasil.php',
        data    : {id_shu_session: GetIdShuSession},
        success: function(data) {
            $('#MenampilkanRincianBagiHasil').html(data);
        }
    });
}
function ShowJurnalBagiHasil() {
    var GetIdShuSession = $('#GetIdShuSession').val();
    $.ajax({
        type    : 'POST',
        url     : '_Page/BagiHasil/TabelJurnalBagiHasil.php',
        data    : {id_shu_session: GetIdShuSession},
        success: function(data) {
            $('#MenampilkanJurnalBagiHasil').html(data);
        }
    });
}
$('#keyword_by').change(function(){
    var keyword_by = $('#keyword_by').val();
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/BagiHasil/FormFilter.php',
        data 	    :  {keyword_by: keyword_by},
        success     : function(data){
            $('#FormFilter').html(data);
        }
    });
});
$('#ProsesFilter').submit(function(){
    $('#page').val("1");
    filterAndLoadTable();
    $('#ModalFilter').modal('hide');
});
$(document).ready(function() {
    filterAndLoadTable();
    ShowRincianBagiHasil();
    ShowJurnalBagiHasil();
});
//Export Bagi Hasil
$('#ModalExportBagiHasil').on('show.bs.modal', function (e) {
    $('#FormExportBagiHasil').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/BagiHasil/FormExportBagiHasil.php',
        success     : function(data){
            $('#FormExportBagiHasil').html(data);
        }
    });
});
//Modal Tambah Bagi Hasil
$('#ModalTambahBagiHasil').on('show.bs.modal', function (e) {
    $('#FormTambahBagiHasil').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/BagiHasil/FormTambahBagiHasil.php',
        success     : function(data){
            $('#FormTambahBagiHasil').html(data);
            $('#NotifikasiTambahBagiHasil').html('<code>Pastikan Parameter Bagi Hasil (SHU) Yang Anda Gunakan Sudah Sesuai</code>');
        }
    });
});
//Proses Tambah Sesi Bagi Hasil
$('#ProsesTambahBagiHasil').submit(function(){
    $('#NotifikasiTambahBagiHasil').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesTambahBagiHasil')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/BagiHasil/ProsesTambahBagiHasil.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiTambahBagiHasil').html(data);
            var NotifikasiTambahBagiHasilBerhasil=$('#NotifikasiTambahBagiHasilBerhasil').html();
            if(NotifikasiTambahBagiHasilBerhasil=="Success"){
                $('#ProsesTambahBagiHasil')[0].reset();
                $('#ProsesFilter')[0].reset();
                filterAndLoadTable();
                $('#NotifikasiTambahBagiHasil').html('');
                $('#ModalTambahBagiHasil').modal('hide');
                Swal.fire(
                    'Success!',
                    'Tambah Sesi Bagi Hasil (SHU) Berhasil!',
                    'success'
                )
            }
        }
    });
});
//Detail Bagi Hasil
$('#ModalDetailBagiHasil').on('show.bs.modal', function (e) {
    var id_shu_session = $(e.relatedTarget).data('id');
    $('#FormDetailBagiHasil').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/BagiHasil/FormDetailBagiHasil.php',
        data 	    :  {id_shu_session: id_shu_session},
        success     : function(data){
            $('#FormDetailBagiHasil').html(data);
        }
    });
});
//Edit Bagi Hasil
$('#ModalEditBagiHasil').on('show.bs.modal', function (e) {
    var id_shu_session = $(e.relatedTarget).data('id');
    $('#FormEditBagiHasil').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/BagiHasil/FormEditBagiHasil.php',
        data        : {id_shu_session: id_shu_session},
        success     : function(data){
            $('#FormEditBagiHasil').html(data);
            $('#NotifikasiEditBagiHasil').html('<code>Pastikan Parameter Bagi Hasil (SHU) Yang Anda Gunakan Sudah Sesuai</code>');
        }
    });
});
//Proses Edit Sesi Bagi Hasil
$('#ProsesEditBagiHasil').submit(function(){
    $('#NotifikasiEditBagiHasil').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesEditBagiHasil')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/BagiHasil/ProsesEditBagiHasil.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiEditBagiHasil').html(data);
            var NotifikasiEditBagiHasilBerhasil=$('#NotifikasiEditBagiHasilBerhasil').html();
            if(NotifikasiEditBagiHasilBerhasil=="Success"){
                $('#ProsesEditBagiHasil')[0].reset();
                filterAndLoadTable();
                $('#NotifikasiEditBagiHasil').html('');
                $('#ModalEditBagiHasil').modal('hide');
                Swal.fire(
                    'Success!',
                    'Edit Sesi Bagi Hasil (SHU) Berhasil!',
                    'success'
                )
            }
        }
    });
});
//Menampilkan Form Hapus Bagi Hasil
$('#ModalHapusBagiHasil').on('show.bs.modal', function (e) {
    var id_shu_session = $(e.relatedTarget).data('id');
    $('#FormHapusBagiHasil').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/BagiHasil/FormHapusBagiHasil.php',
        data        : {id_shu_session: id_shu_session},
        success     : function(data){
            $('#FormHapusBagiHasil').html(data);
            $('#NotifikasiHapusSesiBagiHasil').html('<small><code>Apakah Anda Yakin Akan Menghapus Sesi Bagi Hasil Ini?</code></small>');
        }
    });
});
//Proses Hapus Sesi Bagi Hasil
$('#ProsesHapusBagiHasil').submit(function(){
    $('#NotifikasiHapusSesiBagiHasil').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesHapusBagiHasil')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/BagiHasil/ProsesHapusBagiHasil.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiHapusSesiBagiHasil').html(data);
            var NotifikasiHapusSesiBagiHasilBerhasil=$('#NotifikasiHapusSesiBagiHasilBerhasil').html();
            if(NotifikasiHapusSesiBagiHasilBerhasil=="Success"){
                $('#ProsesHapusBagiHasil')[0].reset();
                filterAndLoadTable();
                $('#NotifikasiHapusSesiBagiHasil').html('');
                $('#ModalHapusBagiHasil').modal('hide');
                Swal.fire(
                    'Success!',
                    'Hapus Sesi Bagi Hasil (SHU) Berhasil!',
                    'success'
                )
            }
        }
    });
});
//Modal Tambah Jurnal
$('#ModalTambahJurnal').on('show.bs.modal', function (e) {
    var id_shu_session = $(e.relatedTarget).data('id');
    $('#FormTambahJurnal').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/BagiHasil/FormTambahJurnal.php',
        data        : {id_shu_session: id_shu_session},
        success     : function(data){
            $('#FormTambahJurnal').html(data);
            $('#NotifikasiTambahJurnal').html('<code class="text-primary">Pastikan informasi jurnal sudah sesuai</code>');
        }
    });
});
//Proses Tambah Jurnal
$('#ProsesTambahJurnal').submit(function(){
    $('#NotifikasiTambahJurnal').html('Loading...');
    var form = $('#ProsesTambahJurnal')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/BagiHasil/ProsesTambahJurnal.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiTambahJurnal').html(data);
            var NotifikasiTambahJurnalBerhasil=$('#NotifikasiTambahJurnalBerhasil').html();
            if(NotifikasiTambahJurnalBerhasil=="Success"){
                $('#NotifikasiTambahJurnal').html('');
                $('#ModalTambahJurnal').modal('hide');
                Swal.fire(
                    'Success!',
                    'Tambah Jurnal Berhasil!',
                    'success'
                )
                //Menampilkan Data
                ShowJurnalBagiHasil();
            }
        }
    });
});
//Modal Edit Jurnal
$('#ModalEditJurnal').on('show.bs.modal', function (e) {
    var id_jurnal = $(e.relatedTarget).data('id');
    $('#FormEditJurnal').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/BagiHasil/FormEditJurnal.php',
        data        : {id_jurnal: id_jurnal},
        success     : function(data){
            $('#FormEditJurnal').html(data);
            $('#NotifikasiEditJurnal').html('<code class="text-primary">Pastikan informasi jurnal sudah sesuai</code>');
        }
    });
});
//Proses Edit Jurnal
$('#ProsesEditJurnal').submit(function(){
    $('#NotifikasiEditJurnal').html('Loading...');
    var form = $('#ProsesEditJurnal')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/BagiHasil/ProsesEditJurnal.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiEditJurnal').html(data);
            var NotifikasiEditJurnalBerhasil=$('#NotifikasiEditJurnalBerhasil').html();
            if(NotifikasiEditJurnalBerhasil=="Success"){
                $('#NotifikasiEditJurnal').html('');
                $('#ModalEditJurnal').modal('hide');
                Swal.fire(
                    'Success!',
                    'Edit Jurnal Berhasil!',
                    'success'
                )
                ShowJurnalBagiHasil();
            }
        }
    });
});
//Modal Hhapus Jurnal 
$('#ModalHapusJurnal').on('show.bs.modal', function (e) {
    var id_jurnal = $(e.relatedTarget).data('id');
    $('#FormHapusJurnal').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/BagiHasil/FormHapusJurnal.php',
        data        : {id_jurnal: id_jurnal},
        success     : function(data){
            $('#FormHapusJurnal').html(data);
            $('#NotifikasiHapusJurnal').html("");
        }
    });
});
//Proses Hapus Jurnal 
$('#ProsesHapusJurnal').submit(function(){
    $('#NotifikasiHapusJurnal').html('Loading...');
    var form = $('#ProsesHapusJurnal')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/BagiHasil/ProsesHapusJurnal.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiHapusJurnal').html(data);
            var NotifikasiHapusJurnalBerhasil=$('#NotifikasiHapusJurnalBerhasil').html();
            if(NotifikasiHapusJurnalBerhasil=="Success"){
                $('#NotifikasiHapusJurnal').html('');
                $('#ModalHapusJurnal').modal('hide');
                Swal.fire(
                    'Success!',
                    'Hapus Jurnal  Berhasil!',
                    'success'
                )
                //Menampilkan Data
                ShowJurnalBagiHasil();
            }
        }
    });
});