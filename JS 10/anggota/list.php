<?php
require __DIR__ . '/../includes/auth.php';
$page_title = "Daftar Pelanggan";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$perPage = 5;
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;
$keyword = trim($_GET['q'] ?? '');

if ($keyword !== '') {
    $hitung = $pdo->prepare("SELECT COUNT(*) FROM pelanggan WHERE nama ILIKE :kw OR no_pelanggan ILIKE :kw OR no_hp ILIKE :kw");
    $hitung->execute(['kw' => '%' . $keyword . '%']);
    $totalRows = (int) $hitung->fetchColumn();

    $stmt = $pdo->prepare("SELECT * FROM pelanggan WHERE nama ILIKE :kw OR no_pelanggan ILIKE :kw OR no_hp ILIKE :kw ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue('kw', '%' . $keyword . '%');
} else {
    $totalRows = (int) $pdo->query("SELECT COUNT(*) FROM pelanggan")->fetchColumn();
    $stmt = $pdo->prepare("SELECT * FROM pelanggan ORDER BY id DESC LIMIT :limit OFFSET :offset");
}
$stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$daftarPelanggan = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalPages = max(1, (int) ceil($totalRows / $perPage));
?>
<section>
    <div class="page-header">
        <div>
            <h2>Daftar Pelanggan</h2>
            <p class="page-description">Kelola data penyewa / pelanggan Rental Motor Lowokwaru.</p>
        </div>
        <a href="tambah.php" class="button button-primary">➕ Tambah Pelanggan</a>
    </div>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo htmlspecialchars($flash['type'] ?? 'info'); ?>">
            <?php echo htmlspecialchars($flash['pesan'] ?? $flash); ?>
        </p>
    <?php endif; ?>

    <div class="search-box">
        <form method="get" action="list.php">
            <input type="text" id="search-input" name="q" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Cari nama, no. pelanggan, atau no. HP...">
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
                    <th>No. Pelanggan</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>No. HP / WA</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftarPelanggan)): ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data pelanggan yang cocok.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($daftarPelanggan as $pelanggan): ?>
                        <tr>
                            <td><code><?php echo htmlspecialchars($pelanggan['no_pelanggan']); ?></code></td>
                            <td><strong><?php echo htmlspecialchars($pelanggan['nama']); ?></strong></td>
                            <td><?php echo htmlspecialchars($pelanggan['alamat'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($pelanggan['no_hp']); ?></td>
                            <td>
                                <div class="action-cell">
                                    <a href="edit.php?id=<?php echo (int) $pelanggan['id']; ?>" class="button button-sm button-edit">Edit</a>
                                    <form method="post" action="hapus.php" class="inline-form" onsubmit="return confirm('Hapus pelanggan ini?');">
                                        <input type="hidden" name="id" value="<?php echo (int) $pelanggan['id']; ?>">
                                        <button type="submit" class="button button-sm button-danger">Hapus</button>
                                    </form>
                                </div>
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