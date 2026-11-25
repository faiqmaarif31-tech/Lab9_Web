<?php
declare(strict_types=1);

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'latihan1';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    error_log('Database connection failed: ' . mysqli_connect_error());
    http_response_code(500);
    exit('Terjadi kesalahan pada koneksi database. Periksa konfigurasi database.');
}
mysqli_set_charset($conn, 'utf8mb4');
