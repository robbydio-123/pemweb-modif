<?php
require __DIR__ . '/../includes/auth.php';
$page_title = "Tambah Motor";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<section class="form-container">
    <div class="card-header-styled">
        <span class="badge-icon">➕</span>
        <div>
            <h2>Tambah Armada Motor Baru</h2>
            <p class="page-description">Lengkapi data sepeda motor baru untuk ditampilkan di katalog Rental Motor Lowokwaru.</p>
        </div>
    </div>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo e($flash['type'] ?? 'info'); ?>">
            <?php echo e($flash['pesan'] ?? $flash); ?>
        </p>
    <?php endif; ?>

    <form method="post" action="proses_tambah.php" class="crud-form">
        <?php echo csrf_field(); ?>

        <div class="form-row-2">
            <div class="form-group">
                <label for="nama_motor">Nama &amp; Model Motor <span class="required">*</span></label>
                <input type="text" id="nama_motor" name="nama_motor" placeholder="Contoh: All New Vario 160 ABS" required>
            </div>
            <div class="form-group">
                <label for="merk">Merk Pabrikan <span class="required">*</span></label>
                <input type="text" id="merk" name="merk" placeholder="Contoh: Honda / Yamaha / Vespa" required>
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label for="tahun">Tahun Pembuatan <span class="required">*</span></label>
                <input type="number" id="tahun" name="tahun" min="2000" max="2099" value="<?php echo date('Y'); ?>" required>
            </div>
            <div class="form-group">
                <label for="plat_nomor">Plat Nomor (Unik) <span class="required">*</span></label>
                <input type="text" id="plat_nomor" name="plat_nomor" placeholder="Contoh: N 3303 EF" required>
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label for="tipe_cc">Kapasitas Mesin (CC)</label>
                <input type="text" id="tipe_cc" name="tipe_cc" placeholder="Contoh: 160 cc / 155 cc / 110 cc" value="125 cc">
            </div>
            <div class="form-group">
                <label for="transmisi">Tipe Transmisi</label>
                <select id="transmisi" name="transmisi">
                    <option value="Matic">Matic (Otomatis)</option>
                    <option value="Matic Maxi">Matic Maxi (Besar/Touring)</option>
                    <option value="Matic Retro">Matic Retro / Klasik</option>
                    <option value="Manual / Kopling">Manual / Kopling</option>
                </select>
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label for="tarif_harian">Tarif Sewa Harian (Rp) <span class="required">*</span></label>
                <input type="number" id="tarif_harian" name="tarif_harian" min="0" step="5000" placeholder="Contoh: 120000" required>
            </div>
            <div class="form-group">
                <label for="status">Status Ketersediaan</label>
                <select id="status" name="status" required>
                    <option value="tersedia">Tersedia (Siap Sewa)</option>
                    <option value="disewa">Sedang Disewa</option>
                    <option value="servis">Dalam Servis</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="gambar">URL Gambar Motor (Foto Keren &amp; Tajam)</label>
            <input type="url" id="gambar" name="gambar" placeholder="https://images.unsplash.com/... atau link foto motor">
            <small class="form-hint">Jika dikosongkan, sistem akan otomatis memilih foto motor default berkualitas tinggi.</small>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi &amp; Keunggulan Motor</label>
            <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Contoh: Mesin bertenaga 160cc, bagasi luas muat helm, USB charger, tarikan enteng untuk rute Malang - Batu."></textarea>
        </div>

        <div class="form-action">
            <button type="submit" class="button button-primary">Simpan Armada Motor</button>
            <a href="list.php" class="button button-secondary">Kembali ke Katalog</a>
        </div>
    </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>