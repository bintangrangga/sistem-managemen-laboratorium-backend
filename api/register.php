<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");
include 'config.php';

// Atasi error preflight (CORS) dari Flutter
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// 1. Menangkap data POST dari Flutter (Sekarang NIM ikut ditangkap)
$nama = $_POST['nama'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? 'user';
$nim = $_POST['nim'] ?? ''; // <--- BARIS BARU UNTUK MENANGKAP NIM

// Validasi jika ada data yang kosong 
if (empty($nama) || empty($username) || empty($password) || empty($nim)) {
    echo json_encode(["status" => "error", "message" => "Semua kolom wajib diisi dari server!"]);
    exit;
}

// 2. Mengecek apakah username sudah pernah didaftarkan (Menggunakan Prepared Statement agar aman)
$cek_query = "SELECT id FROM users WHERE username = ?";
$stmt_cek = $conn->prepare($cek_query);
$stmt_cek->bind_param("s", $username);
$stmt_cek->execute();
if ($stmt_cek->get_result()->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Username '$username' sudah terdaftar!"]);
    exit;
}

// 3. Menyiapkan nilai default untuk profil yang belum lengkap
$prodi = 'Belum Diatur'; // Prodi belum ada di form Flutter, jadi kita beri default

// Menggunakan UI-Avatars untuk membuat foto profil inisial otomatis
$profile_image = 'https://ui-avatars.com/api/?name=' . urlencode($nama) . '&background=random';

// 4. Menyimpan ke database MySQL (Menggunakan Prepared Statement)
$query = "INSERT INTO users (nama, username, password, role, nim, prodi, profile_image) 
          VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt_insert = $conn->prepare($query);
// "sssssss" berarti ada 7 parameter berbentuk string (teks)
$stmt_insert->bind_param("sssssss", $nama, $username, $password, $role, $nim, $prodi, $profile_image);

if ($stmt_insert->execute()) {
    // Respon sukses
    echo json_encode([
        "status" => "success",
        "message" => "Registrasi berhasil! Silakan Login."
    ]);
} else {
    // Respon error database
    echo json_encode([
        "status" => "error",
        "message" => "Gagal mendaftar: " . $conn->error
    ]);
}
