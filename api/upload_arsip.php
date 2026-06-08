<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
// ... kode include config dan lainnya ...
include 'config.php';

$judul = $_POST['judul'] ?? '';

if(isset($_FILES['dokumen']) && !empty($judul)) {
    // Ambil dokumen yang sudah ada di database sebelumnya
    $query_get = "SELECT dokumen FROM kegiatan WHERE judul='$judul'";
    $res = mysqli_query($conn, $query_get);
    $row = mysqli_fetch_assoc($res);
    $existing_docs = $row['dokumen'] ?? '';
    
    // Ubah jadi array (jika ada isinya)
    $doc_array = !empty($existing_docs) ? explode(',', $existing_docs) : [];

    $target_dir = "uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

    $file_count = count($_FILES['dokumen']['name']);
    
    // Looping semua file yang diupload
    for($i = 0; $i < $file_count; $i++) {
        $file_name = time() . "_" . basename($_FILES["dokumen"]["name"][$i]);
        $file_name = str_replace(" ", "_", $file_name); // Hilangkan spasi
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["dokumen"]["tmp_name"][$i], $target_file)) {
            $url_dokumen = "http://192.168.107.152/manajemen_kegiatan/api/" . $target_file;
            $doc_array[] = $url_dokumen; // Masukkan link baru ke dalam array
        }
    }

    // Gabungkan kembali array menjadi string dipisah koma
    $new_docs_string = implode(',', $doc_array);
    $query = "UPDATE kegiatan SET dokumen='$new_docs_string' WHERE judul='$judul'";
    
    if(mysqli_query($conn, $query)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal update database."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "File atau Judul kosong."]);
}
?>