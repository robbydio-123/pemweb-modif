<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/csrf.php';
require __DIR__ . '/../includes/koneksi.php';

csrf_verify();

$pelangganId = filter_input(INPUT_POST, 'pelanggan_id', FILTER_VALIDATE_INT) ?? filter_input(INPUT_POST, 'anggota_id', FILTER_VALIDATE_INT);
$motorId = filter_input(INPUT_POST, 'motor_id', FILTER_VALIDATE_INT) ?? filter_input(INPUT_POST, 'buku_id', FILTER_VALIDATE_INT);
$tanggalPinjam = trim($_POST['tanggal_pinjam'] ?? date('Y-m-d'));
$durasiHari = max(1, (int) ($_POST['durasi_hari'] ?? 1));

if (!$pelangganId || !$motorId) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Pelanggan dan armada motor wajib dipilih.'];
    header('Location: tambah.php');
    exit;
}

try {
    $pdo->beginTransaction();

    // Kunci baris motor (FOR UPDATE) agar status ketersediaan tidak berubah
    // di tengah proses ini — mencegah dua pelanggan menyewa motor yang sama bersamaan.
    $cek = $pdo->prepare("SELECT id, nama_motor, tarif_harian, status FROM motor WHERE id = :id FOR UPDATE");
    $cek->execute(['id' => $motorId]);
    $motor = $cek->fetch(PDO::FETCH_ASSOC);

    if (!$motor || $motor['status'] !== 'tersedia') {
        throw new Exception('Armada motor ' . ($motor['nama_motor'] ?? '') . ' saat ini tidak berstatus tersedia.');
    }

    $totalBiaya = (float) $motor['tarif_harian'] * $durasiHari;

    $insert = $pdo->prepare(
        "INSERT INTO peminjaman (motor_id, pelanggan_id, buku_id, anggota_id, tanggal_pinjam, durasi_hari, total_biaya, status)
         VALUES (:mid, :pid, :mid2, :pid2, :tgl, :durasi, :total, 'dipinjam')"
    );
    $insert->execute([
        'mid' => $motorId,
        'pid' => $pelangganId,
        'mid2' => $motorId,
        'pid2' => $pelangganId,
        'tgl' => $tanggalPinjam,
        'durasi' => $durasiHari,
        'total' => $totalBiaya,
    ]);

    // Ubah status motor menjadi 'disewa'
    $update = $pdo->prepare("UPDATE motor SET status = 'disewa' WHERE id = :id");
    $update->execute(['id' => $motorId]);

    $pdo->commit();

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Transaksi sewa motor ' . $motor['nama_motor'] . ' berhasil dicatat!'];
    header('Location: ../index.php');
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Gagal mencatat penyewaan: ' . $e->getMessage()];
    header('Location: tambah.php');
    exit;
}
