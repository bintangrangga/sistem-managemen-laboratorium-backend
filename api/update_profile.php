<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
include 'config.php';

// Menangkap data dari Flutter
$old_nim = $_POST['old_nim'] ?? ''; // NIM lama untuk patokan mencari data
$new_nim = $_POST['new_nim'] ?? ''; // NIM baru jika user mengeditnya
$nama = $_POST['nama'] ?? '';
$prodi = $_POST['prodi'] ?? '';

if(empty($old_nim) || empty($new_nim) || empty($nama)) {
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
    exit;
}

// Update data di database MySQL
$query = "UPDATE users SET nama = ?, nim = ?, prodi = ? WHERE nim = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $nama, $new_nim, $prodi, $old_nim);

if($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Profil berhasil diperbarui"]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal memperbarui profil: " . $conn->error]);
}
?>