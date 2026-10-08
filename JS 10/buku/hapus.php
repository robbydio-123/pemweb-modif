<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: list.php');
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM motor WHERE id = :id");
    $stmt->execute(['id' => $id]);

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Data motor berhasil dihapus.'];
} catch (Exception $e) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Gagal menghapus motor (mungkin sedang ada relasi transaksi): ' . $e->getMessage()];
}

header('Location: list.php');
exit;
