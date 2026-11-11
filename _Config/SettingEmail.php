<?php
    //Inisiasi setting
    $id_setting_email_gateway = 1;

    // Persiapkan query dengan prepared statement
    $stmt = mysqli_prepare($Conn, "SELECT * FROM setting_email_gateway WHERE id_setting_email_gateway = ?");

    if (!$stmt) {
        die("Error dalam persiapan statement: " . mysqli_error($Conn));
    }

    // Bind parameter (i: integer)
    mysqli_stmt_bind_param($stmt, "i", $id_setting_email_gateway);

    // Eksekusi statement
    mysqli_stmt_execute($stmt);

    // Ambil hasil query
    $result = mysqli_stmt_get_result($stmt);
    $DataPaymentsetting = mysqli_fetch_array($result);

    $email_gateway= $DataPaymentsetting['email_gateway'];
    $password_gateway= $DataPaymentsetting['password_gateway'];
    $url_provider= $DataPaymentsetting['url_provider'];
    $port_gateway= $DataPaymentsetting['port_gateway'];
    $nama_pengirim= $DataPaymentsetting['nama_pengirim'];
    $url_service= $DataPaymentsetting['url_service'];

    // Tutup statement
    mysqli_stmt_close($stmt);
?>