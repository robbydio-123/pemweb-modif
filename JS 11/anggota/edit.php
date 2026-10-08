<?php
require __DIR__ . '/../includes/auth.php';
$page_title = "Edit Pelanggan";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM pelanggan WHERE id = :id");
$stmt->execute(['id' => $id]);
$pelanggan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pelanggan) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Data pelanggan tidak ditemukan.'];
    header('Location: list.php');
    exit;
}
?>
<section class="form-container">
    <h2>Edit Data Pelanggan</h2>
    <p class="page-description">Perbarui informasi pelanggan Rental Motor Lowokwaru.</p>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo e($flash['type'] ?? 'info'); ?>">
            <?php echo e($flash['pesan'] ?? $flash); ?>
        </p>
    <?php endif; ?>

    <form method="post" action="proses_edit.php" class="crud-form">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="id" value="<?php echo (int) $pelanggan['id']; ?>">

        <div class="form-group">
            <label for="no_pelanggan">No. Pelanggan</label>
            <input type="text" id="no_pelanggan" name="no_pelanggan" value="<?php echo e($pelanggan['no_pelanggan']); ?>" required>
        </div>
        <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" value="<?php echo e($pelanggan['nama']); ?>" required>
        </div>
        <div class="form-group">
            <label for="alamat">Alamat / Domisili</label>
            <textarea id="alamat" name="alamat" rows="3"><?php echo e($pelanggan['alamat'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label for="no_hp">No. HP / WhatsApp</label>
            <input type="text" id="no_hp" name="no_hp" value="<?php echo e($pelanggan['no_hp']); ?>" required>
        </div>
        <div class="form-action">
            <button type="submit" class="button button-primary">Simpan Perubahan</button>
            <a href="list.php" class="button button-secondary">Batal</a>
        </div>
    </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
