<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? 'Aplikasi Data Barang') ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container">
    <header>
        <h1>Aplikasi Data Barang</h1>
        <p>Praktikum 8 — Database CRUD yang dimodularisasi pada Praktikum 9</p>
    </header>
    <nav>
        <a href="index.php?page=dashboard">Dashboard</a>
        <a href="index.php?page=user/list">Data Barang</a>
        <a href="index.php?page=user/add">Tambah Barang</a>
    </nav>
    <main>
