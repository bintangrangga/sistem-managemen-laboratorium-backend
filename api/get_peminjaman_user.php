<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include 'config.php';

$nim = $_GET['nim'] ?? '';

if(empty($nim)) {
    echo json_encode(["status" => "error", "message" => "NIM kosong"]);
    exit;
}

$query = "SELECT * FROM peminjaman WHERE nim = ? ORDER BY id DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $nim);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(["status" => "success", "data" => $data]);
?>