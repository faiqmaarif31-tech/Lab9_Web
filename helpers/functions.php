<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function formatRupiah(float $angka): string {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function hitungDiskon(float $harga, float $persen = 10): float {
    return $harga * ($persen / 100);
}

function validasiEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function ringkasTeks(string $teks, int $maksimal = 50): string {
    if (mb_strlen($teks, 'UTF-8') <= $maksimal) return $teks;
    return mb_substr($teks, 0, max(0, $maksimal - 3), 'UTF-8') . '...';
}

function csrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfValid(): bool {
    return isset($_POST['csrf_token'], $_SESSION['csrf_token'])
        && is_string($_POST['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

function redirect(string $page = 'user/list'): never {
    header('Location: index.php?page=' . rawurlencode($page));
    exit;
}

function uploadGambar(array $file): ?string {
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) return null;
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Unggah gambar gagal.');
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        throw new RuntimeException('Ukuran gambar maksimal 2 MB.');
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Format gambar harus JPG, PNG, GIF, atau WEBP.');
    }
    $filename = bin2hex(random_bytes(12)) . '.' . $allowed[$mime];
    $directory = dirname(__DIR__) . '/gambar/';
    if (!is_dir($directory)) mkdir($directory, 0755, true);
    if (!move_uploaded_file($file['tmp_name'], $directory . $filename)) {
        throw new RuntimeException('Gambar tidak dapat disimpan.');
    }
    return 'gambar/' . $filename;
}
