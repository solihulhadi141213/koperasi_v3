<?php
    // Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    // Time Zone
    date_default_timezone_set('Asia/Jakarta');

    // Time Now Tmp
    $now = date('Y-m-d H:i:s');

    // Inisialisasi respons default
    $response = [
        "status" => "Error",
        "message" => "Belum ada proses yang dilakukan pada sistem."
    ];

    // Validasi sesi login
    if (empty($SessionIdAkses)) {
        $response = [
            "status" => "Error",
            "message" => "Sesi Akses Sudah Berakhir, Silahkan Login Ulang"
        ];
    } else {
        // Validasi Data Tidak Boleh Kosong
        $requiredFields = [
            'id_transaksi_jual_beli' => "ID Transaksi Penjualan Tidak Boleh Kosong!",
            'mode' => "Mode Transaksi Penjualan Tidak Boleh Kosong!",
        ];

        foreach ($requiredFields as $field => $errorMessage) {
            if (empty($_POST[$field])) {
                $response = [
                    "status" => "Error",
                    "message" => $errorMessage
                ];
                echo json_encode($response);
                exit;
            }
        }
        // Buat Variabel
        $id_transaksi_jual_beli = validateAndSanitizeInput($_POST['id_transaksi_jual_beli']);
        $mode = validateAndSanitizeInput($_POST['mode']);
        
        //Proses Hapus
        $Hapus = mysqli_query($Conn, "DELETE FROM transaksi_jual_beli WHERE id_transaksi_jual_beli='$id_transaksi_jual_beli'") or die(mysqli_error($Conn));
        if ($Hapus) {
            //Apabila Berhasil Simpan Log
            $kategori_log="Transaksi Penjualan";
            $deskripsi_log="Hapus Transaksi Penjualan";
            $InputLog=addLog($Conn,$SessionIdAkses,$now,$kategori_log,$deskripsi_log);
            if($InputLog=="Success"){
                $response = [
                    "status" => "Success",
                    "message" => "Hapus Transaksi Berhasil!",
                    "mode" => $mode,
                ];
            }else{
                $response = [
                    "status" => "Error",
                    "message" => "Terjadi kesalahan pada saat menyimpan log aktivitas"
                ];
            }
        }else{
            $response = [
                "status" => "Error",
                "message" => "Terjadi kesalahan pada saat hapus data"
            ];
        }
    }

    // Output response
    echo json_encode($response);
?>
