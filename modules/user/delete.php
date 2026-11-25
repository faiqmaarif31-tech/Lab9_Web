<?php
require_once __DIR__ . '/../../config/database.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrfValid()) {
    http_response_code(405);
    $_SESSION['flash'] = 'Permintaan penghapusan tidak valid.';
    redirect('user/list');
}
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    $_SESSION['flash'] = 'ID barang tidak valid.';
    redirect('user/list');
}
$stmt = mysqli_prepare($conn, 'DELETE FROM data_barang WHERE id_barang = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$_SESSION['flash'] = mysqli_stmt_affected_rows($stmt) > 0 ? 'Data barang berhasil dihapus.' : 'Data barang tidak ditemukan.';
redirect('user/list');
