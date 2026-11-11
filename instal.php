<?php
session_start();
mysqli_report(MYSQLI_REPORT_OFF);
define('KOPERASI_INSTALL_MODE', true);
require __DIR__ . DIRECTORY_SEPARATOR . '_Config' . DIRECTORY_SEPARATOR . 'Connection.php';

function installFail($message)
{
    http_response_code(500);
    exit('<!doctype html><html lang="id"><meta charset="utf-8"><title>Instalasi gagal</title><body><p>' .
        htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p></body></html>');
}

function splitSqlStatements($sql)
{
    $statements = array();
    $statement = '';
    $quote = null;
    $lineComment = false;
    $blockComment = false;
    $length = strlen($sql);

    for ($i = 0; $i < $length; $i++) {
        $char = $sql[$i];
        $next = $i + 1 < $length ? $sql[$i + 1] : '';

        if ($lineComment) {
            if ($char === "\n") {
                $lineComment = false;
                $statement .= "\n";
            }
            continue;
        }

        if ($blockComment) {
            if ($char === '*' && $next === '/') {
                $blockComment = false;
                $i++;
            }
            continue;
        }

        if ($quote !== null) {
            $statement .= $char;
            if ($char === '\\' && $i + 1 < $length) {
                $statement .= $sql[++$i];
            } elseif ($char === $quote) {
                if ($next === $quote) {
                    $statement .= $sql[++$i];
                } else {
                    $quote = null;
                }
            }
            continue;
        }

        if ($char === '-' && $next === '-' && ($i + 2 >= $length || ctype_space($sql[$i + 2]))) {
            $lineComment = true;
            $i++;
            continue;
        }
        if ($char === '#') {
            $lineComment = true;
            continue;
        }
        if ($char === '/' && $next === '*') {
            $blockComment = true;
            $i++;
            continue;
        }
        if ($char === "'" || $char === '"' || $char === '`') {
            $quote = $char;
            $statement .= $char;
            continue;
        }
        if ($char === ';') {
            if (trim($statement) !== '') {
                $statements[] = trim($statement);
            }
            $statement = '';
            continue;
        }
        $statement .= $char;
    }

    if (trim($statement) !== '') {
        $statements[] = trim($statement);
    }

    return $statements;
}

function getTableCount($Conn)
{
    $result = $Conn->query('SELECT COUNT(*) FROM `akses`');
    if ($result === false) {
        installFail('Gagal memeriksa tabel akses: ' . $Conn->error);
    }
    $row = $result->fetch_row();
    $result->free();
    return (int) $row[0];
}

if (!preg_match('/\A[A-Za-z0-9_]+\z/', $db)) {
    installFail('Nama database pada konfigurasi tidak valid.');
}

if ($Conn->connect_error) {
    installFail('Gagal terhubung ke server database: ' . $Conn->connect_error);
}

$databaseIdentifier = str_replace('`', '``', $db);
if (!$Conn->query("CREATE DATABASE IF NOT EXISTS `$databaseIdentifier` CHARACTER SET utf8mb4")) {
    installFail('Gagal membuat database: ' . $Conn->error);
}
if (!$Conn->select_db($db)) {
    installFail('Gagal memilih database: ' . $Conn->error);
}
if (!$Conn->set_charset('utf8mb4')) {
    installFail('Gagal mengatur karakter database: ' . $Conn->error);
}

$schemaPath = __DIR__ . DIRECTORY_SEPARATOR . '_Database' . DIRECTORY_SEPARATOR . 'scema.sql';
$schema = file_get_contents($schemaPath);
if ($schema === false) {
    installFail('Berkas _Database/scema.sql tidak dapat dibaca.');
}

$existingTables = array();
$tableResult = $Conn->query('SHOW TABLES');
if ($tableResult === false) {
    installFail('Gagal memeriksa tabel database: ' . $Conn->error);
}
while ($table = $tableResult->fetch_row()) {
    $existingTables[strtolower($table[0])] = true;
}
$tableResult->free();

$newTables = array();
foreach (splitSqlStatements($schema) as $statement) {
    if (preg_match('/\ACREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?`?([A-Za-z0-9_]+)`?/i', $statement, $match)) {
        $tableName = strtolower($match[1]);
        if (!isset($existingTables[$tableName])) {
            if (!$Conn->query($statement)) {
                installFail('Gagal membuat tabel ' . $tableName . ': ' . $Conn->error);
            }
            $existingTables[$tableName] = true;
            $newTables[$tableName] = true;
        }
        continue;
    }

    if (preg_match('/\AINSERT\s+INTO\s+`?(akses_entitas|akses_fitur|akses_referensi)`?/i', $statement, $match)) {
        $tableName = strtolower($match[1]);
        if (isset($newTables[$tableName]) && !$Conn->query($statement)) {
            installFail('Gagal menyiapkan data hak akses: ' . $Conn->error);
        }
        continue;
    }

    if (preg_match('/\AALTER\s+TABLE\s+`?([A-Za-z0-9_]+)`?/i', $statement, $match)) {
        $tableName = strtolower($match[1]);
        if (isset($newTables[$tableName]) && !$Conn->query($statement)) {
            installFail('Gagal menambahkan relasi tabel ' . $tableName . ': ' . $Conn->error);
        }
    }
}

if (!isset($existingTables['akses'])) {
    installFail('Tabel akses tidak ditemukan setelah proses pembuatan tabel.');
}

if (empty($_SESSION['installer_csrf'])) {
    $_SESSION['installer_csrf'] = bin2hex(random_bytes(32));
}

$isInstalled = getTableCount($Conn) > 0;
$errors = array();
$success = false;
$form = array(
    'nama_akses' => '',
    'kontak_akses' => '',
    'email_akses' => ''
);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$isInstalled) {
    $form['nama_akses'] = trim(isset($_POST['nama_akses']) ? $_POST['nama_akses'] : '');
    $form['kontak_akses'] = trim(isset($_POST['kontak_akses']) ? $_POST['kontak_akses'] : '');
    $form['email_akses'] = trim(isset($_POST['email_akses']) ? $_POST['email_akses'] : '');
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $passwordConfirmation = isset($_POST['password_confirmation']) ? $_POST['password_confirmation'] : '';
    $csrfToken = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';

    if (!hash_equals($_SESSION['installer_csrf'], $csrfToken)) {
        $errors[] = 'Sesi formulir tidak valid. Muat ulang halaman dan coba kembali.';
    }
    if ($form['nama_akses'] === '') {
        $errors[] = 'Nama pengguna wajib diisi.';
    }
    if (!preg_match('/\A[0-9]{6,20}\z/', $form['kontak_akses'])) {
        $errors[] = 'Nomor kontak harus terdiri dari 6-20 digit.';
    }
    if (!filter_var($form['email_akses'], FILTER_VALIDATE_EMAIL) || strlen($form['email_akses']) > 225) {
        $errors[] = 'Alamat email tidak valid.';
    }
    if (!preg_match('/\A[A-Za-z0-9]{6,20}\z/', $password)) {
        $errors[] = 'Password harus terdiri dari 6-20 karakter alfanumerik.';
    } elseif ($password !== $passwordConfirmation) {
        $errors[] = 'Konfirmasi password tidak sama.';
    }

    if (count($errors) === 0) {
        $lockName = 'koperasi_install_' . substr(sha1($db), 0, 40);
        $lockResult = $Conn->query("SELECT GET_LOCK('$lockName', 10)");
        if ($lockResult === false) {
            $errors[] = 'Gagal mengunci proses instalasi: ' . $Conn->error;
        } else {
            $lockRow = $lockResult->fetch_row();
            $lockResult->free();

            if ((int) $lockRow[0] !== 1) {
                $errors[] = 'Proses instalasi sedang digunakan. Silakan coba kembali.';
            } elseif (getTableCount($Conn) > 0) {
                $isInstalled = true;
            } else {
                date_default_timezone_set('Asia/Jakarta');
                $now = date('Y-m-d H:i:s');
                if (!$Conn->begin_transaction()) {
                    $errors[] = 'Gagal memulai transaksi: ' . $Conn->error;
                }

                $insertUser = count($errors) === 0 ? $Conn->prepare(
                    'INSERT INTO akses (nama_akses, kontak_akses, email_akses, password, image_akses, akses, datetime_daftar, datetime_update)
                    VALUES (?, ?, ?, ?, NULL, ?, ?, ?)'
                ) : false;
                if ($insertUser === false) {
                    $Conn->rollback();
                    if (count($errors) === 0) {
                        $errors[] = 'Gagal menyiapkan pembuatan pengguna: ' . $Conn->error;
                    }
                } else {
                    $passwordMd5 = md5($password);
                    $role = 'Admin';
                    $insertUser->bind_param(
                        'sssssss',
                        $form['nama_akses'],
                        $form['kontak_akses'],
                        $form['email_akses'],
                        $passwordMd5,
                        $role,
                        $now,
                        $now
                    );

                    if (!$insertUser->execute()) {
                        $Conn->rollback();
                        $errors[] = 'Gagal menyimpan pengguna pertama: ' . $insertUser->error;
                    } else {
                        $adminId = $insertUser->insert_id;
                        $insertPermissions = $Conn->prepare(
                            'INSERT INTO akses_ijin (id_akses, id_akses_fitur, kode, nama, kategori)
                            SELECT ?, fitur.id_akses_fitur, fitur.kode, fitur.nama, fitur.kategori
                            FROM akses_referensi AS referensi
                            INNER JOIN akses_entitas AS entitas
                                ON entitas.uuid_akses_entitas = referensi.uuid_akses_entitas
                            INNER JOIN akses_fitur AS fitur
                                ON fitur.id_akses_fitur = referensi.id_akses_fitur
                            WHERE entitas.akses = ?'
                        );

                        if ($insertPermissions === false) {
                            $Conn->rollback();
                            $errors[] = 'Gagal menyiapkan hak akses Admin: ' . $Conn->error;
                        } else {
                            $insertPermissions->bind_param('is', $adminId, $role);
                            if (!$insertPermissions->execute()) {
                                $Conn->rollback();
                                $errors[] = 'Gagal menyimpan hak akses Admin: ' . $insertPermissions->error;
                            } elseif (!$Conn->commit()) {
                                $Conn->rollback();
                                $errors[] = 'Gagal menyelesaikan transaksi pengguna: ' . $Conn->error;
                            } else {
                                $success = true;
                                $isInstalled = true;
                                $_SESSION['installer_csrf'] = bin2hex(random_bytes(32));
                            }
                            $insertPermissions->close();
                        }
                    }
                    $insertUser->close();
                }
            }

            $Conn->query("SELECT RELEASE_LOCK('$lockName')");
        }
    }
}

function installerEscape($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instalasi Koperasi</title>
    <style>
        body { background: #f3f6fa; color: #263445; font: 16px Arial, sans-serif; margin: 0; }
        main { background: #fff; border-radius: 10px; box-shadow: 0 8px 30px #1c3b671a; margin: 8vh auto; max-width: 520px; padding: 32px; }
        h1 { color: #1c3b67; font-size: 24px; margin-top: 0; }
        label { display: block; font-weight: 600; margin: 16px 0 6px; }
        input { border: 1px solid #cbd4df; border-radius: 5px; box-sizing: border-box; font: inherit; padding: 10px; width: 100%; }
        button { background: #00a88f; border: 0; border-radius: 5px; color: #fff; cursor: pointer; font: inherit; margin-top: 22px; padding: 12px; width: 100%; }
        .message { border-radius: 5px; margin: 14px 0; padding: 12px; }
        .error { background: #fff0f0; color: #a32121; }
        .success { background: #e8f7f1; color: #176345; }
        .note { color: #5b6878; font-size: 14px; line-height: 1.5; }
    </style>
</head>
<body>
<main>
    <h1>Instalasi Koperasi</h1>
    <?php if ($success): ?>
        <div class="message success">Instalasi berhasil. Pengguna pertama telah dibuat sebagai Admin. <a href="Login.php">Lanjut ke halaman login</a>.</div>
        <p class="note">Untuk keamanan, hapus atau ubah nama berkas <strong>instal.php</strong> setelah instalasi selesai.</p>
    <?php elseif ($isInstalled): ?>
        <div class="message success">Instalasi sudah selesai karena pengguna pada tabel akses telah tersedia.</div>
        <p class="note">Untuk keamanan, hapus atau ubah nama berkas <strong>instal.php</strong>.</p>
    <?php else: ?>
        <p class="note">Struktur tabel yang belum tersedia dan data master hak akses dibuat dari <strong>_Database/scema.sql</strong>. Data pengguna dan data operasional dari berkas SQL tidak diimpor. Isi formulir untuk membuat pengguna pertama.</p>
        <?php foreach ($errors as $error): ?>
            <div class="message error"><?php echo installerEscape($error); ?></div>
        <?php endforeach; ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?php echo installerEscape($_SESSION['installer_csrf']); ?>">
            <label for="nama_akses">Nama pengguna</label>
            <input id="nama_akses" name="nama_akses" maxlength="225" required value="<?php echo installerEscape($form['nama_akses']); ?>">

            <label for="kontak_akses">Nomor kontak</label>
            <input id="kontak_akses" name="kontak_akses" inputmode="numeric" minlength="6" maxlength="20" pattern="[0-9]{6,20}" required value="<?php echo installerEscape($form['kontak_akses']); ?>">

            <label for="email_akses">Email</label>
            <input id="email_akses" name="email_akses" type="email" maxlength="225" required value="<?php echo installerEscape($form['email_akses']); ?>">

            <label for="password">Password (6-20 karakter alfanumerik)</label>
            <input id="password" name="password" type="password" minlength="6" maxlength="20" pattern="[A-Za-z0-9]{6,20}" required>

            <label for="password_confirmation">Konfirmasi password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" minlength="6" maxlength="20" pattern="[A-Za-z0-9]{6,20}" required>

            <button type="submit">Buat pengguna pertama</button>
        </form>
    <?php endif; ?>
</main>
</body>
</html>