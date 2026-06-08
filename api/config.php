<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "manajemen_kegiatan";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi ke database failed: " . mysqli_connect_error());
}

define('BASE_URL', 'http://10.21.3.195/manajemen_kegiatan');