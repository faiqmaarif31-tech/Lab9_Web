<?php
$pageTitle = 'Data Barang';
require_once __DIR__ . '/../../config/database.php';

$sql = 'SELECT id_barang, kategori, nama, gambar, harga_beli, harga_jual, stok FROM data_barang ORDER BY id_barang DESC';
$result = mysqli_query($conn, $sql);
if (!$result) {
    error_log(mysqli_error($conn));
    http_response_code(500);
    exit('Data barang gagal dimuat.');
}
?>
<section>
    <h2>Data Barang</h2>
    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="message"><?= e((string) $_SESSION['flash']); unset($_SESSION['flash']); ?></div>
    <?php endif; ?>
    <a class="button" href="index.php?page=user/add">Tambah Barang</a>
    <div class="main">
        <table>
            <thead><tr>
                <th>Gambar</th><th>Nama Barang</th><th>Kategori</th>
                <th>Harga Jual</th><th>Harga Beli</th><th>Stok</th><th>Aksi</th>
            </tr></thead>
            <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php if (!empty($row['gambar'])): ?><img src="<?= e((string) $row['gambar']) ?>" alt="<?= e((string) $row['nama']) ?>"><?php else: ?>-<?php endif; ?></td>
                    <td><?= e((string) $row['nama']) ?></td>
                    <td><?= e((string) $row['kategori']) ?></td>
                    <td><?= formatRupiah((float) $row['harga_jual']) ?></td>
                    <td><?= formatRupiah((float) $row['harga_beli']) ?></td>
                    <td><?= e((string) $row['stok']) ?></td>
                    <td>
                        <a href="index.php?page=user/edit&id=<?= (int) $row['id_barang'] ?>">Ubah</a>
                        <form method="post" action="index.php?page=user/delete" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                            <input type="hidden" name="id" value="<?= (int) $row['id_barang'] ?>">
                            <button class="action-delete" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7">Belum ada data barang.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
