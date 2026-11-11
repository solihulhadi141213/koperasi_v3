<!-- ======= Footer ======= -->
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<!-- Vendor JS Files -->
<script src="node_modules/signature_pad/dist/signature_pad.umd.min.js"></script>
<script src="node_modules/apexcharts/dist/apexcharts.min.js"></script>
<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="node_modules/chart.js/dist/chart.umd.js"></script>
<script src="node_modules/echarts/dist/echarts.min.js"></script>
<script src="node_modules/quill/dist/quill.js"></script>
<script src="node_modules/simple-datatables/dist/umd/simple-datatables.js"></script>
<script src="node_modules/tinymce/tinymce.min.js"></script>
<script src="node_modules/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
<script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="assets/js/jquery.session.js"></script>

<script src="node_modules/html2canvas/dist/html2canvas.min.js"></script>
<script src="node_modules/jspdf/dist/jspdf.umd.min.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        // Format mata uang.
        $( '#kembalian' ).mask('000.000.000.000', {reverse: true});
        $( '#pembayaran' ).mask('000.000.000.000', {reverse: true});
        $( '#jumlah_transaksi' ).mask('000.000.000.000', {reverse: true});
        $( '#jumlah_transaksi_edit' ).mask('000.000.000.000', {reverse: true});
        $( '#pembayaran_edit' ).mask('000.000.000.000', {reverse: true});
        $( '#kembalian_edit' ).mask('000.000.000.000', {reverse: true});
        $( '.format_uang' ).mask('000.000.000.000', {reverse: true});
    })
</script>

<!-- Scan QR -->
<script src="node_modules/jsqr/dist/jsQR.js"></script>
