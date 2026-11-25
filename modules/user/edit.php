<?php
$pageTitle = 'Ubah Barang';
require_once __DIR__ . '/../../config/database.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { http_response_code(404); require __DIR__ . '/../../views/404.php'; return; }

$stmt = mysqli_prepare($conn, 'SELECT id_barang, nama, kategori, harga_jual, harga_beli, stok, gambar FROM data_barang WHERE id_barang = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$data) { http_response_code(404); require __DIR__ . '/../../views/404.php'; return; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfValid()) {
        $error = 'Permintaan tidak valid. Muat ulang halaman dan coba lagi.';
    } else {
        $nama = trim((string) ($_POST['nama'] ?? ''));
        $kategori = trim((string) ($_POST['kategori'] ?? ''));
        $hargaJual = filter_var($_POST['harga_jual'] ?? null, FILTER_VALIDATE_FLOAT);
        $hargaBeli = filter_var($_POST['harga_beli'] ?? null, FILTER_VALIDATE_FLOAT);
        $stok = filter_var($_POST['stok'] ?? null, FILTER_VALIDATE_INT);
        try {
            if ($nama === '' || $kategori === '' || $hargaJual === false || $hargaBeli === false || $stok === false || $hargaJual < 0 || $hargaBeli < 0 || $stok < 0) {
                throw new RuntimeException('Lengkapi data dengan benar. Harga dan stok tidak boleh negatif.');
            }
            $gambarBaru = uploadGambar($_FILES['file_gambar'] ?? []);
            if ($gambarBaru !== null) {
                $stmt = mysqli_prepare($conn, 'UPDATE data_barang SET nama=?, kategori=?, harga_jual=?, harga_beli=?, stok=?, gambar=? WHERE id_barang=?');
                mysqli_stmt_bind_param($stmt, 'ssddisi', $nama, $kategori, $hargaJual, $hargaBeli, $stok, $gambarBaru, $id);
            } else {
                $stmt = mysqli_prepare($conn, 'UPDATE data_barang SET nama=?, kategori=?, harga_jual=?, harga_beli=?, stok=? WHERE id_barang=?');
                mysqli_stmt_bind_param($stmt, 'ssddii', $nama, $kategori, $hargaJual, $hargaBeli, $stok, $id);
            }
            mysqli_stmt_execute($stmt);
            $_SESSION['flash'] = 'Data barang berhasil diperbarui.';
            redirect('user/list');
        } catch (Throwable $e) {
            $error = $e->getMessage();
        }
    }
}
?>
<section>
    <h2>Ubah Barang</h2>
    <?php if ($error !== ''): ?><p class="error"><?= e($error) ?></p><?php endif; ?>
    <form method="post" action="index.php?page=user/edit&id=<?= (int) $id ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
        <div class="input"><label>Nama Barang</label><input type="text" name="nama" required value="<?= e((string) ($_POST['nama'] ?? $data['nama'])) ?>"></div>
        <div class="input"><label>Kategori</label>
            <select name="kategori" required>
                <?php foreach (['Komputer','Elektronik','Hand Phone'] as $kategori): ?>
                <option value="<?= e($kategori) ?>" <?= (($_POST['kategori'] ?? $data['kategori']) === $kategori) ? 'selected' : '' ?>><?= e($kategori) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="input"><label>Harga Jual</label><input type="number" name="harga_jual" min="0" required value="<?= e((string) ($_POST['harga_jual'] ?? $data['harga_jual'])) ?>"></div>
        <div class="input"><label>Harga Beli</label><input type="number" name="harga_beli" min="0" required value="<?= e((string) ($_POST['harga_beli'] ?? $data['harga_beli'])) ?>"></div>
        <div class="input"><label>Stok</label><input type="number" name="stok" min="0" required value="<?= e((string) ($_POST['stok'] ?? $data['stok'])) ?>"></div>
        <div class="input"><label>File Gambar</label><input type="file" name="file_gambar" accept="image/*"></div>
        <?php if (!empty($data['gambar'])): ?><p>Gambar saat ini: <img src="<?= e((string) $data['gambar']) ?>" alt="Gambar barang" width="80"></p><?php endif; ?>
        <div class="form-actions"><div class="submit"><input type="submit" value="Simpan Perubahan"></div><a href="index.php?page=user/list">Kembali</a></div>
    </form>
</section>
