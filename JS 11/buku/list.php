<?php
$page_title = "Daftar Motor";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$perPage = 5;
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;
$keyword = trim($_GET['q'] ?? '');

if ($keyword !== '') {
    $hitung = $pdo->prepare("SELECT COUNT(*) FROM motor WHERE nama_motor ILIKE :kw OR merk ILIKE :kw OR plat_nomor ILIKE :kw");
    $hitung->execute(['kw' => '%' . $keyword . '%']);
    $totalRows = (int) $hitung->fetchColumn();

    $stmt = $pdo->prepare("SELECT * FROM motor WHERE nama_motor ILIKE :kw OR merk ILIKE :kw OR plat_nomor ILIKE :kw ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue('kw', '%' . $keyword . '%');
} else {
    $totalRows = (int) $pdo->query("SELECT COUNT(*) FROM motor")->fetchColumn();
    $stmt = $pdo->prepare("SELECT * FROM motor ORDER BY id DESC LIMIT :limit OFFSET :offset");
}
$stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$daftarMotor = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalPages = max(1, (int) ceil($totalRows / $perPage));
?>
<section>
    <div class="page-header">
        <div>
            <h2>Daftar Motor</h2>
            <p class="page-description">
                <?php echo $sudahLogin
                    ? "Kelola data armada rental motor, tarif sewa, dan status ketersediaan."
                    : "Katalog armada sepeda motor yang tersedia di Rental Motor Lowokwaru."; ?>
            </p>
        </div>
        <?php if ($sudahLogin): ?>
            <a href="tambah.php" class="button button-primary">➕ Tambah Motor</a>
        <?php endif; ?>
    </div>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo e($flash['type'] ?? 'info'); ?>">
            <?php echo e($flash['pesan'] ?? $flash); ?>
        </p>
    <?php endif; ?>

    <div class="search-box">
        <form method="get" action="list.php">
            <input type="text" id="search-input" name="q" value="<?php echo e($keyword); ?>" placeholder="Cari nama motor, merk, atau plat nomor...">
            <button type="submit" class="button">Cari</button>
            <?php if ($keyword !== ''): ?>
                <a href="list.php" class="button button-secondary">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Motor</th>
                    <th>Merk</th>
                    <th>Tahun</th>
                    <th>Plat Nomor</th>
                    <th>Tarif / Hari</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftarMotor)): ?>
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data armada motor yang ditemukan.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($daftarMotor as $motor): ?>
                        <tr>
                            <td><strong><?php echo e($motor['nama_motor']); ?></strong></td>
                            <td><?php echo e($motor['merk']); ?></td>
                            <td><?php echo (int) $motor['tahun']; ?></td>
                            <td><code><?php echo e($motor['plat_nomor']); ?></code></td>
                            <td>Rp <?php echo number_format((float) $motor['tarif_harian'], 0, ',', '.'); ?></td>
                            <td>
                                <span class="badge badge-<?php echo e($motor['status']); ?>">
                                    <?php echo ucfirst(e($motor['status'])); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($sudahLogin): ?>
                                    <div class="action-cell">
                                        <a href="edit.php?id=<?php echo (int) $motor['id']; ?>" class="button button-sm button-edit">Edit</a>
                                        <form method="post" action="hapus.php" class="inline-form" onsubmit="return confirm('Apakah Anda yakin ingin menghapus motor ini?');">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="id" value="<?php echo (int) $motor['id']; ?>">
                                            <button type="submit" class="button button-sm button-danger">Hapus</button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <?php if ($motor['status'] === 'tersedia'): ?>
                                        <a href="https://wa.me/6281234567890?text=Halo%20Rental%20Motor%20Lowokwaru,%20saya%20ingin%20sewa%20motor%20<?php echo urlencode($motor['nama_motor']); ?>" target="_blank" class="button button-sm button-success">Sewa (WA)</a>
                                    <?php else: ?>
                                        <span class="text-muted">Tidak Tersedia</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
        <nav class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="list.php?page=<?php echo $i; ?><?php echo $keyword !== '' ? '&q=' . urlencode($keyword) : ''; ?>"
                   class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </nav>
    <?php endif; ?>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>