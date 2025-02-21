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
        //id_shu_session
        if(empty($_POST['id_shu_session'])){
            echo '<div class="card-body">';
            echo '  <div class="row">';
            echo '      <div class="col col-md-12">';
            echo '          ID Sessi Bagi Hasil Tidak Boleh Kosong';
            echo '      </div>';
            echo '  </div>';
            echo '</div>';
        }else{
            $id_shu_session=$_POST['id_shu_session'];
            $jml_data=mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM shu_rincian WHERE id_shu_session='$id_shu_session'"));
?>
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-items-center mb-0">
                            <thead class="">
                                <tr>
                                    <th class="text-center">
                                        <b>No</b>
                                    </th>
                                    <th class="text-center">
                                        <b>Nama Anggota</b>
                                    </th>
                                    <th class="text-center">
                                        <b>Jasa Simpanan</b>
                                    </th>
                                    <th class="text-center">
                                        <b>Jasa Pinjaman</b>
                                    </th>
                                    <th class="text-center">
                                        <b>Bagi Hasil</b>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $JumlahJasaSimpanan=0;
                                    $JumlahJasaPinjaman=0;
                                    $JumlahShu=0;
                                    if(empty($jml_data)){
                                        echo '<tr>';
                                        echo '  <td colspan="5">';
                                        echo '      <span class="text-danger">Tidak Ada Rincian Bagi Hasil (SHU) Yang Ditampilkan</span>';
                                        echo '  </td>';
                                        echo '</tr>';
                                    }else{
                                        $no = 1;
                                        $query = mysqli_query($Conn, "SELECT*FROM shu_rincian WHERE id_shu_session='$id_shu_session'");
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_shu_rincian= $data['id_shu_rincian'];
                                            $id_anggota= $data['id_anggota'];
                                            $nama_anggota= $data['nama_anggota'];
                                            $simpanan= $data['simpanan'];
                                            $pinjaman= $data['pinjaman'];
                                            $penjualan= $data['penjualan'];
                                            $jasa_simpanan= $data['jasa_simpanan'];
                                            $jasa_pinjaman= $data['jasa_pinjaman'];
                                            $jasa_penjualan= $data['jasa_penjualan'];
                                            $shu= $data['shu'];
                                            $JumlahJasaSimpanan=$JumlahJasaSimpanan+$jasa_simpanan;
                                            $JumlahJasaPinjaman=$JumlahJasaPinjaman+$jasa_pinjaman;
                                            $JumlahShu=$JumlahShu+$shu;
                                            //Format Rupiah
                                            $simpanan = "" . number_format($simpanan,0,',','.');
                                            $pinjaman = "" . number_format($pinjaman,0,',','.');
                                            $penjualan = "" . number_format($penjualan,0,',','.');
                                            $jasa_simpanan = "Rp " . number_format($jasa_simpanan,0,',','.');
                                            $jasa_pinjaman = "Rp " . number_format($jasa_pinjaman,0,',','.');
                                            $jasa_penjualan = "Rp " . number_format($jasa_penjualan,0,',','.');
                                            $shu = "Rp " . number_format($shu,0,',','.');
                                            //Data Anggota
                                            $QryAnggota = mysqli_query($Conn,"SELECT * FROM anggota WHERE id_anggota='$id_anggota'")or die(mysqli_error($Conn));
                                            $DataAnggota = mysqli_fetch_array($QryAnggota);
                                            $tanggal_masuk= $DataAnggota['tanggal_masuk'];
                                            $strtotime=strtotime($tanggal_masuk);
                                            $TanggalMasuk=date('d/m/Y',$strtotime);
                                        ?>
                                    <tr>
                                        <td class="text-center">
                                            <?php echo "$no" ?>
                                        </td>
                                        <td class="text-left">
                                            <?php echo "$nama_anggota" ?>
                                        </td>
                                        <td align="right">
                                            <?php echo "$jasa_simpanan" ?>
                                        </td>
                                        <td align="right">
                                            <?php echo "$jasa_pinjaman" ?>
                                        </td>
                                        <td align="right">
                                            <?php echo "$shu" ?>
                                        </td>
                                    </tr>
                                    <?php
                                                $no++; 
                                            }
                                        }
                                        $JumlahJasaSimpanan = "Rp " . number_format($JumlahJasaSimpanan,0,',','.');
                                        $JumlahJasaPinjaman = "Rp " . number_format($JumlahJasaPinjaman,0,',','.');
                                        $JumlahShu = "Rp " . number_format($JumlahShu,0,',','.');
                                    ?>
                                    
                            </tbody>
                            <tfooter>
                                <tr>
                                    <td colspan="2">
                                        <b>JUMLAH TOTAL</b><br><small><?php echo "$jml_data Record"; ?></small>
                                    </td>
                                    <td align="right">
                                        <b><?php echo "$JumlahJasaSimpanan"; ?></b>
                                    </td>
                                    <td align="right">
                                        <b><?php echo "$JumlahJasaPinjaman"; ?></b>
                                    </td>
                                    <td align="right">
                                        <b><?php echo "$JumlahShu"; ?></b>
                                    </td>
                                </tr>
                            </tfooter>
                        </table>
                    </div>
                </div>
            </div>
<?php 
        } 
    } 
?>