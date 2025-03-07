<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    //Time Zone
    date_default_timezone_set('Asia/Jakarta');
    //Cek Akses
    if(empty($SessionIdAkses)){
        echo '<div class="row mb-3">';
        echo '  <div class="col col-md-12 text-center">';
        echo '      <code>Sesi Akses Sudah Berakhir. Silahkan Login Ulang!</code>';
        echo '  </div>';
        echo '</div>';
    }else{
        //Keyword_by
        if(!empty($_POST['keyword_by'])){
            $keyword_by=$_POST['keyword_by'];
        }else{
            $keyword_by="";
        }
        //keyword
        if(!empty($_POST['keyword'])){
            $keyword=$_POST['keyword'];
        }else{
            $keyword="";
        }
        //batas
        if(!empty($_POST['batas'])){
            $batas=$_POST['batas'];
        }else{
            $batas="10";
        }
        //ShortBy
        if(!empty($_POST['ShortBy'])){
            $ShortBy=$_POST['ShortBy'];
        }else{
            $ShortBy="DESC";
        }
        //OrderBy
        if(!empty($_POST['OrderBy'])){
            $OrderBy=$_POST['OrderBy'];
        }else{
            $OrderBy="tanggal";
        }
        //Atur Page
        if(!empty($_POST['page'])){
            $page=$_POST['page'];
            $posisi = ( $page - 1 ) * $batas;
        }else{
            $page="1";
            $posisi = 0;
        }
        if(empty($keyword_by)){
            if(empty($keyword)){
                $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT uuid FROM jurnal"));
            }else{
                $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT uuid FROM jurnal WHERE kategori like '%$keyword%' OR tanggal like '%$keyword%' OR kode_perkiraan like '%$keyword%' OR nama_perkiraan like '%$keyword%'"));
            }
        }else{
            if(empty($keyword)){
                $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT uuid FROM jurnal"));
            }else{
                $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT uuid FROM jurnal WHERE $keyword_by like '%$keyword%'"));
            }
        }
        //Mengatur Halaman
        $JmlHalaman = ceil($jml_data/$batas); 
        $prev=$page-1;
        $next=$page+1;
        if($next>$JmlHalaman){
            $next=$page;
        }else{
            $next=$page+1;
        }
        if($prev<"1"){
            $prev="1";
        }else{
            $prev=$page-1;
        }
?>
    <script>
        //ketika klik next
        $('#NextPage').click(function() {
            var page=$('#NextPage').val();
            $('#page').val(page);
            filterAndLoadTable();
        });
        //Ketika klik Previous
        $('#PrevPage').click(function() {
            var page = $('#PrevPage').val();
            $('#page').val(page);
            filterAndLoadTable();
        });
    </script>
    <div class="row">
        <div class="col-md-4">
            <small class="credit">
                Halaman : <code class="text-grayish"><?php echo "$page/$JmlHalaman"; ?></code>
            </small><br>
            <small class="credit">
                Jumlah Data : <code class="text-grayish"><?php echo "$jml_data"; ?></code>
            </small>
        </div>
    </div>
    <div class="row mb-3">
        <div class="table table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <td align="center"><b>No</b></td>
                        <td align="center"><b>Ref</b></td>
                        <td align="center"><b>Kode</b></td>
                        <td align="center"><b>Akun</b></td>
                        <td align="center"><b>Debet</b></td>
                        <td align="center"><b>Kredit</b></td>
                        <td align="center"><b>Opsi</b></td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if(empty($jml_data)){
                            echo '<tr>';
                            echo '  <td colspan="7" class="text-center">';
                            echo '      <code class="text-danger">';
                            echo '          Tidak Ada Data Jurnal Yang Dapat Ditampilkan';
                            echo '      </code>';
                            echo '  </td>';
                            echo '</tr>';
                        }else{
                            $no = 1+$posisi;
                            //KONDISI PENGATURAN MASING FILTER
                            if(empty($keyword_by)){
                                if(empty($keyword)){
                                    $query = mysqli_query($Conn, "SELECT DISTINCT uuid FROM jurnal ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                                }else{
                                    $query = mysqli_query($Conn, "SELECT DISTINCT uuid FROM jurnal WHERE kategori like '%$keyword%' OR tanggal like '%$keyword%' OR kode_perkiraan like '%$keyword%' OR nama_perkiraan like '%$keyword%' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                                }
                            }else{
                                if(empty($keyword)){
                                    $query = mysqli_query($Conn, "SELECT DISTINCT uuid FROM jurnal ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                                }else{
                                    $query = mysqli_query($Conn, "SELECT DISTINCT uuid FROM jurnal WHERE $keyword_by like '%$keyword%' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                                }
                            }
                            while ($data = mysqli_fetch_array($query)) {
                                $uuid= $data['uuid'];
                                //Buka Detail
                                $kategori=GetDetailData($Conn,'jurnal','uuid',$uuid,'kategori');
                                $tanggal_jurnal=GetDetailData($Conn,'jurnal','uuid',$uuid,'tanggal');
                                //Banyaknya Data Jurnal
                                $JumlahRow = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM jurnal WHERE uuid='$uuid' AND kategori='$kategori'"));
                                //Mencari Referensi
                                if($kategori=="Angsuran"){
                                    $tanggal_angsuran=GetDetailData($Conn,'pinjaman_angsuran','uuid_angsuran',$uuid,'tanggal_angsuran');
                                    $id_pinjaman_angsuran=GetDetailData($Conn,'pinjaman_angsuran','uuid_angsuran',$uuid,'id_pinjaman_angsuran');
                                    $strtotime=strtotime($tanggal_angsuran);
                                    $tanggal_angsuran_format=date('d/m/y',$strtotime);
                                    $Referensi="ANG/$id_pinjaman_angsuran/$tanggal_angsuran_format";
                                }else{
                                    if($kategori=="Simpanan"){
                                        $TanggalSimpanan=GetDetailData($Conn,'simpanan','uuid_simpanan',$uuid,'tanggal');
                                        $id_simpanan=GetDetailData($Conn,'simpanan','uuid_simpanan',$uuid,'id_simpanan');
                                        $strtotime=strtotime($TanggalSimpanan);
                                        $TanggalSimpananFormat=date('d/m/y',$strtotime);
                                        $Referensi="SMP/$id_simpanan/$TanggalSimpananFormat";
                                    }else{
                                        if($kategori=="Pinjaman"){
                                            $TanggalPinjaman=GetDetailData($Conn,'pinjaman','uuid_pinjaman',$uuid,'tanggal');
                                            $id_pinjaman=GetDetailData($Conn,'pinjaman','uuid_pinjaman',$uuid,'id_pinjaman');
                                            $strtotime=strtotime($TanggalPinjaman);
                                            $TanggalPinjamanFormat=date('d/m/y',$strtotime);
                                            $Referensi="PNJ/$id_pinjaman/$TanggalPinjamanFormat";
                                        }else{
                                            if($kategori=="Transaksi"){
                                                $TanggalTransaksi=GetDetailData($Conn,'transaksi','uuid_transaksi',$uuid,'tanggal');
                                                $id_transaksi=GetDetailData($Conn,'transaksi','uuid_transaksi',$uuid,'id_transaksi');
                                                $strtotime=strtotime($TanggalTransaksi);
                                                $TanggalTransaksiFormat=date('d/m/y',$strtotime);
                                                $Referensi="TRNS/$id_transaksi/$TanggalTransaksiFormat";
                                            }else{
                                                if($kategori=="Bagi Hasil"){
                                                    $TanggalShu=GetDetailData($Conn,'shu_session','uuid_shu_session',$uuid,'periode_hitung2');
                                                    $id_shu_session=GetDetailData($Conn,'shu_session','uuid_shu_session',$uuid,'id_shu_session');
                                                    $strtotime=strtotime($TanggalShu);
                                                    $TanggalShuFormat=date('d/m/y',$strtotime);
                                                    $Referensi="SHU/$id_shu_session/$TanggalShuFormat";
                                                }else{
                                                    if($kategori=="Penjualan"||$kategori=="Retur Penjualan"){
                                                        $TanggalPenjualan=GetDetailData($Conn,'transaksi_jual_beli','id_transaksi_jual_beli',$uuid,'tanggal');
                                                        $strtotime=strtotime($TanggalPenjualan);
                                                        $TanggalPenjualanFormat=date('d/m/y',$strtotime);
                                                        $Referensi="PNJ/$TanggalPenjualanFormat";
                                                    }else{
                                                        if($kategori=="Pembelian"||$kategori=="Retur Pembelian"){
                                                            $TanggalPembelian=GetDetailData($Conn,'transaksi_jual_beli','id_transaksi_jual_beli',$uuid,'tanggal');
                                                            $strtotime=strtotime($TanggalPembelian);
                                                            $TanggalPembelianFormat=date('d/m/y',$strtotime);
                                                            $Referensi="PMB/$TanggalPembelianFormat";
                                                        }else{
                                                            $Referensi="None";
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                    ?>
                                <tr>
                                    <td align="center" rowspan="<?php echo $JumlahRow; ?>"><?php echo $no; ?></td>
                                    <td align="left" rowspan="<?php echo $JumlahRow; ?>">
                                        <?php echo '<code class="text text-dark">' . $Referensi . '</code>'; ?><br>
                                        <?php echo '<code class="text text-dark">' . $tanggal_jurnal . '</code>'; ?>
                                    </td>
                                    <?php
                                        $QrySub = mysqli_query($Conn, "SELECT * FROM jurnal WHERE uuid='$uuid' ORDER BY d_k ASC");
                                        $first = true;
                                        while ($DataSub = mysqli_fetch_array($QrySub)) {
                                            $id_jurnal = $DataSub['id_jurnal'];
                                            $kode_perkiraan = $DataSub['kode_perkiraan'];
                                            $nama_perkiraan = $DataSub['nama_perkiraan'];
                                            $d_k = $DataSub['d_k'];
                                            $nilai = $DataSub['nilai'];
                                            $NilaiFormat = "" . number_format($nilai,0,',','.');
                                            if (!$first) {
                                                echo '<tr>';
                                            }
                                        ?>
                                            <td align="left">
                                                <?php echo '<code class="text text-grayish">' . $kode_perkiraan . '</code>'; ?>
                                            </td>
                                            <td align="left">
                                                <?php echo '<code class="text text-grayish">' . $nama_perkiraan . '</code>'; ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                if ($d_k == "D") {
                                                    echo '<code class="text text-grayish">' . $NilaiFormat . '</code>';
                                                } else {
                                                    echo '<code class="text text-grayish">-</code>';
                                                }
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                if ($d_k == "K") {
                                                    echo '<code class="text text-grayish">' . $NilaiFormat . '</code>';
                                                } else {
                                                    echo '<code class="text text-grayish">-</code>';
                                                }
                                                ?>
                                            </td>
                                            <td align="right">
                                                <a class="btn btn-sm btn-outline-dark btn-rounded" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                                                    <li class="dropdown-header text-start">
                                                        <h6>Option</h6>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalDetailJurnal" data-id="<?php echo "$id_jurnal"; ?>">
                                                            <i class="bi bi-info-circle"></i> Detail
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEditJurnal" data-id="<?php echo "$id_jurnal"; ?>">
                                                            <i class="bi bi-pencil"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapusJurnal" data-id="<?php echo "$id_jurnal"; ?>">
                                                            <i class="bi bi-x"></i> Hapus
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                    <?php
                                        if (!$first) {
                                            echo '</tr>';
                                        }
                                            $first = false;
                                        } 
                                    ?>
                                </tr>
                    <?php
                                $no++; 
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <div class="btn-group shadow-0" role="group" aria-label="Basic example">
                <button class="btn btn-sm btn-info" id="PrevPage" value="<?php echo $prev;?>">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="btn btn-sm btn-outline-info">
                    <?php echo "$page of $JmlHalaman"; ?>
                </button>
                <button class="btn btn-sm btn-info" id="NextPage" value="<?php echo $next;?>">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
<?php } ?>