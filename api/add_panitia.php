<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include 'config.php';

$judul_kegiatan = $_POST['judul_kegiatan'] ?? '';
$nama = $_POST['nama'] ?? '';
$jabatan = $_POST['jabatan'] ?? '';

if(!empty($judul_kegiatan) && !empty($nama) && !empty($jabatan)) {
    $query = "INSERT INTO panitia (judul_kegiatan, nama, jabatan) VALUES ('$judul_kegiatan', '$nama', '$jabatan')";
    if(mysqli_query($conn, $query)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal simpan: " . mysqli_error($conn)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
}
?>