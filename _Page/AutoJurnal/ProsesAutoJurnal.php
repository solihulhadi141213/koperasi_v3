<?php
    // Muat koneksi, fungsi bersama, dan sesi untuk menyimpan pengaturan auto jurnal.
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");
    $now = date("Y-m-d H:i:s");

    if (empty($SessionIdAkses)) {
        echo '<small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang</small>';
        exit;
    }

    // Pastikan setiap ID akun berasal dari nilai integer positif.
    $requiredAccounts = array(
        "debet_id_simpanan" => "Debet Simpanan Tidak Boleh Kosong",
        "kredit_id_simpanan" => "Kredit Simpanan Tidak Boleh Kosong",
        "debet_id_penarikan" => "Debet Penarikan Tidak Boleh Kosong",
        "kredit_id_penarikan" => "Kredit Penarikan Tidak Boleh Kosong",
        "debet_id_pinjaman" => "Debet Pinjaman Tidak Boleh Kosong",
        "kredit_id_pinjaman" => "Kredit Pinjaman Tidak Boleh Kosong",
        "debet_id_angsuran" => "Debet Angsuran Tidak Boleh Kosong",
        "kredit_id_angsuran" => "Kredit Angsuran Tidak Boleh Kosong"
    );
    $accountIds = array();

    foreach ($requiredAccounts as $field => $message) {
        if (!isset($_POST[$field]) || !is_scalar($_POST[$field]) || trim((string)$_POST[$field]) === "") {
            echo '<small class="text-danger">' . $message . '</small>';
            exit;
        }

        $accountId = filter_var(trim((string)$_POST[$field]), FILTER_VALIDATE_INT);
        if ($accountId === false || $accountId < 1) {
            echo '<small class="text-danger">ID akun yang dipilih tidak valid</small>';
            exit;
        }
        $accountIds[$field] = (int)$accountId;
    }

    // Jalankan SELECT atau perubahan data dengan parameter yang diikat sesuai tipe kolom.
    $runPreparedQuery = function($sql, $types, $parameters, $fetchRows = false) use ($Conn) {
        $statement = $Conn->prepare($sql);
        if ($statement === false) {
            return false;
        }

        $bindParameters = array($types);
        foreach ($parameters as $index => $parameter) {
            $parameters[$index] = $parameter;
            $bindParameters[] = &$parameters[$index];
        }
        if (!call_user_func_array(array($statement, "bind_param"), $bindParameters) || !$statement->execute()) {
            $statement->close();
            return false;
        }

        if (!$fetchRows) {
            $statement->close();
            return true;
        }

        $result = $statement->get_result();
        if ($result === false) {
            $statement->close();
            return false;
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        $statement->close();
        return $rows;
    };

    // Susun empat kategori dan nama kolom formulir yang sesuai.
    $settings = array(
        array("category" => "Simpanan", "debitField" => "debet_id_simpanan", "creditField" => "kredit_id_simpanan"),
        array("category" => "Penarikan", "debitField" => "debet_id_penarikan", "creditField" => "kredit_id_penarikan"),
        array("category" => "Pinjaman", "debitField" => "debet_id_pinjaman", "creditField" => "kredit_id_pinjaman"),
        array("category" => "Angsuran", "debitField" => "debet_id_angsuran", "creditField" => "kredit_id_angsuran")
    );
    $categories = array();
    $selectedAccountIds = array();
    foreach ($settings as $setting) {
        $categories[] = $setting["category"];
        $selectedAccountIds[] = $accountIds[$setting["debitField"]];
        $selectedAccountIds[] = $accountIds[$setting["creditField"]];
    }

    // Ambil nama akun berdasarkan ID integer agar sesuai kolom akun_perkiraan dan auto_jurnal.
    $accountPlaceholders = implode(", ", array_fill(0, count($selectedAccountIds), "?"));
    $accountRows = $runPreparedQuery(
        "SELECT id_perkiraan, nama FROM akun_perkiraan WHERE id_perkiraan IN (" . $accountPlaceholders . ")",
        str_repeat("i", count($selectedAccountIds)),
        $selectedAccountIds,
        true
    );
    if ($accountRows === false) {
        echo '<small class="text-danger">Terjadi kesalahan saat membaca data akun perkiraan</small>';
        exit;
    }

    $accountNames = array();
    foreach ($accountRows as $accountRow) {
        $accountNames[(int)$accountRow["id_perkiraan"]] = (string)($accountRow["nama"] ?? "");
    }
    foreach ($selectedAccountIds as $accountId) {
        if (!array_key_exists($accountId, $accountNames)) {
            echo '<small class="text-danger">Akun yang dipilih tidak ditemukan</small>';
            exit;
        }
    }

    // Hitung keberadaan tiap kategori satu per satu karena skema tidak menetapkan kategori unik.
    $categoryPlaceholders = implode(", ", array_fill(0, count($categories), "?"));
    $categoryRows = $runPreparedQuery(
        "SELECT kategori_transaksi, COUNT(*) AS total FROM auto_jurnal WHERE kategori_transaksi IN (" . $categoryPlaceholders . ") GROUP BY kategori_transaksi",
        str_repeat("s", count($categories)),
        $categories,
        true
    );
    if ($categoryRows === false) {
        echo '<small class="text-danger">Terjadi kesalahan saat membaca pengaturan auto jurnal</small>';
        exit;
    }

    $categoryCounts = array_fill_keys($categories, 0);
    foreach ($categoryRows as $categoryRow) {
        $categoryCounts[$categoryRow["kategori_transaksi"]] = (int)$categoryRow["total"];
    }

    // Simpan setiap kategori secara terpisah; ID akun dikirim sebagai integer dan nama sebagai teks.
    foreach ($settings as $setting) {
        $category = $setting["category"];
        $debitId = $accountIds[$setting["debitField"]];
        $creditId = $accountIds[$setting["creditField"]];
        $debitName = $accountNames[$debitId];
        $creditName = $accountNames[$creditId];

        if ($categoryCounts[$category] === 0) {
            $saved = $runPreparedQuery(
                "INSERT INTO auto_jurnal (kategori_transaksi, debet_id, debet_name, kredit_id, kredit_name) VALUES (?, ?, ?, ?, ?)",
                "sisis",
                array($category, $debitId, $debitName, $creditId, $creditName)
            );
        } else {
            $saved = $runPreparedQuery(
                "UPDATE auto_jurnal SET debet_id = ?, debet_name = ?, kredit_id = ?, kredit_name = ? WHERE kategori_transaksi = ?",
                "isiss",
                array($debitId, $debitName, $creditId, $creditName, $category)
            );
        }

        if (!$saved) {
            echo '<small class="text-danger">Terjadi kesalahan saat menyimpan pengaturan auto jurnal ' . $category . '</small>';
            exit;
        }
    }

    // Tulis aktivitas ke tabel log sesuai skema; InputLog.php tidak tersedia di proyek.
    $hasExistingSetting = false;
    foreach ($categoryCounts as $total) {
        if ($total > 0) {
            $hasExistingSetting = true;
            break;
        }
    }
    $logDescription = $hasExistingSetting ? "Update Auto Jurnal" : "Tambah Auto Jurnal";
    $logSaved = $runPreparedQuery(
        "INSERT INTO log (id_akses, datetime_log, kategori_log, deskripsi_log) VALUES (?, ?, ?, ?)",
        "isss",
        array((int)$SessionIdAkses, $now, "Auto Jurnal", $logDescription)
    );
    if (!$logSaved) {
        echo '<small class="text-warning">Pengaturan tersimpan, tetapi log aktivitas gagal disimpan</small>';
    }

    // ID notifikasi ini digunakan JavaScript untuk mengenali keberhasilan penyimpanan.
    $_SESSION["NotifikasiSwal"] = $hasExistingSetting ? "Update Auto Jurnal Berhasil" : "Tambah Auto Jurnal Berhasil";
    echo '<small class="text-success" id="NotifikasiSimpanAutoJurnalBerhasil">Success</small>';
?>
