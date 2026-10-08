<?php
$page_title = "Beranda";
include __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/koneksi.php';

$totalMotor = $pdo->query("SELECT COUNT(*) FROM motor")->fetchColumn();
$totalPelanggan = $pdo->query("SELECT COUNT(*) FROM pelanggan")->fetchColumn();
$totalDisewa = 0; // Pada Jobsheet 10, modul transaksi belum terintegrasi sehingga statis 0
?>
<section class="hero-section">
    <div class="hero-content">
        <h2>Selamat Datang di Rental Motor Lowokwaru</h2>
        <p class="hero-desc">
            Sistem persewaan sepeda motor terpercaya di Lowokwaru, Malang.
            Melayani sewa harian, mingguan, dan bulanan untuk mahasiswa, wisatawan, dan umum.
        </p>
        <div class="hero-actions">
            <a class="button button-primary" href="<?php echo $base; ?>buku/list.php">🛵 Lihat Katalog Motor</a>
            <?php if (!$sudahLogin): ?>
                <a class="button button-secondary" href="<?php echo $base; ?>auth/login.php">🔐 Login Petugas</a>
            <?php else: ?>
                <a class="button button-secondary" href="<?php echo $base; ?>buku/tambah.php">➕ Tambah Armada Motor</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="summary-section">
    <h2 class="section-title">Ringkasan Sistem</h2>
    <div class="card-grid">
        <article class="stat-card">
            <div class="stat-icon">🏍️</div>
            <div class="stat-info">
                <h3>Total Motor</h3>
                <p class="stat-number"><?php echo (int) $totalMotor; ?></p>
                <small>Armada motor terdaftar</small>
            </div>
        </article>

        <article class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
                <h3>Total Pelanggan</h3>
                <p class="stat-number"><?php echo (int) $totalPelanggan; ?></p>
                <small>Pelanggan terverifikasi</small>
            </div>
        </article>

        <article class="stat-card">
            <div class="stat-icon">⏱️</div>
            <div class="stat-info">
                <h3>Sedang Disewa</h3>
                <p class="stat-number"><?php echo (int) $totalDisewa; ?></p>
                <small>Status peminjaman aktif</small>
            </div>
        </article>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
