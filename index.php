<?php
declare(strict_types=1);
ob_start(); // Menjaga header redirect tetap dapat dikirim dari modul CRUD.
require_once __DIR__ . '/helpers/functions.php';

$routes = [
    'dashboard' => __DIR__ . '/views/dashboard.php',
    'user/list' => __DIR__ . '/modules/user/list.php',
    'user/add' => __DIR__ . '/modules/user/add.php',
    'user/edit' => __DIR__ . '/modules/user/edit.php',
    'user/delete' => __DIR__ . '/modules/user/delete.php',
];

$page = $_GET['page'] ?? 'dashboard';
$pageTitles = [
    'dashboard' => 'Dashboard',
    'user/list' => 'Data Barang',
    'user/add' => 'Tambah Barang',
    'user/edit' => 'Ubah Barang',
    'user/delete' => 'Proses Hapus Barang',
];
$pageTitle = is_string($page) ? ($pageTitles[$page] ?? '404 - Halaman tidak ditemukan') : '404 - Halaman tidak ditemukan';
if (!is_string($page) || !array_key_exists($page, $routes)) {
    http_response_code(404);
    $contentFile = __DIR__ . '/views/404.php';
} else {
    $contentFile = $routes[$page];
}

require_once __DIR__ . '/views/header.php';
require $contentFile;
require_once __DIR__ . '/views/footer.php';
