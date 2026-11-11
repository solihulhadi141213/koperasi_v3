<?php
    include __DIR__ . '/../../_Config/Connection.php';
    date_default_timezone_set('Asia/Jakarta');
    header('Content-Type: application/json; charset=utf-8');

    $tahun = (int) date('Y');
    $simpanan = array_fill(1, 12, 0);
    $pinjaman = array_fill(1, 12, 0);
    $queries = [
        ['table' => 'simpanan', 'column' => 'jumlah', 'key' => 'simpanan'],
        ['table' => 'pinjaman', 'column' => 'jumlah_pinjaman', 'key' => 'pinjaman']
    ];

    foreach ($queries as $query) {
        $sql = "SELECT MONTH(tanggal) AS bulan, SUM({$query['column']}) AS total
                FROM {$query['table']}
                WHERE YEAR(tanggal) = ?
                GROUP BY MONTH(tanggal)";
        $stmt = $Conn->prepare($sql);
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(['status' => 'Error', 'message' => 'Gagal menyiapkan data grafik.']);
            exit;
        }

        $stmt->bind_param('i', $tahun);
        if (!$stmt->execute()) {
            $stmt->close();
            http_response_code(500);
            echo json_encode(['status' => 'Error', 'message' => 'Gagal mengambil data grafik.']);
            exit;
        }

        $stmt->bind_result($bulan, $total);
        while (true) {
            $fetchResult = $stmt->fetch();
            if ($fetchResult === null) {
                break;
            }
            if ($fetchResult === false) {
                $stmt->close();
                http_response_code(500);
                echo json_encode(['status' => 'Error', 'message' => 'Gagal membaca data grafik.']);
                exit;
            }

            if ($query['key'] === 'simpanan') {
                $simpanan[(int) $bulan] = (float) $total;
            } else {
                $pinjaman[(int) $bulan] = (float) $total;
            }
        }
        $stmt->close();
    }

    $categories = [];
    for ($bulan = 1; $bulan <= 12; $bulan++) {
        $categories[] = date('F', mktime(0, 0, 0, $bulan, 1, $tahun));
    }

    echo json_encode([
        'status' => 'Success',
        'categories' => $categories,
        'simpanan' => array_values($simpanan),
        'pinjaman' => array_values($pinjaman)
    ]);
?>
