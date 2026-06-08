<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
// ... kode include config dan lainnya ...
include 'config.php';

$judul = $_POST['judul'] ?? '';

if(empty($judul) || !isset($_FILES['poster'])) {
    echo json_encode(["status" => "error", "message" => "Data atau gambar tidak ditemukan!"]);
    exit;
}

$target_dir = "uploads/";
$file_name = time() . '_' . basename($_FILES["poster"]["name"]); 
$target_file = $target_dir . $file_name;

$check = getimagesize($_FILES["poster"]["tmp_name"]);
if($check === false) {
    echo json_encode(["status" => "error", "message" => "File yang diunggah bukan gambar."]);
    exit;
}

if (move_uploaded_file($_FILES["poster"]["tmp_name"], $target_file)) {
    $image_url = BASE_URL . "/api/uploads/" . $file_name;

    $query = "UPDATE kegiatan SET gambar = '$image_url' WHERE judul = '$judul'";
    
    if(mysqli_query($conn, $query)) {
        // --- DI SINI TEMPATNYA ---
        // Ganti echo yang lama dengan yang baru ini agar URL ikut terkirim:
        echo json_encode([
            "status" => "success", 
            "message" => "Poster berhasil diunggah!", 
            "url" => $image_url 
        ]);
        // --- SELESAI ---
    } else {
        echo json_encode(["status" => "error", "message" => "Database gagal diperbarui."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Terjadi kesalahan saat memindahkan file."]);
}
?>