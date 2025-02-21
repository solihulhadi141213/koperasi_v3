//Proses Edit Pinjaman
$('#ProsesFilterPeriodeTahunan').submit(function(){
    $('#MenampilkanTabelPinjamanPeriode').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesFilterPeriodeTahunan')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/RekapPinjaman/ProsesFilterPeriode.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#MenampilkanTabelPinjamanPeriode').html(data);
            $('#ModalRekapTahunan').modal('hide');
        }
    });
});