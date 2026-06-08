<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include 'config.php';

// Menghitung total kegiatan yang ada
$query_kegiatan = mysqli_query($conn, "SELECT COUNT(id) as total_kegiatan FROM kegiatan");
$data_kegiatan = mysqli_fetch_assoc($query_kegiatan);

// Menghitung total pendaftaran yang masuk
$query_pendaftar = mysqli_query($conn, "SELECT COUNT(id) as total_pendaftar FROM pendaftaran");
$data_pendaftar = mysqli_fetch_assoc($query_pendaftar);

echo json_encode([
    "status" => "success",
    "data" => [
        "total_kegiatan" => $data_kegiatan['total_kegiatan'] ?? 0,
        "total_pendaftar" => $data_pendaftar['total_pendaftar'] ?? 0
    ]
]);
?>