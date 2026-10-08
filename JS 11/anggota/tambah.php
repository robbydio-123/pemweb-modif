<?php
require __DIR__ . '/../includes/auth.php';
$page_title = "Tambah Pelanggan";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$nextNo = 'P' . str_pad((string) (mt_rand(100, 999)), 3, '0', STR_PAD_LEFT);
?>
<section class="form-container">
    <h2>Tambah Data Pelanggan</h2>
    <p class="page-description">Daftarkan pelanggan / penyewa baru Rental Motor Lowokwaru.</p>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo e($flash['type'] ?? 'info'); ?>">
            <?php echo e($flash['pesan'] ?? $flash); ?>
        </p>
    <?php endif; ?>

    <form method="post" action="proses_tambah.php" class="crud-form">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label for="no_pelanggan">No. Pelanggan</label>
            <input type="text" id="no_pelanggan" name="no_pelanggan" value="<?php echo e($nextNo); ?>" required>
        </div>
        <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" placeholder="Contoh: Siti Aminah" required>
        </div>
        <div class="form-group">
            <label for="alamat">Alamat / Asal Kampus / Domisili</label>
            <textarea id="alamat" name="alamat" rows="3" placeholder="Contoh: Jl. Soekarno Hatta No. 45, Malang (Kos Putri Melati)"></textarea>
        </div>
        <div class="form-group">
            <label for="no_hp">No. HP / WhatsApp Aktif</label>
            <input type="text" id="no_hp" name="no_hp" placeholder="Contoh: 081234567890" required>
        </div>
        <div class="form-action">
            <button type="submit" class="button button-primary">Simpan Pelanggan</button>
            <a href="list.php" class="button button-secondary">Kembali</a>
        </div>
    </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>