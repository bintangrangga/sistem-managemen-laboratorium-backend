# Sistem Manajemen Laboratorium - Backend

[![PHP Version](https://img.shields.io/badge/php-%5E8.0-blue.svg)](https://apps.internal.umsida.ac.id)
[![Database](https://img.shields.io/badge/database-MySQL-orange.svg)]()
[![License](https://img.shields.io/badge/license-MIT-green.svg)]()

Backend system berbasis PHP yang dirancang khusus untuk mengelola operasional, inventarisasi perangkat, peminjaman alat, serta manajemen kegiatan laboratorium (seperti praktikum, diklat, dan pelatihan) secara terpusat dan efisien.

---

## 📌 Fitur Utama

Aplikasi backend ini menyediakan RESTful API / layanan pemrosesan logika data untuk mendukung fungsionalitas berikut:

### 1. Manajemen Inventaris & Alat Laboratorium

- **Pencatatan Aset:** Pendataan barang, komputer, modul praktikum, dan komponen robotika beserta nomor seri, kondisi (Baik/Rusak), dan lokasi lab.
- **Tracking Stok:** Pengurangan dan penambahan stok alat secara otomatis saat terjadi peminjaman atau pengembalian.
- **Log Maintenance:** Riwayat perawatan berkala perangkat laboratorium oleh asisten lab.

### 2. Sistem Paminjaman & Pengembalian Alat

- **Pengajuan Mandiri:** Mahasiswa/praktikan dapat melakukan request peminjaman alat secara online.
- **Validasi Asisten:** Persetujuan atau penolakan pengajuan peminjaman oleh kepala lab atau asisten laboratorium yang bertugas.
- **Notifikasi Batas Waktu:** Sistem otomatis mencatat tenggat waktu peminjaman untuk meminimalkan keterlambatan pengembalian barang laboratorium.

### 3. Manajemen Kegiatan & Event Laboratorium

- **Penjadwalan Praktikum:** Pengaturan jadwal penggunaan ruangan laboratorium per kelas/program studi.
- **Pendaftaran Diklat / Training:** Pengelolaan inventaris khusus dan absensi peserta saat ada acara internal organisasi teknologi/robotika (seperti persiapan diklat junior member).

### 4. Manajemen Pengguna & Hak Akses (RBAC)

- **Administrator / Kepala Laboratorium:** Akses penuh ke seluruh laporan, master data, dan pengelolaan akun asisten.
- **Asisten Laboratorium:** Verifikasi peminjaman barang, update kondisi inventaris, dan input absensi kegiatan.
- **Mahasiswa / Praktikan:** Mengajukan peminjaman alat dan melihat ketersediaan slot jadwal laboratorium.

---

## 🛠️ Spesifikasi Teknologi

- **Bahasa Pemrograman:** PHP 8.0+ (Native OOP / Clean Architecture)
- **Database:** MySQL / MariaDB
- **Format Data:** JSON (untuk komunikasi API antarmuka/dashboard frontend)
- **Autentikasi:** Session-based Authentication / JWT token verification.

---

## 🗄️ Struktur Arsitektur Basis Data (Database)

Berikut adalah ringkasan tabel utama yang digunakan dalam sistem ini:

- `users` - Menyimpan data akun pengguna (Admin, Asisten, Mahasiswa).
- `peralatan_lab` - Berisi katalog alat, komponen robotika, berserta status dan kuantitasnya.
- `peminjaman` - Mencatat log pengajuan, tanggal pinjam, batas waktu, dan status persetujuan.
- `jadwal_lab` - Mengatur penggunaan ruangan untuk praktikum mingguan atau event diklat.
- `log_inventaris` - Riwayat keluar-masuk barang secara berkala untuk keperluan audit internal.

---

## 🚀 Panduan Instalasi Lokal (Local Deployment)

Ikuti langkah-langkah berikut untuk menjalankan project ini di komputer lokal Anda:

### 1. Prasyarat (Prerequisites)

Pastikan Anda sudah menginstal:

- Web Server (XAMPP / Laragon / Apache) yang mendukung **PHP versi 8.0 ke atas**.
- Database server **MySQL**.
- Alat bantu pengujian API seperti **Postman** atau ekstensi **Thunder Client** di VS Code.

### 2. Kloning Repositori

Buka terminal/command prompt, arahkan ke direktori web server Anda (`htdocs` atau `www`), lalu jalankan perintah:

```bash
git clone [https://github.com/bintangrangga/sistem-managemen-laboratorium-backend.git](https://github.com/bintangrangga/sistem-managemen-laboratorium-backend.git)
cd sistem-managemen-laboratorium-backend
```

### 3. Konfigurasi Database

1. Aktifkan XAMPP/Laragon (Apache & MySQL).
2. Buka `phpMyAdmin` atau database client favorit Anda (HeidiSQL/DBeaver).
3. Buat database baru bernama `db_manajemen_lab`.
4. Import file database (misal: `db_manajemen_lab.sql` atau jalankan file migrasi yang tersedia di dalam folder database project ini).

### 4. Pengaturan Variabel Lingkungan (Environment Configuration)

Sesuaikan konfigurasi koneksi database Anda di file konfigurasi utama (biasanya terletak pada `config/database.php` atau `.env`):

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_manajemen_lab');
```

### 5. Jalankan Aplikasi

Jika menggunakan XAMPP, buka browser dan akses:

```text
http://localhost/sistem-managemen-laboratorium-backend/
```

Atau jika menggunakan built-in PHP server, jalankan perintah ini di dalam folder utama project:

```bash
php -S localhost:8000
```

Lalu akses di browser via `http://localhost:8000`.

---

## 📡 Dokumentasi Endpoint API Resmi

Seluruh request dan response wajib menggunakan format **JSON** dengan header `Content-Type: application/json`.

### 🔐 1. Modul Autentikasi

#### `POST /api/auth/login.php`

- **Deskripsi:** Masuk ke dalam sistem untuk mendapatkan sesi / hak akses user.
- **Request Body:**

```json
{
  "username": "bintang_lab",
  "password": "password123"
}
```

- **Response Sukses (200 OK):**

```json
{
  "status": "success",
  "message": "Login berhasil",
  "data": {
    "id_user": 12,
    "nama": "Bintang",
    "role": "asisten"
  }
}
```

- **Response Gagal (401 Unauthorized):**

```json
{
  "status": "error",
  "message": "Username atau password salah!"
}
```

---

### 📦 2. Modul Inventaris & Peralatan Lab

#### `GET /api/peralatan/read.php`

- **Deskripsi:** Mengambil semua daftar item alat/komponen yang terdaftar di laboratorium.
- **Response Sukses (200 OK):**

```json
{
  "status": "success",
  "total_items": 2,
  "data": [
    {
      "id_alat": 1,
      "nama_alat": "Arduino Uno R3 Starter Kit",
      "stok": 15,
      "kondisi": "Baik",
      "lokasi_lab": "Lab Robotika"
    },
    {
      "id_alat": 2,
      "nama_alat": "Solder Listrik 60W",
      "stok": 5,
      "kondisi": "Rusak",
      "lokasi_lab": "Lab Perangkat Keras"
    }
  ]
}
```

#### `POST /api/peralatan/create.php`

- **Deskripsi:** Menambahkan item/alat baru ke dalam database inventaris (Akses: Admin / Asisten).
- **Request Body:**

```json
{
  "nama_alat": "Sensor Ultrasonic HC-SR04",
  "stok": 20,
  "kondisi": "Baik",
  "lokasi_lab": "Lab Robotika"
}
```

- **Response Sukses (201 Created):**

```json
{
  "status": "success",
  "message": "Item inventaris baru berhasil didaftarkan"
}
```

---

### 📑 3. Modul Transaksi Peminjaman Alat

#### `POST /api/peminjaman/ajukan.php`

- **Deskripsi:** Endpoint bagi mahasiswa untuk memesan/mengajukan peminjaman alat laboratorium.
- **Request Body:**

```json
{
  "id_user": 45,
  "id_alat": 1,
  "jumlah_pinjam": 2,
  "tgl_pinjam": "2026-06-10",
  "tgl_kembali_rencana": "2026-06-15"
}
```

- **Response Sukses (200 OK):**

```json
{
  "status": "success",
  "message": "Pengajuan peminjaman berhasil diproses, menunggu validasi asisten"
}
```

- **Response Gagal (400 Bad Request):**

```json
{
  "status": "error",
  "message": "Gagal mengajukan, stok barang tidak mencukupi!"
}
```

#### `POST /api/peminjaman/verifikasi.php`

- **Deskripsi:** Mengubah status peminjaman oleh asisten (menyetujui, menolak, atau mengonfirmasi pengembalian alat).
- **Request Body:**

```json
{
  "id_peminjaman": 102,
  "status_baru": "disetujui",
  "id_asisten_pemeriksa": 12
}
```

- **Response Sukses (200 OK):**

```json
{
  "status": "success",
  "message": "Status peminjaman berhasil diperbarui menjadi disetujui"
}
```

---

## 📝 Kontribusi

Jika Anda ingin ikut mengembangkan atau melaporkan adanya _bug_, silakan ikuti alur berikut:

1. Fork repositori ini.
2. Buat branch fitur baru (`git checkout -b fitur-keren-anda`).
3. Lakukan Commit atas perubahan Anda (`git commit -m 'Menambahkan fitur XYZ'`).
4. Push ke branch tersebut (`git push origin fitur-keren-anda`).
5. Buat **Pull Request** baru di halaman GitHub ini.

## 📄 Lisensi

Project ini dilisensikan di bawah **MIT License** - Anda bebas menyalin, memodifikasi, dan mendistribusikan ulang untuk kepentingan edukasi atau komersial dengan tetap menyertakan atribusi pengembang asli.