// Fungsi untuk ubah format tanggal dari Y-m-d ke d/m/Y
function formatTanggal(tanggal) {
    var parts = tanggal.split('-');
    return parts[2] + '/' + parts[1] + '/' + parts[0];
}

//Fungsi Untuk Menampilkan Data Anggota
function ShowData() {
    
    // Tangkap Periode Data
    var periode_1 = formatTanggal($('#periode_1').val());
    var periode_2 = formatTanggal($('#periode_2').val());

    //Tempelkan Ke Header Card
    $('#put_periode_data').html(''+periode_1+' s/d '+periode_2+'');
    //Tangkap Data Dari Form
    var ProsesFilter = $('#ProsesFilter').serialize();
    $.ajax({
        type: 'POST',
        url: '_Page/PotonganAnggota/TabelPotonganAnggota.php',
        data: ProsesFilter,
        success: function(data) {
            $('#TabelPotonganAnggota').html(data);
        }
    });
}

$(document).ready(function() {

    //Menampilkan Data Pertama kali
    ShowData();
    
    //Ketika Submit Filter
    $('#ProsesFilter').submit(function(){
        //Kembalikan ke halaman 1
        $('#page').val(1);

        //Tampilkan Data
        ShowData();

        //Tutup Modal
        $('#ModalFilter').modal('hide');
    });

    //PAGGING
    $(document).on('click', '#next_button', function() {
        var page_now = parseInt($('#page').val(), 10); // Pastikan nilai diambil sebagai angka
        var next_page = page_now + 1;
        $('#page').val(next_page);
        ShowData();
    });
    $(document).on('click', '#prev_button', function() {
        var page_now = parseInt($('#page').val(), 10); // Pastikan nilai diambil sebagai angka
        var next_page = page_now - 1;
        $('#page').val(next_page);
        ShowData();
    });

    //Modal Detail Potongan
    $('#ModalDetailPotongan').on('show.bs.modal', function (e) {
        var id_anggota= $(e.relatedTarget).data('id');
        var periode_1= $(e.relatedTarget).data('periode_1');
        var periode_2= $(e.relatedTarget).data('periode_2');

        //Loading
        $('#FormDetailPotongan').html('Loading...');

        //Tampikan Dalam Ajax
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/PotonganAnggota/FormDetailPotongan.php',
            data 	    :  {
                id_anggota: id_anggota, 
                periode_1: periode_1, 
                periode_2: periode_2
            },
            success     : function(data){
                $('#FormDetailPotongan').html(data);
            }
        });
    });
    $('#ProsesCetakDetailPotongan').submit(function(event) {
        event.preventDefault(); // Mencegah reload halaman
        
        var formatCetak = $('#tipe_cetak').val(); // Ambil format yang dipilih
        var content = document.getElementById("FormDetailPotongan"); // Ambil elemen yang ingin dicetak

        if (!formatCetak) {
            $('#NotifikasiCetak').html('<div class="alert alert-danger">Silakan pilih format cetak!</div>');
            return;
        }

        // Tampilkan loading
        $('#NotifikasiCetak').html('<div class="alert alert-info">Sedang memproses cetak...</div>');
        $('#ButtonCetak').prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Memproses...');

        setTimeout(() => {
            if (formatCetak === "PDF") {
                html2canvas(content, { scale: 2 }).then(canvas => {
                    var imgData = canvas.toDataURL("image/png");
                    var { jsPDF } = window.jspdf;
                    var doc = new jsPDF("p", "mm", "a4");
                    var imgWidth = 190;
                    var imgHeight = (canvas.height * imgWidth) / canvas.width;

                    doc.addImage(imgData, "PNG", 10, 10, imgWidth, imgHeight);
                    doc.save("Potongan_Anggota.pdf");

                    $('#NotifikasiCetak').html('<div class="alert alert-success">Download PDF berhasil!</div>');
                    resetButton();
                }).catch(error => {
                    $('#NotifikasiCetak').html('<div class="alert alert-danger">Gagal mencetak PDF!</div>');
                    resetButton();
                });
            } else if (formatCetak === "Image") {
                html2canvas(content, { scale: 2 }).then(canvas => {
                    var imgData = canvas.toDataURL("image/png");
                    var link = document.createElement("a");
                    link.href = imgData;
                    link.download = "Potongan_Anggota.png";
                    link.click();

                    $('#NotifikasiCetak').html('<div class="alert alert-success">Download gambar berhasil!</div>');
                    resetButton();
                }).catch(error => {
                    $('#NotifikasiCetak').html('<div class="alert alert-danger">Gagal mencetak gambar!</div>');
                    resetButton();
                });
            } else if (formatCetak === "Direct") {
                var printWindow = window.open("", "", "width=800,height=600");
                printWindow.document.write('<html><head><title>Cetak Nota</title>');
                printWindow.document.write('<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">');
                printWindow.document.write('</head><body>');
                printWindow.document.write(content.innerHTML);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.focus();

                setTimeout(() => {
                    printWindow.print();
                    printWindow.close();
                    $('#NotifikasiCetak').html('<div class="alert alert-success">Pencetakan berhasil!</div>');
                    resetButton();
                }, 1000);
            }
        }, 1000);
    });

    function resetButton() {
        $('#ButtonCetak').prop('disabled', false).html('<i class="bi bi-printer"></i> Cetak');
    }
});