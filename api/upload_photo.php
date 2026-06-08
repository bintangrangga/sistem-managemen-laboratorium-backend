<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'config.php'; 

// Mengambil URL dari konstanta BASE_URL yang ada di config.php
// Kita tambahkan /uploads/ agar mengarah ke folder penyimpanan foto
$base_url_image = BASE_URL . "/uploads/";

$nim = $_POST['nim'] ?? '';

if (!empty($nim) && isset($_FILES['image'])) {
    
    $file_name = $_FILES['image']['name'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_error = $_FILES['image']['error'];
    
    if ($file_error === 0) {
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $new_file_name = $nim . "_" . date('YmdHis') . "." . $file_ext;
        $file_destination = '../uploads/' . $new_file_name;

        if (move_uploaded_file($file_tmp, $file_destination)) {
            
            $final_image_url = $base_url_image . $new_file_name;

            $query_update = "UPDATE users SET profile_image = ? WHERE nim = ?";
            $stmt = $conn->prepare($query_update);
            $stmt->bind_param("ss", $final_image_url, $nim);
            
            if ($stmt->execute()) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Foto profil berhasil diperbarui",
                    "new_photo_url" => $final_image_url 
                ]);
            } else {
                echo json_encode(["status" => "error", "message" => "Gagal update database: " . $conn->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal memindahkan file ke folder uploads"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Terjadi error pada file"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "NIM atau File Gambar tidak diterima server"]);
}
?>