<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json"); // Tambahan wajib agar tidak dibaca sebagai teks biasa
include 'config.php';

$id = $_POST['id'] ?? '';

if(!empty($id)) {
    // Pastikan variabel $id aman sebelum dimasukkan ke query
    $id = mysqli_real_escape_string($conn, $id);
    
    $query = "DELETE FROM panitia WHERE id = '$id'";
    if(mysqli_query($conn, $query)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menghapus: " . mysqli_error($conn)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ID tidak ditemukan"]);
}
?>