<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include 'config.php';

$judul = $_POST['judul'] ?? '';

if(empty($judul)) {
    echo json_encode(["status" => "error", "message" => "Judul tidak ditemukan!"]);
    exit;
}

$query = "DELETE FROM kegiatan WHERE judul = '$judul'";

if(mysqli_query($conn, $query)) {
    echo json_encode(["status" => "success", "message" => "Berhasil dihapus"]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal menghapus"]);
}
?>