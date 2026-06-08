<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
// ... kode include config dan lainnya ...
include 'config.php';


// Ambil semua data kegiatan, urutkan dari yang terbaru
$query = mysqli_query($conn, "SELECT * FROM kegiatan ORDER BY id DESC");
$data_kegiatan = [];

while ($row = mysqli_fetch_assoc($query)) {
    $data_kegiatan[] = $row;
}

// Kirimkan datanya dalam format JSON
echo json_encode([
    "status" => "success",
    "data" => $data_kegiatan
]);
?>