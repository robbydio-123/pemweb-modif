<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/csrf.php';
require __DIR__ . '/../includes/koneksi.php';

csrf_verify();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nama_motor = trim($_POST['nama_motor'] ?? '');
$merk = trim($_POST['merk'] ?? '');
$tahun = (int) ($_POST['tahun'] ?? 0);
$plat_nomor = strtoupper(trim($_POST['plat_nomor'] ?? ''));
$tarif_harian = (float) ($_POST['tarif_harian'] ?? 0);
$status = trim($_POST['status'] ?? 'tersedia');

if (!$id) {
    header('Location: list.php');
    exit;
}

$errors = [];
if ($nama_motor === '') $errors[] = "Nama motor wajib diisi.";
if ($merk === '') $errors[] = "Merk wajib diisi.";
if ($tahun < 1990 || $tahun > 2100) $errors[] = "Tahun motor tidak valid.";
if ($plat_nomor === '') $errors[] = "Plat nomor wajib diisi.";
if ($tarif_harian <= 0) $errors[] = "Tarif harian harus lebih dari 0.";
if (!in_array($status, ['tersedia', 'disewa', 'servis'], true)) $status = 'tersedia';

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: edit.php?id=' . $id);
    exit;
}

// Cek apakah plat nomor bentrok dengan id motor lain
$cekPlat = $pdo->prepare("SELECT id FROM motor WHERE plat_nomor = :plat AND id != :id");
$cekPlat->execute(['plat' => $plat_nomor, 'id' => $id]);
if ($cekPlat->fetch()) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Plat nomor ' . $plat_nomor . ' sudah dipakai motor lain.'];
    header('Location: edit.php?id=' . $id);
    exit;
}

try {
    $stmt = $pdo->prepare(
        "UPDATE motor SET
            nama_motor = :nama,
            merk = :merk,
            tahun = :tahun,
            plat_nomor = :plat,
            tarif_harian = :tarif,
            status = :status
         WHERE id = :id"
    );
    $stmt->execute([
        'nama' => $nama_motor,
        'merk' => $merk,
        'tahun' => $tahun,
        'plat' => $plat_nomor,
        'tarif' => $tarif_harian,
        'status' => $status,
        'id' => $id,
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Data motor berhasil diperbarui.'];
    header('Location: list.php');
    exit;
} catch (Exception $e) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Gagal memperbarui motor: ' . $e->getMessage()];
    header('Location: edit.php?id=' . $id);
    exit;
}
