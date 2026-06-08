<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
include 'config.php';

$id = $_POST['id'] ?? '';
$nim = $_POST['nim'] ?? '';

if(!empty($id) && !empty($nim)) {
    // Ubah status menjadi Dipinjam hanya jika milik NIM tersebut (Keamanan)
    $query = "UPDATE peminjaman SET status_pinjam = 'Dipinjam' WHERE id = ? AND nim = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $id, $nim);
    
    if($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Barang berhasil dikonfirmasi diterima"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal mengonfirmasi"]);
    }   
} else {
    echo json_encode(["status" => "error", "message" => "Data tidak valid"]);
}
?>