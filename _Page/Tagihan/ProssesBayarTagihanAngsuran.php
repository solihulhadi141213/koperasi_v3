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

        if(empty($_POST['id_pinjaman'])){
            $response = [
                "status" => "Error",
                "message" => "Tidak ada data yang dipilih"
            ];
        }else{
            $jumlah_data=count($_POST['id_pinjaman']);
            $jumlah_proses=0;
            $jumlah_error=0;
            foreach ($_POST['id_pinjaman'] as $data){
                //Explode data
                $explode=explode('-',$data);
                $id_pinjaman=$explode[0];

                //Buka Data Pinjaman
                $sql = "SELECT * FROM pinjaman WHERE id_pinjaman = ?";
                $stmt = $Conn->prepare($sql);
                $id = 1;
                $stmt->bind_param("i", $id_pinjaman);
                
                // Eksekusi statement
                $stmt->execute();
                
                // Ambil hasil query
                $result = $stmt->get_result();
                $DataPinjaman = $result->fetch_assoc();
                
                // Simpan hasil ke variabel
                $id_anggota = $DataPinjaman['id_anggota'] ?? 0;
                $uuid_pinjaman = $DataPinjaman['uuid_pinjaman'] ?? null;
                $nip = $DataPinjaman['nip'] ?? null;
                $nama = $DataPinjaman['nama'] ?? null;
                $lembaga = $DataPinjaman['lembaga'] ?? null;
                $ranking = $DataPinjaman['ranking'] ?? 0;
                $tanggal = $DataPinjaman['tanggal'] ?? null;
                $jatuh_tempo = $DataPinjaman['jatuh_tempo'] ?? null;
                $denda = $DataPinjaman['denda'] ?? 0;
                $sistem_denda = $DataPinjaman['sistem_denda'] ?? null;
                $jumlah_pinjaman = $DataPinjaman['jumlah_pinjaman'] ?? 0;
                $persen_jasa = $DataPinjaman['persen_jasa'] ?? 0;
                $persen_jasa = $DataPinjaman['persen_jasa'] ?? 0;
                $rp_jasa = $DataPinjaman['rp_jasa'] ?? 0;
                $angsuran_pokok = $DataPinjaman['angsuran_pokok'] ?? 0;
                $angsuran_total = $DataPinjaman['angsuran_total'] ?? 0;
                $periode_angsuran = $DataPinjaman['periode_angsuran'] ?? 0;
                $status = $DataPinjaman['status'] ?? null;
                
                //Tanggal Sekarang
                $TanggalSekarang=date('Y-m-d');
                
                //Simulasi Pinjaman
                $error_notification=[];
                for ( $i=1; $i<=$periode_angsuran; $i++ ){
                    $GetPeriodePinjaman=date('d/m/Y', strtotime('+'.$i.' month', strtotime($tanggal))); 
                    
                    //Ubah Format Tanggal
                    $GetPeriodePinjaman2=date('Y-m-d', strtotime('+'.$i.' month', strtotime($tanggal))); 
                    
                    //Periode Angsuran Yang Sudah Tertunggak
                    if($TanggalSekarang>$GetPeriodePinjaman2){
                        
                        //Cek Apakah Sudah Ada Angsuran
                        $QryAngsuran = mysqli_query($Conn,"SELECT * FROM pinjaman_angsuran WHERE id_pinjaman='$id_pinjaman' AND tanggal_angsuran='$GetPeriodePinjaman2'")or die(mysqli_error($Conn));
                        $DataAngsuran = mysqli_fetch_array($QryAngsuran);
                        if(empty($DataAngsuran['id_pinjaman_angsuran'])){
                            //Persiapan Pembayaran angsuran
                            $uuid_angsuran=generateRandomString(36);
                            $tanggal_bayar=date('Y-m-d');
                            $keterlambatan=0;
                            $denda=0;
                            
                            //Melakukan input data
                            $EntryAngsuran="INSERT INTO pinjaman_angsuran (
                                uuid_angsuran,
                                id_pinjaman,
                                id_anggota,
                                tanggal_angsuran,
                                tanggal_bayar,
                                keterlambatan,
                                pokok,
                                jasa,
                                denda,
                                jumlah
                            ) VALUES (
                                '$uuid_angsuran',
                                '$id_pinjaman',
                                '$id_anggota',
                                '$GetPeriodePinjaman2',
                                '$tanggal_bayar',
                                '$keterlambatan',
                                '$angsuran_pokok',
                                '$rp_jasa',
                                '$denda',
                                '$angsuran_total'
                            )";
                            $InputAngsuran=mysqli_query($Conn, $EntryAngsuran);
                            if($InputAngsuran){
                                $jumlah_error=$jumlah_error+0;
                            }else{
                                $jumlah_error=$jumlah_error+1;
                            }

                            $error_notification[]=[
                                "uuid_angsuran" => $uuid_angsuran,
                                "id_pinjaman" => $id_pinjaman,
                                "id_anggota" => $id_anggota,
                                "tanggal_angsuran" => $GetPeriodePinjaman2,
                                "tanggal_bayar" => $tanggal_bayar,
                                "keterlambatan" => $keterlambatan,
                                "angsuran_pokok" => $angsuran_pokok,
                                "rp_jasa" => $rp_jasa,
                                "denda" => $denda,
                                "angsuran_total" => $angsuran_total
                            ];
                        }
                    }
                }



                //Jumlah Proses Jika Berhasil
                $jumlah_proses=$jumlah_proses+1;
            }
            //Apabila ada data yang error
            if($jumlah_error!==0){
                $response = [
                    "status" => "Error",
                    "message" => "Terdapat $jumlah_error data angsuran yang gagal disimpan",
                    "error_notification" => $error_notification
                ];
            }else{
                //Apabila jumlah data sama dengan jumlah proses
                if($jumlah_data==$jumlah_proses){
                    $response = [
                        "status" => "Success",
                        "message" => "Pembayaran Tagihan Angsuran Berhasil Disimpan"
                    ];
                }else{
                    $response = [
                        "status" => "Error",
                        "message" => "Terjadi kesalahan pada saat melakukan proses pembayaran tagihan angsuran pinjaman. 
                        Beberapa data mungkin sudah berhasil disimpan. Cek kembali data yang sudah ada atau reload halaman ini."
                    ];
                }
            }
        }
    }

    // Output response
    echo json_encode($response);
?>