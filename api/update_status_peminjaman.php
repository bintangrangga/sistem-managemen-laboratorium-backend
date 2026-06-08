<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
include 'config.php';

$id = $_POST['id'] ?? '';
$status_baru = $_POST['status_baru'] ?? ''; // 'Disetujui' atau 'Ditolak'

if(!empty($id) && !empty($status_baru)) {
    $query = "UPDATE peminjaman SET status_pinjam = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status_baru, $id);
    
    if($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Status berhasil diubah"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal mengubah status"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
}
?>