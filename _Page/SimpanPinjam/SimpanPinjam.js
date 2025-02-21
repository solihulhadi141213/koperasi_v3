function hanyaAngka(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    // Memastikan bahwa hanya angka yang bisa dimasukkan (charCode 48-57 adalah angka 0-9)
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
$('#ProsesSimpanPinjam').submit(function(){
    var ProsesSimpanPinjam = $('#ProsesSimpanPinjam').serialize();
    $('#MenampilkanTabelSimpanPinjam').html('Loading...');
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/SimpanPinjam/TabelSimpanPinjam.php',
        data 	    :  ProsesSimpanPinjam,
        success     : function(data){
            $('#MenampilkanTabelSimpanPinjam').html(data);
        }
    });
});