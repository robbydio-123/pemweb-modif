<?php
require __DIR__ . '/../includes/auth.php';
$page_title = "Pengembalian Motor";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$keyword = trim($_GET['q'] ?? '');

$sqlDasar = "SELECT p.id, p.tanggal_pinjam, p.durasi_hari, p.total_biaya,
                    m.nama_motor, m.plat_nomor, m.tarif_harian,
                    a.nama AS nama_pelanggan, a.no_hp, a.no_pelanggan
             FROM peminjaman p
             JOIN motor m ON m.id = p.motor_id
             JOIN pelanggan a ON a.id = p.pelanggan_id
             WHERE p.status = 'dipinjam'";

if ($keyword !== '') {
    $stmt = $pdo->prepare($sqlDasar . " AND (m.nama_motor ILIKE :kw OR m.plat_nomor ILIKE :kw OR a.nama ILIKE :kw OR a.no_pelanggan ILIKE :kw) ORDER BY p.tanggal_pinjam ASC");
    $stmt->execute(['kw' => '%' . $keyword . '%']);
} else {
    $stmt = $pdo->query($sqlDasar . " ORDER BY p.tanggal_pinjam ASC");
}
$daftarAktif = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<section>
    <div class="page-header">
        <div>
            <h2>Pengembalian Unit Motor</h2>
            <p class="page-description">Kelola penyewaan aktif dan proses pengembalian unit sepeda motor yang selesai disewa.</p>
        </div>
        <a href="tambah.php" class="button button-primary">➕ Sewa Motor Baru</a>
    </div>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo e($flash['type'] ?? 'info'); ?>">
            <?php echo e($flash['pesan'] ?? $flash); ?>
        </p>
    <?php endif; ?>

    <div class="search-box">
        <form method="get" action="kembali.php">
            <input type="text" id="search-input" name="q" value="<?php echo e($keyword); ?>" placeholder="Cari nama pelanggan, plat motor, atau nama motor...">
            <button type="submit" class="button">Cari</button>
            <?php if ($keyword !== ''): ?>
                <a href="kembali.php" class="button button-secondary">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>Unit Motor</th>
                    <th>Plat Nomor</th>
                    <th>Tgl Pinjam</th>
                    <th>Durasi</th>
                    <th>Total Biaya</th>
                    <th>Aksi Pengembalian</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftarAktif)): ?>
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada unit motor yang sedang aktif dipinjam/disewa saat ini.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($daftarAktif as $trx): ?>
                        <tr>
                            <td>
                                <strong><?php echo e($trx['nama_pelanggan']); ?></strong><br>
                                <small class="text-muted"><?php echo e($trx['no_pelanggan']); ?> | WA: <?php echo e($trx['no_hp']); ?></small>
                            </td>
                            <td><strong><?php echo e($trx['nama_motor']); ?></strong></td>
                            <td><code><?php echo e($trx['plat_nomor']); ?></code></td>
                            <td><?php echo e($trx['tanggal_pinjam']); ?></td>
                            <td><?php echo (int) $trx['durasi_hari']; ?> Hari</td>
                            <td>Rp <?php echo number_format((float) $trx['total_biaya'], 0, ',', '.'); ?></td>
                            <td>
                                <form method="post" action="proses_kembali.php" onsubmit="return confirm('Konfirmasi pengembalian unit motor <?php echo htmlspecialchars(addslashes($trx['nama_motor'])); ?>? Pastikan kondisi unit, helm, dan jas hujan lengkap.');">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id" value="<?php echo (int) $trx['id']; ?>">
                                    <button type="submit" class="button button-sm button-success">🔄 Selesai &amp; Kembalikan</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
