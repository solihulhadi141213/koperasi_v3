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

    //Validasi Akses
    if(empty($SessionIdAkses)){
        $response = [
            "status" => "Error",
            "message" => "Sesi Akses Sudah Berakhir! Silahkan Login Ulang!"
        ];
    }else{
        // Validasi Data Tidak Boleh Kosong
        $requiredFields = [
            'id_transaksi_jual_beli'    => "ID Transaksi Penjualan Tidak Boleh Kosong!",
            'tanggal'                   => "Tanggal Pembayaran Tidak Boleh Kosong!",
            'jam'                       => "Jam Pembayaran Tidak Boleh Kosong!",
            'kategori'                  => "Kategori Transaksi Tidak Boleh Kosong!",
            'jumlah'                    => "Jumlah/Nominal Pembayaran Tidak Boleh Kosong!",
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
        $tanggal = validateAndSanitizeInput($_POST['tanggal']);
        $jam = validateAndSanitizeInput($_POST['jam']);
        $kategori = validateAndSanitizeInput($_POST['kategori']);
        $jumlah = validateAndSanitizeInput($_POST['jumlah']);
        
        //Tangkap Variabel Yang Tidak Wajib
        if(empty($_POST['id_anggota'])){
            $id_anggota=0;
        }else{
            $id_anggota = validateAndSanitizeInput($_POST['id_anggota']);
        }
        //Cek apakah data transaksi ada dan belum lunas?
        $status=GetDetailData($Conn, 'transaksi_jual_beli', 'id_transaksi_jual_beli', $id_transaksi_jual_beli, 'status');
        if(empty($status)){
            $response = [
                "status" => "Error",
                "message" => "ID Transaksi Tidak Ditemukan Pada Database!"
            ];
        }else{
            if($status=="Lunas"){
                $response = [
                    "status" => "Error",
                    "message" => "Transaksi Tersebut Sudah Lunas! Anda tidak perlu melakukan pembayaran untuk transaksi tersebut"
                ];
            }else{
                
            }
        }
    }
    // Output response
    echo json_encode($response);
?>