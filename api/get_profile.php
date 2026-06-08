<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include 'config.php';

$nim = $_GET['nim'] ?? '';

if (empty($nim)) {
    echo json_encode(["status" => "error", "message" => "NIM tidak dikirim"]);
    exit;
}

// Mencari data user berdasarkan NIM
$query = "SELECT nama, username, nim, prodi, profile_image, role FROM users WHERE nim = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $nim);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode([
        "status" => "success",
        "data" => $user
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Pengguna tidak ditemukan"]);
}
?>