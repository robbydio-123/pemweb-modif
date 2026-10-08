<?php
$page_title = "Beranda";
include __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/koneksi.php';

$totalMotor = (int) $pdo->query("SELECT COUNT(*) FROM motor")->fetchColumn();
$totalTersedia = (int) $pdo->query("SELECT COUNT(*) FROM motor WHERE status = 'tersedia'")->fetchColumn();
$totalPelanggan = (int) $pdo->query("SELECT COUNT(*) FROM pelanggan")->fetchColumn();

// Jobsheet 12: Kartu "Sedang Dipinjam" dihitung dinamis dari tabel peminjaman
$totalDipinjam = (int) $pdo->query("SELECT COUNT(*) FROM peminjaman WHERE status = 'dipinjam'")->fetchColumn();
$totalSelesai = (int) $pdo->query("SELECT COUNT(*) FROM peminjaman WHERE status = 'selesai'")->fetchColumn();

// Ambil 4 motor unggulan untuk ditampilkan di beranda
$motorUnggulan = $pdo->query("SELECT * FROM motor ORDER BY id ASC LIMIT 4")->fetchAll(PDO::FETCH_ASSOC);

function getHeroImg($motor) {
    if (!empty($motor['gambar'])) return $motor['gambar'];
    return 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=800&q=80';
}
?>
<div class="landing-page">
    <!-- Hero Section -->
    <section class="hero-premium">
        <div class="hero-badge-pill">
            <span class="sparkle">⭐</span> Rental Motor No. 1 di Lowokwaru Malang
        </div>
        <h1 class="hero-main-title">
            Jelajahi Malang &amp; Bromo Lebih Nyaman dengan <span class="highlight-text">Armada Prima</span>
        </h1>
        <p class="hero-subtext">
            Sedia sewa motor harian, mingguan, &amp; bulanan dengan unit keluaran terbaru.
            Fasilitas komplit <strong>2 Helm SNI + 2 Jas Hujan Gratis</strong>, serta layanan antar-jemput stasiun, hotel, dan kampus UB, Polinema, UM, UMM!
        </p>

        <div class="hero-button-group">
            <a href="buku/list.php" class="btn-hero-primary">🛵 Lihat Katalog &amp; Tarif Motor</a>
            <a href="https://wa.me/6281234567890?text=Halo%20Rental%20Motor%20Lowokwaru,%20saya%20ingin%20sewa%20motor%20hari%20ini" target="_blank" class="btn-hero-wa">
                💬 Chat Booking WhatsApp (Fast Respon)
            </a>
            <?php if ($sudahLogin): ?>
                <a href="peminjaman/tambah.php" class="btn-hero-admin">📝 Input Sewa Baru</a>
            <?php endif; ?>
        </div>

        <div class="hero-trust-badges">
            <div class="trust-item">
                <span class="trust-icon">🌟</span>
                <span>Rating <strong>4.9/5</strong> dari 1.200+ Mahasiswa &amp; Wisatawan</span>
            </div>
            <div class="trust-item">
                <span class="trust-icon">🛡️</span>
                <span><strong>100% Bebas Mogok</strong> Servis Rutin Dealer Resmi</span>
            </div>
            <div class="trust-item">
                <span class="trust-icon">⚡</span>
                <span><strong>Proses Cepat 5 Menit</strong> Tanpa Jaminan Ribet</span>
            </div>
        </div>
    </section>

    <!-- Ringkasan Statistik Sistem (Jobsheet 12 Dynamic Metrics) -->
    <section class="metrics-section">
        <div class="section-title-wrap">
            <h2>Statistik Operasional Rental</h2>
            <p>Data pemantauan armada dan transaksi rental motor terintegrasi secara real-time.</p>
        </div>

        <div class="metrics-grid">
            <article class="metric-card card-motor">
                <div class="metric-icon-wrap">🏍️</div>
                <div class="metric-content">
                    <span class="metric-label">Total Armada</span>
                    <strong class="metric-value"><?php echo $totalMotor; ?></strong>
                    <span class="metric-desc">Sepeda motor terdaftar</span>
                </div>
            </article>

            <article class="metric-card card-tersedia">
                <div class="metric-icon-wrap">✨</div>
                <div class="metric-content">
                    <span class="metric-label">Siap Disewa</span>
                    <strong class="metric-value"><?php echo $totalTersedia; ?></strong>
                    <span class="metric-desc">Unit tersedia di garasi</span>
                </div>
            </article>

            <article class="metric-card card-dipinjam">
                <div class="metric-icon-wrap">⏱️</div>
                <div class="metric-content">
                    <span class="metric-label">Sedang Disewa</span>
                    <strong class="metric-value"><?php echo $totalDipinjam; ?></strong>
                    <span class="metric-desc">Transaksi sewa aktif berjalan</span>
                </div>
            </article>

            <article class="metric-card card-pelanggan">
                <div class="metric-icon-wrap">👥</div>
                <div class="metric-content">
                    <span class="metric-label">Total Pelanggan</span>
                    <strong class="metric-value"><?php echo $totalPelanggan; ?></strong>
                    <span class="metric-desc">Pelanggan terverifikasi</span>
                </div>
            </article>

            <article class="metric-card card-selesai">
                <div class="metric-icon-wrap">🏆</div>
                <div class="metric-content">
                    <span class="metric-label">Selesai Kembali</span>
                    <strong class="metric-value"><?php echo $totalSelesai; ?></strong>
                    <span class="metric-desc">Transaksi selesai sukses</span>
                </div>
            </article>
        </div>
    </section>

    <!-- Keunggulan Layanan -->
    <section class="features-section">
        <div class="section-title-wrap">
            <h2>Kenapa Pilih Rental Motor Lowokwaru?</h2>
            <p>Pengalaman sewa motor nomor satu di Malang dengan kenyamanan dan keamanan terjamin.</p>
        </div>

        <div class="features-grid">
            <div class="feature-box">
                <div class="feature-icon">🎁</div>
                <h3>Fasilitas Sewa Lengkap</h3>
                <p>Setiap sewa sudah termasuk 2 helm SNI bersih &amp; wangi, 2 jas hujan anti bocor, plus phone holder untuk kemudahan GPS navigasi.</p>
            </div>
            <div class="feature-box">
                <div class="feature-icon">🚚</div>
                <h3>Layanan Antar-Jemput Unit</h3>
                <p>Unit kami antarkan langsung ke Stasiun Malang Kota Baru, Terminal Arjosari, hotel penginapan, kos, maupun kampus area Lowokwaru.</p>
            </div>
            <div class="feature-box">
                <div class="feature-icon">🔧</div>
                <h3>Kondisi Motor Selalu Prima</h3>
                <p>Semua motor dirawat intensif, ban selalu prima, rem terawat, oli rutin diganti, sehingga aman untuk rute tanjakan Batu maupun Bromo.</p>
            </div>
            <div class="feature-box">
                <div class="feature-icon">📄</div>
                <h3>Syarat Mudah &amp; Transparan</h3>
                <p>Cukup titip jaminan identitas (KTM mahasiswa / KTP / SIM A). Tidak ada biaya deposit tersembunyi atau potongan siluman.</p>
            </div>
        </div>
    </section>

    <!-- Armada Motor Unggulan -->
    <section class="featured-motors-section">
        <div class="section-header-flex">
            <div>
                <h2>Armada Terpopuler</h2>
                <p>Motor favorit mahasiswa dan wisatawan yang paling diminati setiap akhir pekan.</p>
            </div>
            <a href="buku/list.php" class="view-all-link">Lihat Semua Armada &rarr;</a>
        </div>

        <div class="motor-grid-preview">
            <?php foreach ($motorUnggulan as $mu): ?>
                <?php
                    $isTersedia = $mu['status'] === 'tersedia';
                    $img = getHeroImg($mu);
                    $waLink = "https://wa.me/6281234567890?text=" . urlencode("Halo Rental Motor Lowokwaru, saya tertarik sewa motor " . $mu['nama_motor'] . " (" . $mu['plat_nomor'] . ")");
                ?>
                <div class="motor-card">
                    <div class="motor-media">
                        <img src="<?php echo e($img); ?>" alt="<?php echo e($mu['nama_motor']); ?>" loading="lazy">
                        <div class="motor-badge-status badge-<?php echo e($mu['status']); ?>">
                            <span class="status-dot"></span>
                            <?php echo $isTersedia ? 'Siap Sewa' : ($mu['status'] === 'disewa' ? 'Sedang Disewa' : 'Servis'); ?>
                        </div>
                        <div class="motor-merk-tag"><?php echo e($mu['merk']); ?></div>
                    </div>
                    <div class="motor-body">
                        <div class="motor-title-row">
                            <h3 class="motor-name"><?php echo e($mu['nama_motor']); ?></h3>
                            <span class="motor-year"><?php echo (int) $mu['tahun']; ?></span>
                        </div>
                        <p class="motor-description"><?php echo e($mu['deskripsi'] ?? 'Motor terawat, bensin irit, tarikan enteng dan bagasi lapang.'); ?></p>
                        <div class="motor-specs">
                            <span class="spec-pill">⚡ <?php echo e($mu['tipe_cc'] ?? '125 cc'); ?></span>
                            <span class="spec-pill">⚙️ <?php echo e($mu['transmisi'] ?? 'Matic'); ?></span>
                            <span class="spec-pill">🏷️ <?php echo e($mu['plat_nomor']); ?></span>
                        </div>
                        <div class="motor-footer">
                            <div class="motor-price-box">
                                <span class="price-label">Tarif:</span>
                                <span class="price-value">Rp <?php echo number_format((float) $mu['tarif_harian'], 0, ',', '.'); ?></span>
                                <span class="price-period">/ hari</span>
                            </div>
                            <?php if ($isTersedia): ?>
                                <a href="<?php echo $waLink; ?>" target="_blank" class="button button-sm button-wa-btn">💬 Sewa via WA</a>
                            <?php else: ?>
                                <span class="button button-sm button-disabled">Disewa</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Testimoni Pelanggan -->
    <section class="testimonial-section">
        <div class="section-title-wrap">
            <h2>Apa Kata Pelanggan Kami?</h2>
            <p>Ulasan jujur dari teman-teman mahasiswa dan wisatawan yang telah menyewa motor di Lowokwaru.</p>
        </div>

        <div class="testimonial-grid">
            <div class="testimonial-card">
                <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
                <p class="testimonial-text">"Sewa Vario 160 buat liburan ke Batu dan Bromo 3 hari. Motornya super mulus, tarikan enteng di tanjakan, helmnya wangi dan bersih banget. Pasti bakal langganan lagi!"</p>
                <div class="testimonial-author">
                    <strong>Dimas Pratama</strong>
                    <small>Wisatawan Asal Jakarta</small>
                </div>
            </div>

            <div class="testimonial-card">
                <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
                <p class="testimonial-text">"Pelayanan ramah banget! Motor Beat Street dianterin langsung ke kos di Sigura-gura tepat waktu. Syarat cuma KTM UB, proses gak ribet sama sekali."</p>
                <div class="testimonial-author">
                    <strong>Anisa Rahmawati</strong>
                    <small>Mahasiswi Universitas Brawijaya</small>
                </div>
            </div>

            <div class="testimonial-card">
                <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
                <p class="testimonial-text">"NMAX Connected-nya mantap pol! Buat touring ke pantai Malang Selatan nyaman banget, suspensi empuk dan gak bikin capek. Harganya bersahabat buat kantong mahasiswa."</p>
                <div class="testimonial-author">
                    <strong>Rian Saputra</strong>
                    <small>Mahasiswa Polinema Suhat</small>
                </div>
            </div>
        </div>
    </section>

    <!-- Cara Sewa 3 Langkah -->
    <section class="steps-section">
        <div class="section-title-wrap">
            <h2>Cara Sewa Motor Gampang &amp; Cepat</h2>
            <p>Hanya 3 langkah praktis dan armada motor siap meluncur ke lokasi Anda.</p>
        </div>

        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <h3>Pilih Motor</h3>
                <p>Buka katalog dan pilih unit motor yang sesuai dengan kebutuhan perjalanan dan budget Anda.</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <h3>Konfirmasi WhatsApp</h3>
                <p>Klik tombol Sewa via WA, kirim foto identitas (KTP/KTM), dan tentukan jadwal serta lokasi antar-jemput.</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <h3>Motor Diantar &amp; Gas!</h3>
                <p>Petugas kami mengantarkan unit beserta 2 helm &amp; jas hujan ke lokasi Anda. Siap jalan tanpa repot!</p>
            </div>
        </div>
    </section>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
