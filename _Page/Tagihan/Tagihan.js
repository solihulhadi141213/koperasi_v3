function filterAndLoadTable() {
    var ProsesFilter = $('#ProsesFilter').serialize();
    $.ajax({
        type: 'POST',
        url: '_Page/Tagihan/TabelTagihan.php',
        data: ProsesFilter,
        success: function(data) {
            $('#MenampilkanTabelTagihan').html(data);
        }
    });
}
$(document).ready(function() {
    filterAndLoadTable();
});
$('#keyword_by').change(function(){
    var keyword_by = $('#keyword_by').val();
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Pinjaman/FormFilter.php',
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
//Ketika Modal Export Tagihan Muncul
$('#ModalExportTagihan').on('show.bs.modal', function (e) {
    $('#FormExportTagihan').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Tagihan/FormExportTagihan.php',
        success     : function(data){
            $('#FormExportTagihan').html(data);
        }
    });
});
//Detail Pinjaman
$('#ModalDetailPinjaman').on('show.bs.modal', function (e) {
    var id_pinjaman= $(e.relatedTarget).data('id');
    $('#FormDetailPinjaman').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Tagihan/FormDetailPinjaman.php',
        data        : {id_pinjaman: id_pinjaman},
        success     : function(data){
            $('#FormDetailPinjaman').html(data);
        }
    });
});