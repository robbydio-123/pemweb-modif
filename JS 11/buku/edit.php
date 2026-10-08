<?php
require __DIR__ . '/../includes/auth.php';
$page_title = "Edit Motor";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM motor WHERE id = :id");
$stmt->execute(['id' => $id]);
$motor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$motor) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Data motor tidak ditemukan.'];
    header('Location: list.php');
    exit;
}
?>
<section class="form-container">
    <h2>Edit Data Motor</h2>
    <p class="page-description">Perbarui informasi armada motor Rental Motor Lowokwaru.</p>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo e($flash['type'] ?? 'info'); ?>">
            <?php echo e($flash['pesan'] ?? $flash); ?>
        </p>
    <?php endif; ?>

    <form method="post" action="proses_edit.php" class="crud-form">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="id" value="<?php echo (int) $motor['id']; ?>">

        <div class="form-group">
            <label for="nama_motor">Nama Motor</label>
            <input type="text" id="nama_motor" name="nama_motor" value="<?php echo e($motor['nama_motor']); ?>" required>
        </div>
        <div class="form-group">
            <label for="merk">Merk</label>
            <input type="text" id="merk" name="merk" value="<?php echo e($motor['merk']); ?>" required>
        </div>
        <div class="form-group">
            <label for="tahun">Tahun Pembuatan</label>
            <input type="number" id="tahun" name="tahun" min="2000" max="2099" value="<?php echo (int) $motor['tahun']; ?>" required>
        </div>
        <div class="form-group">
            <label for="plat_nomor">Plat Nomor</label>
            <input type="text" id="plat_nomor" name="plat_nomor" value="<?php echo e($motor['plat_nomor']); ?>" required>
        </div>
        <div class="form-group">
            <label for="tarif_harian">Tarif Sewa Harian (Rp)</label>
            <input type="number" id="tarif_harian" name="tarif_harian" min="0" step="5000" value="<?php echo (float) $motor['tarif_harian']; ?>" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="tersedia" <?php echo $motor['status'] === 'tersedia' ? 'selected' : ''; ?>>Tersedia</option>
                <option value="disewa" <?php echo $motor['status'] === 'disewa' ? 'selected' : ''; ?>>Disewa</option>
                <option value="servis" <?php echo $motor['status'] === 'servis' ? 'selected' : ''; ?>>Servis</option>
            </select>
        </div>
        <div class="form-action">
            <button type="submit" class="button button-primary">Simpan Perubahan</button>
            <a href="list.php" class="button button-secondary">Batal</a>
        </div>
    </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>