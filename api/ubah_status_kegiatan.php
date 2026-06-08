<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
include 'config.php';

$judul = $_POST['judul'] ?? '';
$status_baru = $_POST['status_baru'] ?? '';

if (!empty($judul) && !empty($status_baru)) {
    $query = "UPDATE kegiatan SET status_acara = ? WHERE judul = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $status_baru, $judul);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Status berhasil diubah"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal merubah status"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
}
?>