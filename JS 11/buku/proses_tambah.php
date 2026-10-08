<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/csrf.php';
require __DIR__ . '/../includes/koneksi.php';

csrf_verify();

$nama_motor = trim($_POST['nama_motor'] ?? '');
$merk = trim($_POST['merk'] ?? '');
$tahun = (int) ($_POST['tahun'] ?? 0);
$plat_nomor = strtoupper(trim($_POST['plat_nomor'] ?? ''));
$tarif_harian = (float) ($_POST['tarif_harian'] ?? 0);
$status = trim($_POST['status'] ?? 'tersedia');

$errors = [];
if ($nama_motor === '') $errors[] = "Nama motor wajib diisi.";
if ($merk === '') $errors[] = "Merk wajib diisi.";
if ($tahun < 1990 || $tahun > 2100) $errors[] = "Tahun motor tidak valid.";
if ($plat_nomor === '') $errors[] = "Plat nomor wajib diisi.";
if ($tarif_harian <= 0) $errors[] = "Tarif harian harus lebih dari 0.";
if (!in_array($status, ['tersedia', 'disewa', 'servis'], true)) $status = 'tersedia';

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php');
    exit;
}

// Cek apakah plat nomor sudah terdaftar
$cekPlat = $pdo->prepare("SELECT id FROM motor WHERE plat_nomor = :plat");
$cekPlat->execute(['plat' => $plat_nomor]);
if ($cekPlat->fetch()) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Plat nomor ' . $plat_nomor . ' sudah terdaftar.'];
    header('Location: tambah.php');
    exit;
}

try {
    $stmt = $pdo->prepare(
        "INSERT INTO motor (nama_motor, merk, tahun, plat_nomor, tarif_harian, status)
         VALUES (:nama, :merk, :tahun, :plat, :tarif, :status)"
    );
    $stmt->execute([
        'nama' => $nama_motor,
        'merk' => $merk,
        'tahun' => $tahun,
        'plat' => $plat_nomor,
        'tarif' => $tarif_harian,
        'status' => $status,
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Data motor berhasil ditambahkan.'];
    header('Location: list.php');
    exit;
} catch (Exception $e) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Gagal menambahkan motor: ' . $e->getMessage()];
    header('Location: tambah.php');
    exit;
}