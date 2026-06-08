<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
include 'config.php';

$nim = $_POST['nim'] ?? '';
$nama_kegiatan = $_POST['nama_kegiatan'] ?? '';
$nama_barang = $_POST['nama_barang'] ?? '';
$jumlah = $_POST['jumlah'] ?? '';

if(empty($nim) || empty($nama_kegiatan) || empty($nama_barang) || empty($jumlah)) {
    echo json_encode(["status" => "error", "message" => "Semua data wajib diisi"]);
    exit;
}

$query = "INSERT INTO peminjaman (nim, nama_kegiatan, nama_barang, jumlah) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssi", $nim, $nama_kegiatan, $nama_barang, $jumlah);

if($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Pengajuan berhasil dikirim"]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal mengajukan: " . $conn->error]);
}
?>