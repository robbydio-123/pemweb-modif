<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/csrf.php';
require __DIR__ . '/../includes/koneksi.php';

csrf_verify();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: list.php');
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM pelanggan WHERE id = :id");
    $stmt->execute(['id' => $id]);

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Data pelanggan berhasil dihapus.'];
} catch (Exception $e) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Gagal menghapus pelanggan (terkait riwayat transaksi rental): ' . $e->getMessage()];
}

header('Location: list.php');
exit;
