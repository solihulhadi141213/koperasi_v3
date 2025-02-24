<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

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
        if(empty($_POST['kategori_transaksi'])){
            echo '
                <tr>
                    <td colspan="8" class="text-center text-danger">
                        Kategori Transaksi Tidak Boleh Kosong
                    </td>
                </tr>
            ';
        }else{
            $kategori_transaksi=$_POST['kategori_transaksi'];
            
            $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_transaksi_bulk FROM transaksi_bulk WHERE id_akses='$SessionIdAkses' AND kategori='$kategori_transaksi'"));
            if(empty($jml_data)){
                echo '
                    <tr>
                        <td colspan="7" class="text-center text-danger">
                            Tidak Ada Data Rincian Yang Ditampilkan.
                        </td>
                    </tr>
                ';
            }else{
                $no = 1;
                $jumlah_total=0;
                $query = mysqli_query($Conn, "SELECT*FROM transaksi_bulk WHERE id_akses='$SessionIdAkses' AND kategori='$kategori_transaksi' ORDER BY id_transaksi_bulk DESC");
                while ($data = mysqli_fetch_array($query)) {
                    $id_transaksi_bulk= $data['id_transaksi_bulk'];
                    $id_akses= $data['id_akses'];
                    $kategori= $data['kategori'];
                    $id_barang= $data['id_barang'];
                    $nama_barang= $data['nama_barang'];
                    $satuan= $data['satuan'];
                    $qty= $data['qty'];
                    $harga= $data['harga'];
                    $ppn= $data['ppn'];
                    $diskon= $data['diskon'];
                    $subtotal= $data['subtotal'];
                    $subtotal_rp = "Rp " . number_format($subtotal,0,',','.');
                    $harga_rp = "Rp " . number_format($harga,0,',','.');
                    $jumlah_total=$jumlah_total+$subtotal;
                    //Pembulatan QTY
                    $qty = (float) $qty; // Konversi ke float
                    $qty = ($qty == floor($qty)) ? (int)$qty : $qty;
                    echo '
                        <tr>
                            <td><small>'.$no.'</small></td>
                            <td><small>'.$nama_barang.'</small></td>
                            <td><small>'.$qty.' '.$satuan.'</small></td>
                            <td><small>'.$harga_rp.'</small></td>
                            <td><small>'.$ppn.'</small></td>
                            <td><small>'.$diskon.'</small></td>
                            <td><small>'.$subtotal_rp.'</small></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-floating btn-outline-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                                    <li class="dropdown-header text-start">
                                        <h6>Option</h6>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEditBulk" data-id="'.$id_transaksi_bulk.'">
                                            <i class="bi bi-pencil"></i> Edit Rincian
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapusBulk" data-id="'.$id_transaksi_bulk.'">
                                            <i class="bi bi-trash"></i> Hapus Rincian
                                        </a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    ';
                    $no++;
                }
                $jumlah_total_rp = "Rp " . number_format($jumlah_total,0,',','.');
                echo '
                    <tr>
                        <td colspan="6" class="text-end">
                            <h3>SUBTOTAL</h3>
                        </td>
                        <td colspan="2">
                            <h3>'.$jumlah_total_rp.'</h3>
                        </td>
                    </tr>
                ';
            }
        }
    }
?>
