//Fungsi Menampilkan Data Akses
function ShowData() {
    var ProsesFilter = $('#ProsesFilter').serialize();
    $.ajax({
        type    : 'POST',
        url     : '_Page/Supplier/TabelSupplier.php',
        data    : ProsesFilter,
        success: function(data) {
            $('#TabelSupplier').html(data);
        }
    });
}
$(document).ready(function() {
    //Inisiasi Data Pertama Kali
    ShowData();

    //Ketika Batas Diubah
    $('#batas').change(function(){
        ShowData();
    });

    //Ketiika keyword_by Diubah
    $('#keyword_by').change(function(){
        var KeywordBy = $('#keyword_by').val();
        $('#FormFilterKeyword').html('Loading...');
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Supplier/FormFilterKeyword.php',
            data 	    :  {KeywordBy: KeywordBy},
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

    //Proses Tambah Supplier
    $('#ProsesTambahSupplier').submit(function(){
        $('#NotifikasiTambahSupplier').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesTambahSupplier')[0];
        var data = new FormData(form);
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Supplier/ProsesTambahSupplier.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiTambahSupplier').html(data);
                var NotifikasiTambahSupplierBerhasil=$('#NotifikasiTambahSupplierBerhasil').html();
                if(NotifikasiTambahSupplierBerhasil=="Success"){
                    $('#NotifikasiTambahSupplier').html("");
                    //Reset Form Filter
                    $('#ProsesFilter')[0].reset();

                    //Reset Form ProsesTambahSupplier
                    $('#ProsesTambahSupplier')[0].reset();

                    //Tutup Modal
                    $('#ModalTambahSupplier').modal('hide');

                    //Tampilkan Swal
                    Swal.fire(
                        'Success!',
                        'Tambah Supplier Berhasil!',
                        'success'
                    )

                    //Tampilkan Data
                    ShowData(0);
                }
            }
        });
    });

    //Detail Supplier
    $('#ModalDetailSupplier').on('show.bs.modal', function (e) {
        var id_supplier= $(e.relatedTarget).data('id');
        $('#FormDetailSupplier').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Supplier/FormDetailSupplier.php',
            data        : {id_supplier: id_supplier},
            success     : function(data){
                $('#FormDetailSupplier').html(data);
            }
        });
    });

    //Edit Supplier
    $('#ModalEditSupplier').on('show.bs.modal', function (e) {
        var id_supplier = $(e.relatedTarget).data('id');
        $('#FormEditSupplier').html("Loading...");
        $('#NotifikasiEditSupplier').html("");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Supplier/FormEditSupplier.php',
            data        : {id_supplier: id_supplier},
            success     : function(data){
                $('#FormEditSupplier').html(data);
            }
        });
    });

    //Proses Edit Supplier
    $('#ProsesEditSupplier').submit(function(){
        $('#NotifikasiEditSupplier').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesEditSupplier')[0];
        var data = new FormData(form);
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Supplier/ProsesEditSupplier.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiEditSupplier').html(data);
                var NotifikasiEditSupplierBerhasil=$('#NotifikasiEditSupplierBerhasil').html();
                if(NotifikasiEditSupplierBerhasil=="Success"){
                    
                    //Kosongkan Notifikasi
                    $('#NotifikasiEditSupplier').html("");
                    //Reset Form Filter
                    $('#ProsesEditSupplier')[0].reset();

                    //Tutup Modal
                    $('#ModalEditSupplier').modal('hide');

                    //Tampilkan Swal
                    Swal.fire(
                        'Success!',
                        'Edit Supplier Berhasil!',
                        'success'
                    )

                    //Tampilkan Data
                    ShowData(0);
                }
            }
        });
    });

    //Modal Hapus Supplier
    $('#ModalHapusSupplier').on('show.bs.modal', function (e) {
        var id_supplier = $(e.relatedTarget).data('id');
        $('#FormHapusSupplier').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Supplier/FormHapusSupplier.php',
            data        : {id_supplier: id_supplier},
            success     : function(data){
                $('#FormHapusSupplier').html(data);
                //Bersihkan Notifikasi
                $('#NotifikasiHapusSupplier').html("");
            }
        });
    });

    //Proses Hapus Supplier
    $('#ProsesHapusSupplier').submit(function(){
        $('#NotifikasiHapusSupplier').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesHapusSupplier')[0];
        var data = new FormData(form);
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Supplier/ProsesHapusSupplier.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiHapusSupplier').html(data);
                var NotifikasiHapusSupplierBerhasil=$('#NotifikasiHapusSupplierBerhasil').html();
                if(NotifikasiHapusSupplierBerhasil=="Success"){
                    
                    //Kosongkan Notifikasi
                    $('#NotifikasiHapusSupplier').html("");
                    //Reset Form Filter
                    $('#ProsesHapusSupplier')[0].reset();

                    //Tutup Modal
                    $('#ModalHapusSupplier').modal('hide');

                    //Tampilkan Swal
                    Swal.fire(
                        'Success!',
                        'Hapus Supplier Berhasil!',
                        'success'
                    )

                    //Tampilkan Data
                    ShowData(0);
                }
            }
        });
    });
});







$('#RincianBarang').click(function(){
    $('#HalamanDetailSupplier').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var GetIdSupplier =$('#GetIdSupplier').html();
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Supplier/RincianBarang.php',
        data 	    :  {GetIdSupplier: GetIdSupplier},
        success     : function(data){
            $('#HalamanDetailSupplier').html(data);
        }
    });
});
$('#RiwayatTransaksi').click(function(){
    $('#HalamanDetailSupplier').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var GetIdSupplier =$('#GetIdSupplier').html();
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Supplier/RiwayatTransaksi.php',
        data 	    :  {GetIdSupplier: GetIdSupplier},
        success     : function(data){
            $('#HalamanDetailSupplier').html(data);
        }
    });
});



//Proses Import Data Anggota
$('#ProsesImportDataSupplier').submit(function(){
    $('#NotifikasiLogProsesImport').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesImportDataSupplier')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Supplier/ProsesImportDataSupplier.php',
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