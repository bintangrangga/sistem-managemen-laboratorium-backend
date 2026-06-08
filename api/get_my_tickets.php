<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include 'config.php';

$nim = trim($_GET['nim'] ?? '');

if (!empty($nim)) {
    // Kita hapus k.waktu dan k.lokasi agar tidak error jika kolomnya tidak ada di database
    $query = "SELECT p.judul_kegiatan, k.tanggal, k.kategori, k.status_acara 
              FROM pendaftaran p 
              LEFT JOIN kegiatan k ON p.judul_kegiatan = k.judul 
              WHERE p.nim = ?";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nim);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode(["status" => "success", "data" => $data]);
} else {
    echo json_encode(["status" => "error", "message" => "NIM kosong"]);
}
?>