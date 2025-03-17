<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    //Hitung Jumlah Jenis Simpanan
    $jumlah_jenis_simpanan=mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM simpanan_jenis"));
    $colspan=$jumlah_jenis_simpanan+5;

    //inisiasi Variabe;
    $JmlHalaman=0;
    $page=1;
    if(empty($SessionIdAkses)){
        echo '
            <tr>
                <td colspan="'.$colspan.'" class="text-center text-danger">
                    Sesi Akses Sudah Berakhir! Silahkan Login Ulang
                </td>
            </tr>
        ';
    }else{
        if(empty($_POST['periode_1'])){
            echo '
                <tr>
                    <td colspan="'.$colspan.'" class="text-center text-danger">
                        Periode Awal Data Tidak Boleh Kosong!
                    </td>
                </tr>
            ';
        }else{
            if(empty($_POST['periode_2'])){
                echo '
                    <tr>
                        <td colspan="'.$colspan.'" class="text-center text-danger">
                            Periode Akhir Data Tidak Boleh Kosong!
                        </td>
                    </tr>
                ';
            }else{
                $periode_1=$_POST['periode_1'];
                $periode_2=$_POST['periode_2'];
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
                    $OrderBy="id_anggota";
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
                        $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_anggota FROM anggota"));
                    }else{
                        $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_anggota FROM anggota WHERE nip like '%$keyword%' OR nama like '%$keyword%' OR status like '%$keyword%'"));
                    }
                }else{
                    if(empty($keyword)){
                        $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_anggota FROM anggota"));
                    }else{
                        $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_anggota FROM anggota WHERE $keyword_by like '%$keyword%'"));
                    }
                }
                if(empty($jml_data)){
                    echo '
                        <tr>
                            <td colspan="'.$colspan.'" class="text-center text-danger">
                                Tidak Ada Data Yang Ditampilkan.
                            </td>
                        </tr>
                    ';
                }else{
                    $no = 1+$posisi;
                    //KONDISI PENGATURAN MASING FILTER
                    if(empty($keyword_by)){
                        if(empty($keyword)){
                            $query = mysqli_query($Conn, "SELECT*FROM anggota ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                        }else{
                            $query = mysqli_query($Conn, "SELECT*FROM anggota WHERE nip like '%$keyword%' OR nama like '%$keyword%' OR status like '%$keyword%' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                        }
                    }else{
                        if(empty($keyword)){
                            $query = mysqli_query($Conn, "SELECT*FROM anggota ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                        }else{
                            $query = mysqli_query($Conn, "SELECT*FROM anggota WHERE $keyword_by like '%$keyword%' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                        }
                    }
                    while ($data = mysqli_fetch_array($query)) {
                        $id_anggota= $data['id_anggota'];
                        $nama= $data['nama'];
                        $nip= $data['nip'];
                        $status_anggota= $data['status'];
                        if($status_anggota=="Aktif"){
                            $label_status_anggota='<span class="badge badge-success text-primary">'.$nama.'</span>';
                        }else{
                            $label_status_anggota='<span class="badge badge-danger text-primary">'.$nama.'</span>';
                        }
                        //Menghitung Jumlah Angsuran

                        echo '<tr>';
                        echo '  <td><small>'.$no.'</small></td>';
                        echo '  
                            <td>
                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalDetailPotongan" data-id="'.$id_anggota.'" data-periode_1="'.$periode_1.'" data-periode_2="'.$periode_2.'">
                                    '.$label_status_anggota.'
                                </a>
                            </td>
                        ';
                        echo '  
                            <td>
                                <small>
                                    <small class="text text-muted">'.$nip.'</small>
                                </small>
                            </td>
                        ';
                        $QryPinjaman = mysqli_query($Conn, "SELECT id_pinjaman_jenis FROM pinjaman_jenis ORDER BY id_pinjaman_jenis ASC");
                        while ($DataPinjaman = mysqli_fetch_array($QryPinjaman)) {
                            $id_pinjaman_jenis= $DataPinjaman['id_pinjaman_jenis'];
                            $jumlah_total_potongan_angsuran=0;
                            //Buka Detail Pinjaman
                            $QrySesiPinjaman = $Conn->prepare("SELECT * FROM pinjaman WHERE id_pinjaman_jenis = ? AND id_anggota = ? AND status!='Lunas'");
                            $QrySesiPinjaman->bind_param("ii", $id_pinjaman_jenis, $id_anggota);
                            if (!$QrySesiPinjaman->execute()) {
                                $error=$Conn->error;
                                echo '<td><small class="text-danger">'.$error.'</small></td>';
                                $jumlah_total_potongan_angsuran=$jumlah_total_potongan_angsuran+0;
                            }else{
                                $ResultSesiPinjaman = $QrySesiPinjaman->get_result();
                                $DataSesiPinjaman = $ResultSesiPinjaman->fetch_assoc();
                                $QrySesiPinjaman->close();

                                //Buat Variabel Pinjaman
                                if(empty($DataSesiPinjaman['angsuran_total'])){
                                    $angsuran_total=0;
                                }else{
                                    $angsuran_total=$DataSesiPinjaman['angsuran_total'];
                                }
                                $jumlah_total_potongan_angsuran=$jumlah_total_potongan_angsuran+$angsuran_total;
                                $angsuran_total_rp = "Rp " . number_format($angsuran_total,0,',','.');
                                echo '<td class="text-right"><small class="text-muted">'.$angsuran_total_rp.'</small></td>';
                            }
                        }
                        //Menghitung Jumlah Pembelian Anggota Yang Belum Lunas
                        $SumPenjualan = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(total) AS total FROM transaksi_jual_beli WHERE id_anggota='$id_anggota' AND kategori='Penjualan' AND status='Kredit' AND tanggal >='$periode_1' AND tanggal<='$periode_2'"));
                        $JumlahNomiinalPenjualan = $SumPenjualan['total'];
                        $SumPenjualanRetur = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(total) AS total FROM transaksi_jual_beli WHERE id_anggota='$id_anggota' AND kategori='Retur Penjualan' AND status='Kredit' AND tanggal >='$periode_1' AND tanggal<='$periode_2'"));
                        $JumlahNomiinalPenjualanRetur = $SumPenjualanRetur['total'];
                        //Hitung Jumlah
                        $jumlah_penjualan_anggota=$JumlahNomiinalPenjualan-$JumlahNomiinalPenjualanRetur;
                        $jumlah_penjualan_anggota_rp = "Rp " . number_format($jumlah_penjualan_anggota,0,',','.');

                        //Akumulasikan
                        $jumlah_total_potongan_anggota=$jumlah_penjualan_anggota+$jumlah_total_potongan_angsuran;
                        $jumlah_total_potongan_anggota_rp = "Rp " . number_format($jumlah_total_potongan_anggota,0,',','.');
                        echo '<td><small>'.$jumlah_penjualan_anggota_rp.'</small></td>';
                        echo '<td><small>'.$jumlah_total_potongan_anggota_rp.'</small></td>';
                        echo '</tr>';
                        $no++;
                    }
                    $JmlHalaman = ceil($jml_data/$batas); 
                }
            }
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
