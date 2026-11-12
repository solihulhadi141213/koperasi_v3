// BLOCK FUNCTION

// Tampilkan toast sukses sebelum memuat ulang halaman setelah penyimpanan.
function showAutoJurnalSuccessToast(message) {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: message,
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true
    }).then(function() {
        location.reload();
    });
}

//Proses Auto Jurnal Simpan, Pinjam
$('#ProsesAutoJurnal').submit(function(){
    $('#NotifikasiSimpanAutoJurnal').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesAutoJurnal')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/AutoJurnal/ProsesAutoJurnal.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiSimpanAutoJurnal').html(data);
            var NotifikasiSimpanAutoJurnalBerhasil=$('#NotifikasiSimpanAutoJurnalBerhasil').html();
            if(NotifikasiSimpanAutoJurnalBerhasil=="Success"){
                showAutoJurnalSuccessToast('Pengaturan Auto Jurnal Simpan Pinjam Berhasil');
            }
        }
    });
});

//Proses Auto Jurnal Jual/Beli
$('#ProssesSimpanAutoJurnalJualBeli').submit(function(){
    $('#NotifikasiSimpanAutoJurnalJualBeli').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProssesSimpanAutoJurnalJualBeli')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/AutoJurnal/ProssesSimpanAutoJurnalJualBeli.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiSimpanAutoJurnalJualBeli').html(data);
            var NotifikasiSimpanAutoJurnalJualBeliBerhasil=$('#NotifikasiSimpanAutoJurnalJualBeliBerhasil').html();
            if(NotifikasiSimpanAutoJurnalJualBeliBerhasil=="Berhasil"){
                showAutoJurnalSuccessToast('Pengaturan Auto Jurnal Jual/Beli Berhasil');
            }
        }
    });
});

//Proses Auto Jurnal SHU
$('#ProssesSimpanAutoJurnalShu').submit(function(){
    $('#NotifikasiSimpanAutoJurnalShu').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProssesSimpanAutoJurnalShu')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/AutoJurnal/ProssesSimpanAutoJurnalShu.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiSimpanAutoJurnalShu').html(data);
            var NotifikasiSimpanAutoJurnalShuBerhasil=$('#NotifikasiSimpanAutoJurnalShuBerhasil').html();
            if(NotifikasiSimpanAutoJurnalShuBerhasil=="Berhasil"){
                showAutoJurnalSuccessToast('Pengaturan Auto Jurnal SHU Berhasil');
            }
        }
    });
});