<?php
$page_title = "Katalog Motor";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$keyword = trim($_GET['q'] ?? '');
$filterMerk = trim($_GET['merk'] ?? '');
$filterStatus = trim($_GET['status'] ?? '');

$sql = "SELECT * FROM motor WHERE 1=1";
$params = [];

if ($keyword !== '') {
    $sql .= " AND (nama_motor ILIKE :kw OR merk ILIKE :kw OR plat_nomor ILIKE :kw)";
    $params['kw'] = '%' . $keyword . '%';
}
if ($filterMerk !== '') {
    $sql .= " AND merk ILIKE :merk";
    $params['merk'] = $filterMerk;
}
if ($filterStatus !== '') {
    $sql .= " AND status = :status";
    $params['status'] = $filterStatus;
}

$sql .= " ORDER BY (status = 'tersedia') DESC, id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$daftarMotor = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalArmada = (int) $pdo->query("SELECT COUNT(*) FROM motor")->fetchColumn();
$totalTersedia = (int) $pdo->query("SELECT COUNT(*) FROM motor WHERE status = 'tersedia'")->fetchColumn();

// Fallback image generator based on motor name
function getMotorImage($motor) {
    if (!empty($motor['gambar'])) {
        return $motor['gambar'];
    }
    $nama = strtolower($motor['nama_motor']);
    if (strpos($nama, 'vespa') !== false) {
        return 'https://images.unsplash.com/photo-1622185135505-2d795003994a?auto=format&fit=crop&w=800&q=80';
    } elseif (strpos($nama, 'nmax') !== false) {
        return 'https://images.unsplash.com/photo-1571188654248-7a89213915f7?auto=format&fit=crop&w=800&q=80';
    } elseif (strpos($nama, 'aerox') !== false) {
        return 'https://images.unsplash.com/photo-1609630875171-b1321377ee65?auto=format&fit=crop&w=800&q=80';
    } elseif (strpos($nama, 'vario') !== false) {
        return 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=800&q=80';
    } elseif (strpos($nama, 'scoopy') !== false) {
        return 'https://images.unsplash.com/photo-1599819811279-d5ad9cccf838?auto=format&fit=crop&w=800&q=80';
    } elseif (strpos($nama, 'pcx') !== false) {
        return 'https://images.unsplash.com/photo-1558980664-3a031cf67ea8?auto=format&fit=crop&w=800&q=80';
    }
    return 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=800&q=80';
}
?>
<section class="catalog-page">
    <div class="catalog-header-banner">
        <div class="catalog-intro">
            <span class="sub-badge">🛵 Armada Premium &amp; Terawat</span>
            <h2>Katalog Sepeda Motor Rental Lowokwaru</h2>
            <p>
                Pilihan armada sepeda motor matic dan manual terbaik di Malang Raya.
                Semua unit selalu diservis berkala, bersih wangi, serta dilengkapi <strong>2 Helm SNI + 2 Jas Hujan Gratis</strong>.
            </p>
        </div>
        <?php if ($sudahLogin): ?>
            <div class="catalog-cta">
                <a href="tambah.php" class="button button-primary button-lg">➕ Tambah Armada Motor</a>
                <a href="../peminjaman/tambah.php" class="button button-accent button-lg">📝 Input Sewa Baru</a>
            </div>
        <?php else: ?>
            <div class="catalog-cta">
                <a href="https://wa.me/6281234567890?text=Halo%20Rental%20Motor%20Lowokwaru,%20saya%20ingin%20tanya%20ketersediaan%20motor%20hari%20ini" target="_blank" class="button button-wa button-lg">
                    💬 Booking Cepat via WhatsApp
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo e($flash['type'] ?? 'info'); ?>">
            <?php echo e($flash['pesan'] ?? $flash); ?>
        </p>
    <?php endif; ?>

    <!-- Filter & Search Toolbar -->
    <div class="catalog-toolbar">
        <form method="get" action="list.php" class="filter-form">
            <div class="search-input-group">
                <span class="search-icon">🔍</span>
                <input type="text" name="q" value="<?php echo e($keyword); ?>" placeholder="Cari nama motor, merk, atau plat nomor...">
            </div>

            <div class="filter-select-group">
                <select name="merk" onchange="this.form.submit()">
                    <option value="">Semua Merk</option>
                    <option value="Honda" <?php echo $filterMerk === 'Honda' ? 'selected' : ''; ?>>Honda</option>
                    <option value="Yamaha" <?php echo $filterMerk === 'Yamaha' ? 'selected' : ''; ?>>Yamaha</option>
                    <option value="Vespa" <?php echo $filterMerk === 'Vespa' ? 'selected' : ''; ?>>Vespa</option>
                </select>

                <select name="status" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="tersedia" <?php echo $filterStatus === 'tersedia' ? 'selected' : ''; ?>>Tersedia (Siap Sewa)</option>
                    <option value="disewa" <?php echo $filterStatus === 'disewa' ? 'selected' : ''; ?>>Sedang Disewa</option>
                    <option value="servis" <?php echo $filterStatus === 'servis' ? 'selected' : ''; ?>>Dalam Servis</option>
                </select>

                <button type="submit" class="button button-primary">Filter</button>
                <?php if ($keyword !== '' || $filterMerk !== '' || $filterStatus !== ''): ?>
                    <a href="list.php" class="button button-secondary">Reset</a>
                <?php endif; ?>
            </div>
        </form>

        <div class="catalog-counts">
            <span>Menampilkan <strong><?php echo count($daftarMotor); ?></strong> dari <strong><?php echo $totalArmada; ?></strong> unit (<?php echo $totalTersedia; ?> unit siap jalan)</span>
        </div>
    </div>

    <!-- Motor Grid Cards -->
    <div class="motor-grid">
        <?php if (empty($daftarMotor)): ?>
            <div class="no-results-card">
                <span class="no-res-icon">🛵</span>
                <h3>Tidak Ada Motor yang Cocok</h3>
                <p>Tidak ditemukan unit motor dengan kata kunci atau filter yang Anda pilih.</p>
                <a href="list.php" class="button button-primary mt-2">Lihat Semua Armada</a>
            </div>
        <?php else: ?>
            <?php foreach ($daftarMotor as $m): ?>
                <?php
                    $imgUrl = getMotorImage($m);
                    $isTersedia = $m['status'] === 'tersedia';
                    $isDisewa = $m['status'] === 'disewa';
                    $waMsg = urlencode("Halo Rental Motor Lowokwaru Malang, saya ingin booking motor " . $m['nama_motor'] . " (" . $m['plat_nomor'] . ") untuk tanggal...");
                ?>
                <article class="motor-card <?php echo !$isTersedia ? 'is-unavailable' : ''; ?>">
                    <div class="motor-media">
                        <img src="<?php echo e($imgUrl); ?>" alt="<?php echo e($m['nama_motor']); ?>" loading="lazy" class="motor-thumb">
                        <div class="motor-badge-status badge-<?php echo e($m['status']); ?>">
                            <span class="status-dot"></span>
                            <?php echo $isTersedia ? 'Siap Sewa' : ($isDisewa ? 'Sedang Disewa' : 'Dalam Servis'); ?>
                        </div>
                        <div class="motor-merk-tag"><?php echo e($m['merk']); ?></div>
                    </div>

                    <div class="motor-body">
                        <div class="motor-title-row">
                            <h3 class="motor-name"><?php echo e($m['nama_motor']); ?></h3>
                            <span class="motor-year"><?php echo (int) $m['tahun']; ?></span>
                        </div>

                        <p class="motor-description">
                            <?php echo e($m['deskripsi'] ?? 'Motor prima dengan performa responsif, bagasi lapang, dan nyaman untuk perjalanan dalam maupun luar kota Malang.'); ?>
                        </p>

                        <div class="motor-specs">
                            <span class="spec-pill" title="Kapasitas Mesin">⚡ <?php echo e($m['tipe_cc'] ?? '125 cc'); ?></span>
                            <span class="spec-pill" title="Tipe Transmisi">⚙️ <?php echo e($m['transmisi'] ?? 'Matic'); ?></span>
                            <span class="spec-pill" title="Plat Nomor">🏷️ <?php echo e($m['plat_nomor']); ?></span>
                        </div>

                        <div class="motor-facilities">
                            <span>✅ 2 Helm SNI</span>
                            <span>✅ 2 Jas Hujan</span>
                            <span>✅ Antar Jemput</span>
                        </div>

                        <div class="motor-footer">
                            <div class="motor-price-box">
                                <span class="price-label">Tarif Harian:</span>
                                <span class="price-value">Rp <?php echo number_format((float) $m['tarif_harian'], 0, ',', '.'); ?></span>
                                <span class="price-period">/ 24 jam</span>
                            </div>

                            <div class="motor-action-group">
                                <?php if ($sudahLogin): ?>
                                    <div class="admin-actions">
                                        <?php if ($isTersedia): ?>
                                            <a href="../peminjaman/tambah.php?motor_id=<?php echo (int) $m['id']; ?>" class="button button-sm button-accent" title="Catat Transaksi Sewa">Sewa Unit</a>
                                        <?php endif; ?>
                                        <a href="edit.php?id=<?php echo (int) $m['id']; ?>" class="button button-sm button-edit">Edit</a>
                                        <form method="post" action="hapus.php" class="inline-form" onsubmit="return confirm('Hapus armada <?php echo htmlspecialchars(addslashes($m['nama_motor'])); ?>?');">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="id" value="<?php echo (int) $m['id']; ?>">
                                            <button type="submit" class="button button-sm button-danger">Hapus</button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <?php if ($isTersedia): ?>
                                        <a href="https://wa.me/6281234567890?text=<?php echo $waMsg; ?>" target="_blank" class="button button-wa-btn">
                                            💬 Sewa via WA
                                        </a>
                                    <?php elseif ($isDisewa): ?>
                                        <a href="https://wa.me/6281234567890?text=<?php echo urlencode("Halo admin, motor " . $m['nama_motor'] . " kapan selesai disewa? Saya mau booking untuk hari berikutnya."); ?>" target="_blank" class="button button-waitlist-btn">
                                            📅 Booking Berikutnya
                                        </a>
                                    <?php else: ?>
                                        <span class="button button-disabled">Sedang Servis</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>