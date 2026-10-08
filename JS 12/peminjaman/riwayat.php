<?php
require __DIR__ . '/../includes/auth.php';
$page_title = "Riwayat Sewa";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$pelangganId = filter_input(INPUT_GET, 'pelanggan_id', FILTER_VALIDATE_INT) ?? filter_input(INPUT_GET, 'anggota_id', FILTER_VALIDATE_INT);
$daftarPelanggan = $pdo->query("SELECT * FROM pelanggan ORDER BY nama ASC")->fetchAll(PDO::FETCH_ASSOC);

$riwayat = [];
$pelangganTerpilih = null;

if ($pelangganId) {
    $stmtA = $pdo->prepare("SELECT * FROM pelanggan WHERE id = :id");
    $stmtA->execute(['id' => $pelangganId]);
    $pelangganTerpilih = $stmtA->fetch(PDO::FETCH_ASSOC);

    if ($pelangganTerpilih) {
        $stmt = $pdo->prepare(
            "SELECT m.nama_motor, m.plat_nomor, p.tanggal_pinjam, p.tanggal_kembali, p.durasi_hari, p.total_biaya, p.status
             FROM peminjaman p
             JOIN motor m ON m.id = p.motor_id
             WHERE p.pelanggan_id = :id
             ORDER BY p.tanggal_pinjam DESC, p.id DESC"
        );
        $stmt->execute(['id' => $pelangganId]);
        $riwayat = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    // Tampilkan 15 transaksi sewa terbaru di rental jika belum memilih pelanggan tertentu
    $stmt = $pdo->query(
        "SELECT m.nama_motor, m.plat_nomor, a.nama AS nama_pelanggan, p.tanggal_pinjam, p.tanggal_kembali, p.durasi_hari, p.total_biaya, p.status
         FROM peminjaman p
         JOIN motor m ON m.id = p.motor_id
         JOIN pelanggan a ON a.id = p.pelanggan_id
         ORDER BY p.tanggal_pinjam DESC, p.id DESC
         LIMIT 20"
    );
    $riwayat = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<section>
    <div class="page-header">
        <div>
            <h2>Riwayat Transaksi Rental</h2>
            <p class="page-description">Lacak histori penyewaan motor, tanggal pengembalian, dan status pembayaran per pelanggan.</p>
        </div>
        <a href="tambah.php" class="button button-primary">➕ Sewa Motor Baru</a>
    </div>

    <div class="search-box">
        <form method="get" action="riwayat.php">
            <label for="pelanggan_id" style="font-weight: 600; margin-right: 0.5rem;">Filter Pelanggan:</label>
            <select id="pelanggan_id" name="pelanggan_id">
                <option value="">-- Semua Pelanggan (20 Transaksi Terakhir) --</option>
                <?php foreach ($daftarPelanggan as $pelanggan): ?>
                    <option value="<?php echo (int) $pelanggan['id']; ?>" <?php echo $pelangganId === (int) $pelanggan['id'] ? 'selected' : ''; ?>>
                        <?php echo e($pelanggan['nama']); ?> (<?php echo e($pelanggan['no_pelanggan']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="button">Tampilkan</button>
            <?php if ($pelangganId): ?>
                <a href="riwayat.php" class="button button-secondary">Tampilkan Semua</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if ($pelangganTerpilih): ?>
        <div class="customer-info-banner">
            <h3>Histori Sewa Pelanggan: <strong><?php echo e($pelangganTerpilih['nama']); ?></strong></h3>
            <p>No. Pelanggan: <code><?php echo e($pelangganTerpilih['no_pelanggan']); ?></code> | No. HP: <?php echo e($pelangganTerpilih['no_hp']); ?> | Alamat: <?php echo e($pelangganTerpilih['alamat'] ?? '-'); ?></p>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <?php if (!$pelangganTerpilih): ?>
                        <th>Pelanggan</th>
                    <?php endif; ?>
                    <th>Unit Motor</th>
                    <th>Plat Nomor</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Durasi</th>
                    <th>Total Biaya</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($riwayat)): ?>
                    <tr>
                        <td colspan="<?php echo $pelangganTerpilih ? 7 : 8; ?>" class="text-center">Belum ada catatan riwayat transaksi sewa.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($riwayat as $r): ?>
                        <tr>
                            <?php if (!$pelangganTerpilih): ?>
                                <td><strong><?php echo e($r['nama_pelanggan'] ?? '-'); ?></strong></td>
                            <?php endif; ?>
                            <td><strong><?php echo e($r['nama_motor']); ?></strong></td>
                            <td><code><?php echo e($r['plat_nomor']); ?></code></td>
                            <td><?php echo e($r['tanggal_pinjam']); ?></td>
                            <td><?php echo $r['tanggal_kembali'] ? e($r['tanggal_kembali']) : '<span class="text-warning">Belum Kembali</span>'; ?></td>
                            <td><?php echo (int) $r['durasi_hari']; ?> Hari</td>
                            <td>Rp <?php echo number_format((float) $r['total_biaya'], 0, ',', '.'); ?></td>
                            <td>
                                <?php if ($r['status'] === 'dipinjam'): ?>
                                    <span class="badge badge-disewa">Sedang Disewa</span>
                                <?php else: ?>
                                    <span class="badge badge-tersedia">Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
