<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
// ... kode include config dan lainnya ...
include 'config.php';

// Cek apakah ada request filter berdasarkan judul kegiatan
$judul_kegiatan = $_GET['judul'] ?? '';

if ($judul_kegiatan != '') {
    // Jika ada judul, tampilkan peserta untuk kegiatan itu saja
    $query = mysqli_query($conn, "SELECT * FROM pendaftaran WHERE judul_kegiatan = '$judul_kegiatan' ORDER BY id DESC");
} else {
    // Jika tidak ada, tampilkan semua peserta (untuk dashboard)
    $query = mysqli_query($conn, "SELECT * FROM pendaftaran ORDER BY id DESC");
}

$data = [];
while($row = mysqli_fetch_assoc($query)){
    $data[] = $row;
}

echo json_encode([
    "status" => "success",
    "data" => $data
]);
?>