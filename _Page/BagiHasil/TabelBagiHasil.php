<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");
    if(empty($SessionIdAkses)){
        echo '<div class="row">';
        echo '  <div class="col-md-12 text-center">';
        echo '      <small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang</small>';
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
            if($ShortBy=="ASC"){
                $NextShort="DESC";
            }else{
                $NextShort="ASC";
            }
        }else{
            $ShortBy="DESC";
            $NextShort="ASC";
        }
        //OrderBy
        if(!empty($_POST['OrderBy'])){
            $OrderBy=$_POST['OrderBy'];
        }else{
            $OrderBy="id_shu_session";
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
                $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM shu_session"));
            }else{
                $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM shu_session WHERE like '%$keyword%' OR periode_hitung1 like '%$keyword%' OR periode_hitung2 like '%$keyword%' OR jasa_modal like '%$keyword%' OR jasa_usaha like '%$keyword%' OR status like '%$keyword%'"));
            }
        }else{
            if(empty($keyword)){
                $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM shu_session"));
            }else{
                $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM shu_session WHERE $keyword_by like '%$keyword%'"));
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
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <td class="text-center">
                                <b>No</b>
                            </td>
                            <td class="text-center">
                                <b>Nama Sesi</b>
                            </td>
                            <td class="text-center">
                                <b>Periode SHU</b>
                            </td>
                            <td class="text-center">
                                <b>Anggota</b>
                            </td>
                            <td class="text-center">
                                <b>SHU (Rp)</b>
                            </td>
                            <td class="text-center">
                                <b>Jurnal</b>
                            </td>
                            <td class="text-center">
                                <b>Status</b>
                            </td>
                            <td class="text-center">
                                <b>Opsi</b>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(empty($jml_data)){
                                echo '<tr>';
                                echo '  <td colspan="8" align="center">';
                                echo '      <span class="text-danger">Belum Ada Data Sesi Bagi Hasil Yang Ditampilkan</span>';
                                echo '  </td>';
                                echo '</tr>';
                            }else{
                                $no = 1+$posisi;
                                //KONDISI PENGATURAN MASING FILTER
                                if(empty($keyword_by)){
                                    if(empty($keyword)){
                                        $query = mysqli_query($Conn, "SELECT*FROM shu_session ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                                    }else{
                                        $query = mysqli_query($Conn, "SELECT*FROM shu_session WHERE sesi_shu like '%$keyword%' OR periode_hitung1 like '%$keyword%' OR periode_hitung2 like '%$keyword%' OR jasa_modal like '%$keyword%' OR jasa_usaha like '%$keyword%' OR status like '%$keyword%' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                                    }
                                }else{
                                    if(empty($keyword)){
                                        $query = mysqli_query($Conn, "SELECT*FROM shu_session ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                                    }else{
                                        $query = mysqli_query($Conn, "SELECT*FROM shu_session WHERE $keyword_by like '%$keyword%' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                                    }
                                }
                                while ($data = mysqli_fetch_array($query)) {
                                    $id_shu_session= $data['id_shu_session'];
                                    $uuid_shu_session= $data['uuid_shu_session'];
                                    $sesi_shu= $data['sesi_shu'];
                                    $periode_hitung1= $data['periode_hitung1'];
                                    $periode_hitung2= $data['periode_hitung2'];
                                    $alokasi_hitung= $data['alokasi_hitung'];
                                    $alokasi_nyata= $data['alokasi_nyata'];
                                    $status= $data['status'];
                                    $alokasi_hitung_rp = "" . number_format($alokasi_hitung,0,',','.');
                                    $alokasi_nyata_rp = "" . number_format($alokasi_nyata,0,',','.');
                                    $strtotime1=strtotime($periode_hitung1);
                                    $strtotime2=strtotime($periode_hitung2);
                                    $periode_hitung1=date('d/m/Y',$strtotime1);
                                    $periode_hitung2=date('d/m/Y',$strtotime2);
                                    //Cek Status Jurnal
                                    $JumlahJurnal = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM jurnal WHERE uuid='$uuid_shu_session'"));
                                    //Jumlah Anggota
                                    $JumlahRincian = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM shu_rincian WHERE id_shu_session='$id_shu_session'"));
                                    $JumlahRincian = "" . number_format($JumlahRincian,0,',','.');
                                    //Label Jurnal Ada/Tidak Ada
                                    if(empty($JumlahJurnal)){
                                        $LabelJurnal='<span class="text-grayish">No Jurnal</span>';
                                    }else{
                                        $LabelJurnal='<span class="text-sucess"> '.$JumlahJurnal.' Record</span>';
                                    }
                                    //Label Status
                                    if($status=="Pending"){
                                        $LabelStatus='<span class="badge badge-warning">Pending</span>';
                                    }else{
                                        $LabelStatus='<span class="badge badge-success">'.$status.'</span>';
                                    }
                                ?>
                            <tr>
                                <td class="text-center text-xs">
                                    <?php echo "$no"; ?>
                                </td>
                                <td class="text-left">
                                    <?php echo "$sesi_shu"; ?>
                                </td>
                                <td class="text-left">
                                    <?php  echo "$periode_hitung1 - $periode_hitung2"; ?>
                                </td>
                                <td class="text-left">
                                    <?php  echo "$JumlahRincian Record"; ?>
                                </td>
                                <td align="right">
                                    <?php  echo "$alokasi_nyata_rp"; ?>
                                </td>
                                <td class="text-left">
                                    <?php  echo "$LabelJurnal"; ?>
                                </td>
                                <td class="text-center text-xs">
                                    <?php  echo "$LabelStatus"; ?>
                                </td>
                                <td align="center">
                                    <a class="btn btn-sm btn-outline-dark btn-rounded" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                                        <li class="dropdown-header text-start">
                                            <h6>Option</h6>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalDetailBagiHasil" data-id="<?php echo "$id_shu_session"; ?>">
                                                <i class="bi bi-info-circle"></i> Detail
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEditBagiHasil" data-id="<?php echo "$id_shu_session"; ?>">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapusBagiHasil" data-id="<?php echo "$id_shu_session"; ?>">
                                                <i class="bi bi-x"></i> Hapus
                                            </a>
                                        </li>
                                    </ul>
                                </td>
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
    </div>
    <div class="row mb-3">
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