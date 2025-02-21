<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM simpanan_jenis"));
?>
<?php
    if(empty($SessionIdAkses)){
        echo '<div class="row">';
        echo '  <div class="col-md-12 mb-3 text-center">';
        echo '      <small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang</small>';
        echo '  </div>';
        echo '</div>';
    }else{
        if(empty($jml_data)){
            echo '<div class="row">';
            echo '  <div class="col col-md-12 text-center text-danger">';
            echo '      Tidak Ada Data Jenis Simapanan Yang Dapat Ditampilkan';
            echo '  </div>';
            echo '</div>';
        }else{
            $no = 1;
            $query = mysqli_query($Conn, "SELECT*FROM simpanan_jenis ORDER BY id_simpanan_jenis ASC");
            while ($data = mysqli_fetch_array($query)) {
                $id_simpanan_jenis= $data['id_simpanan_jenis'];
                $nama_simpanan= $data['nama_simpanan'];
                $rutin= $data['rutin'];
                $nominal= $data['nominal'];
                $id_perkiraan_debet= $data['id_perkiraan_debet'];
                $id_perkiraan_kredit= $data['id_perkiraan_kredit'];
                //Buka Data Perkiraan
                $nama_perkiraan_debet=GetDetailData($Conn,'akun_perkiraan','id_perkiraan',$id_perkiraan_debet,'nama');
                $nama_perkiraan_kredit=GetDetailData($Conn,'akun_perkiraan','id_perkiraan',$id_perkiraan_kredit,'nama');
                //Label Rutin
                if(empty($rutin)){
                    $LabelRutin='<span class="text text-danger">Tidak</span>';
                }else{
                    $LabelRutin='<span class="text text-success">Ya</span>';
                }
                $NominalRp = "Rp " . number_format($nominal,0,',','.');

?>
            <div class="row mb-3 border-1 border-bottom">
                <div class="col-md-12">
                    <b><?php echo "$no. $nama_simpanan"; ?></b>
                </div>
                <div class="col-md-4">
                    <ul>
                        <li>
                            <small class="credit">
                                ID Simpanan : <code class="text text-grayish"><?php echo $id_simpanan_jenis; ?></code>
                            </small>
                        </li>
                        <li>
                            <small class="credit">
                                Simpanan Wajib : <code class="text text-grayish"><?php echo $LabelRutin; ?></code>
                            </small>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <ul>
                        <li>
                            <small class="credit">
                                Akun Debet : <code class="text text-grayish"><?php echo $nama_perkiraan_debet; ?></code>
                            </small>
                        </li>
                        <li>
                            <small class="credit">
                                Akun Kredit : <code class="text text-grayish"><?php echo $nama_perkiraan_kredit; ?></code>
                            </small>
                        </li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <ul>
                        <li>
                            <small class="credit">
                                Nominal : <code class="text text-grayish"><?php echo $NominalRp; ?></code>
                            </small>
                        </li>
                    </ul>
                </div>
                <div class="col-md-1 text-center">
                    <a class="btn btn-sm btn-outline-dark btn-rounded mb-3" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                        <li class="dropdown-header text-start">
                            <h6>Option</h6>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalDetailJenisSimpanan" data-id="<?php echo "$id_simpanan_jenis"; ?>">
                                <i class="bi bi-info-circle"></i> Detail
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEditJenisSimpanan" data-id="<?php echo "$id_simpanan_jenis"; ?>">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapusJenisSimpanan" data-id="<?php echo "$id_simpanan_jenis"; ?>">
                                <i class="bi bi-x"></i> Hapus
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

<?php
                $no++;
            }
        }
    }
?>
