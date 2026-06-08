<?php
// Izinkan akses dari Web Admin (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include 'config.php';

// Menangkap data dari FormData
$judul_kegiatan = trim($_POST['judul_kegiatan'] ?? '');
$raw_qr = trim($_POST['nim'] ?? '');

// Pembersihan NIM (mengambil angka saja)
preg_match_all('!\d+!', $raw_qr, $matches);
$nim = isset($matches[0][0]) ? $matches[0][0] : $raw_qr;

if (!empty($judul_kegiatan) && !empty($nim)) {
    // 1. Cek apakah NIM terdaftar di judul kegiatan tersebut
    $query_cek = "SELECT * FROM pendaftaran WHERE judul_kegiatan = ? AND nim = ?";
    $stmt = $conn->prepare($query_cek);
    $stmt->bind_param("ss", $judul_kegiatan, $nim);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // 2. Jika ada, update status menjadi 'Hadir'
        $query_update = "UPDATE pendaftaran SET status_kehadiran = 'Hadir' WHERE judul_kegiatan = ? AND nim = ?";
        $stmt_upd = $conn->prepare($query_update);
        $stmt_upd->bind_param("ss", $judul_kegiatan, $nim);
        
        if ($stmt_upd->execute()) {
            echo json_encode(["status" => "success", "message" => "NIM $nim berhasil Check-in!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal menyimpan ke database."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "NIM $nim tidak terdaftar di kegiatan '$judul_kegiatan'."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap."]);
}
?>