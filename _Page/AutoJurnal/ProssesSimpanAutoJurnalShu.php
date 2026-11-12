<?php
    // Muat koneksi, fungsi bersama, dan sesi untuk menyimpan konfigurasi SHU.
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    if (empty($SessionIdAkses)) {
        echo '<div class="alert alert-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang</div>';
        exit;
    }

    // Pastikan setiap pilihan akun berisi ID integer positif sebelum diproses.
    $requiredAccounts = array(
        "debet_pembagian" => "Debet Pembagian SHU Tidak Boleh Kosong",
        "kredit_pembagian" => "Kredit Pembagian SHU Tidak Boleh Kosong",
        "debet_pembayaran" => "Debet Pembayaran SHU Tidak Boleh Kosong",
        "kredit_pembayaran" => "Kredit Pembayaran SHU Tidak Boleh Kosong"
    );
    $accountIds = array();

    foreach ($requiredAccounts as $field => $message) {
        if (!isset($_POST[$field]) || !is_scalar($_POST[$field]) || trim((string)$_POST[$field]) === "") {
            echo '<div class="alert alert-danger">' . $message . '</div>';
            exit;
        }

        $accountId = filter_var(trim((string)$_POST[$field]), FILTER_VALIDATE_INT);
        if ($accountId === false || $accountId < 1) {
            echo '<div class="alert alert-danger">ID akun yang dipilih tidak valid</div>';
            exit;
        }
        $accountIds[$field] = (int)$accountId;
    }

    // Jalankan INSERT atau UPDATE dengan seluruh nilai terikat sebagai parameter.
    $executePreparedStatement = function($sql, $types, $parameters) use ($Conn) {
        $statement = $Conn->prepare($sql);
        if ($statement === false) {
            return false;
        }

        $bindParameters = array($types);
        foreach ($parameters as $index => $parameter) {
            $parameters[$index] = $parameter;
            $bindParameters[] = &$parameters[$index];
        }

        if (!call_user_func_array(array($statement, "bind_param"), $bindParameters)) {
            $statement->close();
            return false;
        }
        if (!$statement->execute()) {
            $statement->close();
            return false;
        }

        $statement->close();
        return true;
    };

    // Simpan pilihan untuk satu komponen SHU, membuat data jika belum tersedia.
    $saveAutoJurnalShu = function($component, $settingId, $debitAccountId, $creditAccountId) use ($executePreparedStatement) {
        if (empty($settingId)) {
            return $executePreparedStatement(
                "INSERT INTO setting_autojurnal_shu (komponen, id_perkiraan_debet, id_perkiraan_kredit) VALUES (?, ?, ?)",
                "sii",
                array($component, $debitAccountId, $creditAccountId)
            );
        }

        return $executePreparedStatement(
            "UPDATE setting_autojurnal_shu SET id_perkiraan_debet = ?, id_perkiraan_kredit = ? WHERE id_setting_autojurnal_shu = ?",
            "iii",
            array($debitAccountId, $creditAccountId, (int)$settingId)
        );
    };

    // Ambil ID pengaturan yang ada agar masing-masing komponen dipilih INSERT atau UPDATE.
    $pembagianShu = getAutoJurnalSHU($Conn, "Pembagian");
    $pembayaranShu = getAutoJurnalSHU($Conn, "Pembayaran");
    $idPembagian = $pembagianShu["id_setting_autojurnal_shu"] ?? null;
    $idPembayaran = $pembayaranShu["id_setting_autojurnal_shu"] ?? null;

    if (!$saveAutoJurnalShu(
        "Pembagian",
        $idPembagian,
        $accountIds["debet_pembagian"],
        $accountIds["kredit_pembagian"]
    )) {
        echo '<div class="alert alert-danger">Terjadi kesalahan saat menyimpan pengaturan Auto Jurnal Pembagian SHU</div>';
        exit;
    }

    if (!$saveAutoJurnalShu(
        "Pembayaran",
        $idPembayaran,
        $accountIds["debet_pembayaran"],
        $accountIds["kredit_pembayaran"]
    )) {
        echo '<div class="alert alert-danger">Terjadi kesalahan saat menyimpan pengaturan Auto Jurnal Pembayaran SHU</div>';
        exit;
    }

    // ID notifikasi ini digunakan JavaScript untuk mengenali keberhasilan penyimpanan.
    echo '
        <div class="alert alert-success">
            <small id="NotifikasiSimpanAutoJurnalShuBerhasil">Berhasil</small>
        </div>
    ';
?>
