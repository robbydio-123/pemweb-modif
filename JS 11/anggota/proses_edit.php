<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/csrf.php';
require __DIR__ . '/../includes/koneksi.php';

csrf_verify();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$no_pelanggan = trim($_POST['no_pelanggan'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$no_hp = trim($_POST['no_hp'] ?? '');

if (!$id) {
    header('Location: list.php');
    exit;
}

$errors = [];
if ($no_pelanggan === '') $errors[] = "No. pelanggan wajib diisi.";
if ($nama === '') $errors[] = "Nama pelanggan wajib diisi.";
if ($no_hp === '') $errors[] = "No. HP wajib diisi.";

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: edit.php?id=' . $id);
    exit;
}

// Cek duplikasi no_pelanggan pada id berbeda
$cek = $pdo->prepare("SELECT id FROM pelanggan WHERE no_pelanggan = :nopel AND id != :id");
$cek->execute(['nopel' => $no_pelanggan, 'id' => $id]);
if ($cek->fetch()) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'No. Pelanggan ' . $no_pelanggan . ' sudah terdaftar untuk pelanggan lain.'];
    header('Location: edit.php?id=' . $id);
    exit;
}

try {
    $stmt = $pdo->prepare(
        "UPDATE pelanggan SET
            nama = :nama,
            no_pelanggan = :no_pelanggan,
            alamat = :alamat,
            no_hp = :no_hp
         WHERE id = :id"
    );
    $stmt->execute([
        'nama' => $nama,
        'no_pelanggan' => $no_pelanggan,
        'alamat' => $alamat,
        'no_hp' => $no_hp,
        'id' => $id,
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Data pelanggan berhasil diperbarui.'];
    header('Location: list.php');
    exit;
} catch (Exception $e) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Gagal memperbarui data: ' . $e->getMessage()];
    header('Location: edit.php?id=' . $id);
    exit;
}
