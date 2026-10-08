<?php
require __DIR__ . '/../includes/auth.php';
$page_title = "Tambah Motor";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<section class="form-container">
    <h2>Tambah Armada Motor</h2>
    <p class="page-description">Daftarkan sepeda motor baru ke armada Rental Motor Lowokwaru.</p>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo htmlspecialchars($flash['type'] ?? 'info'); ?>">
            <?php echo htmlspecialchars($flash['pesan'] ?? $flash); ?>
        </p>
    <?php endif; ?>

    <form method="post" action="proses_tambah.php" class="crud-form">
        <div class="form-group">
            <label for="nama_motor">Nama Motor</label>
            <input type="text" id="nama_motor" name="nama_motor" placeholder="Contoh: Honda Vario 160" required>
        </div>
        <div class="form-group">
            <label for="merk">Merk</label>
            <input type="text" id="merk" name="merk" placeholder="Contoh: Honda / Yamaha / Vespa" required>
        </div>
        <div class="form-group">
            <label for="tahun">Tahun Pembuatan</label>
            <input type="number" id="tahun" name="tahun" min="2000" max="2099" value="2023" required>
        </div>
        <div class="form-group">
            <label for="plat_nomor">Plat Nomor</label>
            <input type="text" id="plat_nomor" name="plat_nomor" placeholder="Contoh: N 1234 AB" required>
        </div>
        <div class="form-group">
            <label for="tarif_harian">Tarif Sewa Harian (Rp)</label>
            <input type="number" id="tarif_harian" name="tarif_harian" min="0" step="5000" placeholder="Contoh: 100000" required>
        </div>
        <div class="form-group">
            <label for="status">Status Awal</label>
            <select id="status" name="status" required>
                <option value="tersedia">Tersedia</option>
                <option value="disewa">Disewa</option>
                <option value="servis">Servis</option>
            </select>
        </div>
        <div class="form-action">
            <button type="submit" class="button button-primary">Simpan Motor</button>
            <a href="list.php" class="button button-secondary">Kembali</a>
        </div>
    </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>