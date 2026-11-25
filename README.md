# Lab9Web — Integrasi Praktikum 8 dan Praktikum 9

Project ini merupakan pengembangan aplikasi CRUD data barang dari Praktikum 8 (PHP dan MySQL) yang diintegrasikan dengan materi PHP Modular Praktikum 9. Fitur Create, Read, Update, dan Delete dipertahankan, sedangkan susunan file diubah menjadi modular.

## Tujuan
- Mempertahankan aplikasi CRUD barang berbasis PHP dan MySQLi dari Praktikum 8.
- Menggunakan `require_once` untuk memuat konfigurasi dan helper inti.
- Memisahkan template header/footer, function umum, konfigurasi database, dan modul CRUD.
- Menggunakan router `index.php` dengan whitelist rute serta halaman 404.
- Menggunakan prepared statement, escaping output, validasi input, dan token CSRF pada aksi hapus.

## Kebutuhan
- XAMPP (Apache, MySQL/MariaDB, PHP).
- PHP dengan ekstensi `mysqli` dan `fileinfo`.
- Visual Studio Code atau editor sejenis.
- Browser dan phpMyAdmin.

## Struktur Direktori
```text
Lab9Web/
├── index.php
├── .htaccess
├── config/
│   └── database.php
├── views/
│   ├── header.php
│   ├── footer.php
│   ├── dashboard.php
│   └── 404.php
├── helpers/
│   └── functions.php
├── modules/
│   └── user/
│       ├── list.php
│       ├── add.php
│       ├── edit.php
│       └── delete.php
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
└── gambar/
```

## Database
Project menggunakan MySQLi seperti Praktikum 8 (tidak mencampur PDO). Buat database `latihan1`, kemudian jalankan SQL berikut di phpMyAdmin:

```sql
CREATE DATABASE IF NOT EXISTS latihan1;
USE latihan1;

CREATE TABLE IF NOT EXISTS data_barang (
    id_barang INT(10) AUTO_INCREMENT PRIMARY KEY,
    kategori VARCHAR(30),
    nama VARCHAR(30),
    gambar VARCHAR(100),
    harga_beli DECIMAL(10,0),
    harga_jual DECIMAL(10,0),
    stok INT(4)
);
```

Contoh data awal (jalankan jika tabel masih kosong):
```sql
INSERT INTO data_barang
(kategori, nama, gambar, harga_beli, harga_jual, stok)
VALUES
('Elektronik', 'HP Samsung Android', '', 2000000, 2400000, 5),
('Elektronik', 'HP Xiaomi Android', '', 1000000, 1400000, 5),
('Elektronik', 'HP OPPO Android', '', 1800000, 2300000, 5);
```

Jika database lokal berbeda, sesuaikan host, username, password, dan nama database pada `config/database.php`. Konfigurasi bawaan mengikuti XAMPP standar: host `localhost`, user `root`, password kosong, database `latihan1`.

## Cara Menjalankan
1. Ekstrak folder `Lab9Web` ke `C:/xampp/htdocs/`.
2. Jalankan Apache dan MySQL melalui XAMPP Control Panel.
3. Buat database dan tabel menggunakan SQL di bagian Database.
4. Pastikan pengaturan koneksi pada `config/database.php` sesuai.
5. Buka `http://localhost/Lab9Web/` atau `http://localhost/Lab9Web/index.php`.

## Daftar Rute
| URL | Fungsi |
|---|---|
| `index.php` atau `index.php?page=dashboard` | Dashboard |
| `index.php?page=user/list` | Menampilkan daftar barang (Read) |
| `index.php?page=user/add` | Form dan proses tambah barang (Create) |
| `index.php?page=user/edit&id=1` | Form dan proses ubah barang (Update) |
| `index.php?page=user/delete` | Proses hapus barang melalui POST (Delete) |
| `index.php?page=alamat-tidak-valid` | Menampilkan halaman 404 |

Router menggunakan whitelist. Nilai parameter `page` tidak langsung digabungkan menjadi path file, sehingga input URL tidak dapat menentukan file yang di-require.

## Penerapan Materi Praktikum 9
- **Template:** `views/header.php` dan `views/footer.php` dipakai bersama oleh semua halaman.
- **Function:** `helpers/functions.php` berisi `e()`, `formatRupiah()`, `hitungDiskon()`, `validasiEmail()`, `ringkasTeks()`, serta helper CSRF, redirect, dan upload.
- **Struktur direktori:** konfigurasi, views, helpers, modules, dan assets dipisahkan berdasarkan tanggung jawab.
- **Routing:** `index.php` menjadi titik masuk dan memakai daftar rute yang diizinkan.
- **404:** rute yang tidak dikenal menghasilkan status HTTP 404 dan halaman khusus.
- **Integrasi CRUD:** seluruh operasi barang tetap memakai tabel `data_barang` dan koneksi MySQLi yang dipusatkan di satu file.
- **Keamanan dasar:** query CRUD menggunakan prepared statement, output di-escape dengan `htmlspecialchars`, dan penghapusan dilakukan melalui POST dengan token CSRF.

## Pengujian
Lakukan pengujian berikut di browser:
1. Buka dashboard dan pastikan template header, navigasi, serta footer tampil.
2. Buka menu Data Barang dan pastikan data database tampil.
3. Tambahkan barang baru, lalu pastikan data muncul di daftar.
4. Ubah salah satu barang, lalu pastikan perubahan tersimpan.
5. Hapus salah satu barang dan pastikan data hilang dari daftar.
6. Buka `index.php?page=admin/rahasia` dan pastikan halaman 404 tampil.
7. Uji unggah gambar JPG/PNG/GIF/WEBP maksimal 2 MB.
8. Pastikan CSS termuat pada dashboard, daftar, tambah, ubah, dan 404.

## Screenshot dan Repository
Sesuai ketentuan laporan Praktikum 9, tambahkan screenshot hasil pekerjaan pada laporan/repository:
- Struktur direktori project.
- Template header dan footer.
- File helper/function.
- Router dan halaman 404.
- Hasil pengujian CRUD (tambah, tampil, ubah, hapus).

Buat repository baru dengan nama **Lab9Web**, lakukan commit hasil pekerjaan, lalu kirim URL repository melalui e-learning sesuai instruksi dosen.

## Catatan
- File `.htaccess` disediakan sebagai opsi URL rewrite sesuai modul. Aturan ini menggunakan `RewriteBase /Lab9Web/`; sesuaikan jika nama folder project diubah. Jika rewrite tidak diperlukan, aplikasi tetap dapat dijalankan melalui URL `index.php?page=...`.
- Screenshot tidak disertakan karena harus diambil dari hasil aplikasi yang dijalankan pada komputer/lokal pengguna.
