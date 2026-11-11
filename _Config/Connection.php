<?php
    //Ini adalah halaman untuk melakukan konfigurasi database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "koperasi";
    // The installer needs a server connection before the configured database exists.
    if (defined('KOPERASI_INSTALL_MODE') && KOPERASI_INSTALL_MODE) {
        $Conn = new mysqli($servername, $username, $password);
    } else {
        $Conn = new mysqli($servername, $username, $password, $db);
    }
    // Check connection
    if ($Conn->connect_error) {
        die("Connection failed: " . $Conn->connect_error);
    }