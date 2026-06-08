<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
// ... kode include config dan lainnya ...
include 'config.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Username dan password kosong!"]);
    exit;
}

// Cari user berdasarkan username dan password
$query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' AND password = '$password'");

if (mysqli_num_rows($query) > 0) {
    // Jika ketemu, ambil data usernya
    $user = mysqli_fetch_assoc($query);
    
    // Kirim balikan JSON sukses beserta data lengkapnya ke Flutter
    echo json_encode([
        "status" => "success",
        "message" => "Login berhasil",
        "user" => [
            "id" => $user['id'],
            "nama" => $user['nama'],
            "nim" => $user['nim'],
            "prodi" => $user['prodi'],
            "profile_image" => $user['profile_image'],
            "role" => $user['role']
        ]
    ]);
} else {
    // Jika tidak ketemu
    echo json_encode(["status" => "error", "message" => "Username atau password salah!"]);
}
?>