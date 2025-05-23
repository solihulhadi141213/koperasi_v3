<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    //inisiasi Variabe;
    $JmlHalaman=0;
    $page=1;
    if(empty($SessionIdAkses)){
        echo '
            <tr>
                <td colspan="8" class="text-center text-danger">
                    Sesi Akses Sudah Berakhir! Silahkan Login Ulang
                </td>
            </tr>
        ';
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
                $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_transaksi_jual_beli FROM transaksi_jual_beli WHERE kategori='Pembelian' OR kategori='Retur Pembelian'"));
            }else{
                $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_transaksi_jual_beli FROM transaksi_jual_beli WHERE (kategori='Pembelian' OR kategori='Retur Pembelian') AND (tanggal like '%$keyword%' OR status like '%$keyword%')"));
            }
        }else{
            if(empty($keyword)){
                $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_transaksi_jual_beli FROM transaksi_jual_beli WHERE kategori='Pembelian' OR kategori='Retur Pembelian'"));
            }else{
                $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_transaksi_jual_beli FROM transaksi_jual_beli WHERE (kategori='Pembelian' OR kategori='Retur Pembelian') AND ($keyword_by like '%$keyword%')"));
            }
        }
        if(empty($jml_data)){
            echo '
                <tr>
                    <td colspan="8" class="text-center text-danger">
                        Tidak Ada Data Yang Ditampilkan.
                    </td>
                </tr>
            ';
        }else{
            $no = 1+$posisi;
            //KONDISI PENGATURAN MASING FILTER
            if(empty($keyword_by)){
                if(empty($keyword)){
                    $query = mysqli_query($Conn, "SELECT*FROM transaksi_jual_beli WHERE kategori='Pembelian' OR kategori='Retur Pembelian' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                }else{
                    $query = mysqli_query($Conn, "SELECT*FROM transaksi_jual_beli WHERE (kategori='Pembelian' OR kategori='Retur Pembelian') AND (tanggal like '%$keyword%' OR status like '%$keyword%') ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                }
            }else{
                if(empty($keyword)){
                    $query = mysqli_query($Conn, "SELECT*FROM transaksi_jual_beli WHERE kategori='Pembelian' OR kategori='Retur Pembelian' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                }else{
                    $query = mysqli_query($Conn, "SELECT*FROM transaksi_jual_beli WHERE (kategori='Pembelian' OR kategori='Retur Pembelian') AND ($keyword_by like '%$keyword%') ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                }
            }
            while ($data = mysqli_fetch_array($query)) {
                $id_transaksi_jual_beli= $data['id_transaksi_jual_beli'];
                $kategori= $data['kategori'];
                $tanggal= $data['tanggal'];
                $total= $data['total'];
                $status= $data['status'];
                $total_rp = "Rp " . number_format($total,0,',','.');
                //Routing Pembelian
                if($kategori=="Pembelian"){
                    $label_kategori='<span class="text text-success">PEMBELIAN</span>';
                }else{
                    $label_kategori='<span class="text text-muted">RETUR</span>';
                }
                //Buka nama supplier dari tabel anggota
                if(empty($data['id_supplier'])){
                    $id_supplier= "";
                    $nama_supplier="-";
                }else{
                    $id_supplier= $data['id_supplier'];
                    $nama_supplier=GetDetailData($Conn, 'supplier', 'id_supplier', $id_supplier, 'nama_supplier');
                }
                
                //Routing status
                if($status=="Lunas"){
                    $label_status='<span class="badge badge-success">Lunas</span>';
                }else{
                    if($kategori=="Pembelian"){
                        $label_status='<span class="badge badge-danger">Utang</span>';
                    }else{
                        $label_status='<span class="badge badge-warning">Piutang</span>';
                    }
                }
                //Format tanggal
                $strtotime=strtotime($tanggal);
                $TanggalTransaksi=date('d/m/Y H:i', $strtotime);

                //Hitung Jurnal
                $jml_jurnal = mysqli_num_rows(mysqli_query($Conn, "SELECT id_jurnal FROM jurnal WHERE id_transaksi_jual_beli='$id_transaksi_jual_beli'"));
                if(empty($jml_jurnal)){
                    $label_jurnal='<span class="badge badge-light">NULL</span>';
                }else{
                    $label_jurnal='<span class="badge badge-info">'.$jml_jurnal.' Record</span>';
                }

                echo '
                    <tr>
                        <td><small>'.$no.'</small></td>
                        <td>
                            <a href="javascript:void(0);" class="text text-decoration-underline" data-bs-toggle="modal" data-bs-target="#ModalDetail" data-id="'.$id_transaksi_jual_beli.'">
                                <small>'.$TanggalTransaksi.'</small>
                            </a>
                        </td>
                        <td><small>'.$label_kategori.'</small></td>
                        <td>
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalListSupplierEdit" data-id="'.$id_transaksi_jual_beli.'" data-mode="List">
                                <small class="text text-muted">'.$nama_supplier.'</small>
                            </a>
                        </td>
                        <td><small>'.$total_rp.'</small></td>
                        <td><small>'.$label_status.'</small></td>
                        <td><small>'.$label_jurnal.'</small></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-floating btn-outline-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                                <li class="dropdown-header text-start">
                                    <h6>Option</h6>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalDetail" data-id="'.$id_transaksi_jual_beli.'">
                                        <i class="bi bi-info-circle"></i> Detail Transaksi
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalCetak" data-id="'.$id_transaksi_jual_beli.'">
                                        <i class="bi bi-printer"></i> Cetak
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEdit" data-id="'.$id_transaksi_jual_beli.'" data-mode="List">
                                        <i class="bi bi-pencil"></i> Edit Transaksi
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalListSupplierEdit" data-id="'.$id_transaksi_jual_beli.'" data-mode="List">
                                        <i class="bi bi-person"></i> Ganti Supplier
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapus" data-id="'.$id_transaksi_jual_beli.'" data-mode="List">
                                        <i class="bi bi-trash"></i> Hapus Transaksi
                                    </a>
                                </li>
                            </ul>
                        </td>
                    </tr>
                ';
                $no++;
            }
            $JmlHalaman = ceil($jml_data/$batas); 
        }
    }
?>

<script>
    //Creat Javascript Variabel
    var page_count=<?php echo $JmlHalaman; ?>;
    var curent_page=<?php echo $page; ?>;
    
    //Put Into Pagging Element
    $('#page_info').html('Page '+curent_page+' Of '+page_count+'');
    
    //Set Pagging Button
    if(curent_page==1){
        $('#prev_button').prop('disabled', true);
    }else{
        $('#prev_button').prop('disabled', false);
    }
    if(page_count<=curent_page){
        $('#next_button').prop('disabled', true);
    }else{
        $('#next_button').prop('disabled', false);
    }
</script>
