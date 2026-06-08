<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
include 'config.php';

// Atasi error preflight (CORS) dari aplikasi Flutter
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$nama_lengkap = $_POST['nama_lengkap'] ?? '';
$nim = $_POST['nim'] ?? '';
$prodi = $_POST['prodi'] ?? '';
$judul_kegiatan = $_POST['judul_kegiatan'] ?? '';
$no_telp = $_POST['no_telp'] ?? ''; 

// Validasi data kosong
if(empty($nama_lengkap) || empty($nim) || empty($judul_kegiatan)) {
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
    exit;
}

// 1. CEK DOUBLE PENDAFTARAN (Mencegah mahasiswa daftar 2x di acara yang sama)
$cek_query = "SELECT id FROM pendaftaran WHERE nim = ? AND judul_kegiatan = ?";
$stmt_cek = $conn->prepare($cek_query);
$stmt_cek->bind_param("ss", $nim, $judul_kegiatan);
$stmt_cek->execute();
if ($stmt_cek->get_result()->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "NIM $nim sudah mendaftar di kegiatan ini!"]);
    exit;
}

$bukti_url = "";

// 2. PROSES UPLOAD FILE GAMBAR DARI FLUTTER
if (isset($_FILES['bukti_pembayaran']) && $_FILES['bukti_pembayaran']['error'] === UPLOAD_ERR_OK) {
    $file_name = $_FILES['bukti_pembayaran']['name'];
    $file_tmp = $_FILES['bukti_pembayaran']['tmp_name'];
    
    // Memberi nama unik pada file gambar
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $new_file_name = "bukti_" . time() . "_" . rand(1000, 9999) . "." . $file_ext;
    
    // Folder tujuan upload
    $upload_dir = "../uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Buat folder otomatis jika belum ada
    }
    
    if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
        // Simpan URL gambar. Jika BASE_URL tidak jalan, pakai IP langsung.
        $server_url = defined('BASE_URL') ? BASE_URL : "http://192.168.107.138/manajemen_kegiatan";
        $bukti_url = $server_url . "/uploads/" . $new_file_name;
    }
}

// 3. SIMPAN KE DATABASE (Tepat 6 kolom sesuai struktur asli MySQL kamu)
$query = "INSERT INTO pendaftaran (judul_kegiatan, nama_lengkap, nim, prodi, no_telp, bukti_pembayaran) 
          VALUES (?, ?, ?, ?, ?, ?)";
          
$stmt_insert = $conn->prepare($query);
// "ssssss" berarti ada 6 parameter bertipe string (teks)
$stmt_insert->bind_param("ssssss", $judul_kegiatan, $nama_lengkap, $nim, $prodi, $no_telp, $bukti_url);

if($stmt_insert->execute()) {
    echo json_encode(["status" => "success", "message" => "Pendaftaran berhasil!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal daftar: " . $conn->error]);
}
?>