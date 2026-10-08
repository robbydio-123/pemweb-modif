<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';

$no_pelanggan = trim($_POST['no_pelanggan'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$no_hp = trim($_POST['no_hp'] ?? '');

$errors = [];
if ($no_pelanggan === '') $errors[] = "No. pelanggan wajib diisi.";
if ($nama === '') $errors[] = "Nama pelanggan wajib diisi.";
if ($no_hp === '') $errors[] = "No. HP wajib diisi.";

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php');
    exit;
}

// Cek apakah no_pelanggan sudah ada
$cek = $pdo->prepare("SELECT id FROM pelanggan WHERE no_pelanggan = :nopel");
$cek->execute(['nopel' => $no_pelanggan]);
if ($cek->fetch()) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'No. Pelanggan ' . $no_pelanggan . ' sudah terdaftar.'];
    header('Location: tambah.php');
    exit;
}

try {
    $stmt = $pdo->prepare(
        "INSERT INTO pelanggan (nama, no_pelanggan, alamat, no_hp)
         VALUES (:nama, :no_pelanggan, :alamat, :no_hp)"
    );
    $stmt->execute([
        'nama' => $nama,
        'no_pelanggan' => $no_pelanggan,
        'alamat' => $alamat,
        'no_hp' => $no_hp,
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Pelanggan berhasil ditambahkan.'];
    header('Location: list.php');
    exit;
} catch (Exception $e) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Gagal menambahkan pelanggan: ' . $e->getMessage()];
    header('Location: tambah.php');
    exit;
}