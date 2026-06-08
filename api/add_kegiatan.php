<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
// ... kode include config dan lainnya ...
include 'config.php';

$judul = $_POST['judul'] ?? '';
$tanggal = $_POST['tanggal'] ?? '';

// Karena form di HTML-mu hanya ada nama dan tanggal, 
// kita buat isi otomatis (default) untuk kolom lainnya
$deskripsi = "Deskripsi kegiatan belum ditambahkan oleh Admin.";
$waktu = "08:00 - Selesai WIB";
$lokasi = "Belum ditentukan";
$kategori = $_POST['kategori'];
$gambar = "https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=600&auto=format&fit=crop"; 

if(empty($judul) || empty($tanggal)) {
    echo json_encode(["status" => "error", "message" => "Nama kegiatan dan tanggal wajib diisi!"]);
    exit;
}

// Masukkan ke database
$query = "INSERT INTO kegiatan (judul, deskripsi, tanggal, waktu, lokasi, kategori, gambar) 
          VALUES ('$judul', '$deskripsi', '$tanggal', '$waktu', '$lokasi', '$kategori', '$gambar')";

if (mysqli_query($conn, $query)) {
    echo json_encode(["status" => "success", "message" => "Kegiatan berhasil ditambahkan"]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal menyimpan: " . mysqli_error($conn)]);
}
?>