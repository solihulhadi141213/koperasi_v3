<?php
    // Cek apakah ada cookies yang diset
    if (count($_COOKIE) > 0) {
        echo "Cookies yang terdeteksi:<br><br>";
        
        // Loop melalui setiap cookie dan tampilkan namanya serta nilainya
        foreach ($_COOKIE as $name => $value) {
            echo "Nama cookie: " . htmlspecialchars($name) . "<br>";
            echo "Nilai cookie: " . htmlspecialchars($value) . "<br><br>";
        }
    } else {
        echo "Tidak ada cookies yang terdeteksi.";
    }
?>