<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include 'config.php';

$judul = $_POST['judul'] ?? '';
$url_hapus = $_POST['url_hapus'] ?? '';

if(!empty($judul) && !empty($url_hapus)) {
    // Ambil dokumen saat ini
    $query_get = "SELECT dokumen FROM kegiatan WHERE judul='$judul'";
    $res = mysqli_query($conn, $query_get);
    $row = mysqli_fetch_assoc($res);
    $existing_docs = $row['dokumen'] ?? '';

    if(!empty($existing_docs)) {
        $doc_array = explode(',', $existing_docs);
        
        // Hapus link yang cocok dari dalam array
        $doc_array = array_filter($doc_array, function($url) use ($url_hapus) {
            return trim($url) !== trim($url_hapus);
        });

        // Gabungkan lagi jadi string koma
        $new_docs_string = implode(',', $doc_array);
        $query = "UPDATE kegiatan SET dokumen='$new_docs_string' WHERE judul='$judul'";
        
        if(mysqli_query($conn, $query)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal update database"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Dokumen kosong"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
}
?>