<?php
$pageTitle = 'Tambah Barang';
require_once __DIR__ . '/../../config/database.php';
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
            $gambar = uploadGambar($_FILES['file_gambar'] ?? []);
            $stmt = mysqli_prepare($conn, 'INSERT INTO data_barang (nama, kategori, harga_jual, harga_beli, stok, gambar) VALUES (?, ?, ?, ?, ?, ?)');
            mysqli_stmt_bind_param($stmt, 'ssddis', $nama, $kategori, $hargaJual, $hargaBeli, $stok, $gambar);
            mysqli_stmt_execute($stmt);
            $_SESSION['flash'] = 'Data barang berhasil ditambahkan.';
            redirect('user/list');
        } catch (Throwable $e) {
            $error = $e->getMessage();
        }
    }
}
?>
<section>
    <h2>Tambah Barang</h2>
    <?php if ($error !== ''): ?><p class="error"><?= e($error) ?></p><?php endif; ?>
    <form method="post" action="index.php?page=user/add" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
        <div class="input"><label>Nama Barang</label><input type="text" name="nama" required value="<?= e((string) ($_POST['nama'] ?? '')) ?>"></div>
        <div class="input"><label>Kategori</label>
            <select name="kategori" required>
                <?php foreach (['Komputer','Elektronik','Hand Phone'] as $kategori): ?>
                <option value="<?= e($kategori) ?>" <?= (($_POST['kategori'] ?? 'Elektronik') === $kategori) ? 'selected' : '' ?>><?= e($kategori) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="input"><label>Harga Jual</label><input type="number" name="harga_jual" min="0" step="1" required value="<?= e((string) ($_POST['harga_jual'] ?? '')) ?>"></div>
        <div class="input"><label>Harga Beli</label><input type="number" name="harga_beli" min="0" step="1" required value="<?= e((string) ($_POST['harga_beli'] ?? '')) ?>"></div>
        <div class="input"><label>Stok</label><input type="number" name="stok" min="0" required value="<?= e((string) ($_POST['stok'] ?? '')) ?>"></div>
        <div class="input"><label>File Gambar</label><input type="file" name="file_gambar" accept="image/*"></div>
        <div class="form-actions"><div class="submit"><input type="submit" value="Simpan"></div><a href="index.php?page=user/list">Kembali</a></div>
    </form>
</section>
