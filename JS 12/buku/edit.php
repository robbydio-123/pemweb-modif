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
    <div class="card-header-styled">
        <span class="badge-icon">✏️</span>
        <div>
            <h2>Edit Data Armada Motor</h2>
            <p class="page-description">Perbarui informasi spesifikasi, tarif, dan foto motor.</p>
        </div>
    </div>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo e($flash['type'] ?? 'info'); ?>">
            <?php echo e($flash['pesan'] ?? $flash); ?>
        </p>
    <?php endif; ?>

    <form method="post" action="proses_edit.php" class="crud-form">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="id" value="<?php echo (int) $motor['id']; ?>">

        <div class="form-row-2">
            <div class="form-group">
                <label for="nama_motor">Nama &amp; Model Motor <span class="required">*</span></label>
                <input type="text" id="nama_motor" name="nama_motor" value="<?php echo e($motor['nama_motor']); ?>" required>
            </div>
            <div class="form-group">
                <label for="merk">Merk Pabrikan <span class="required">*</span></label>
                <input type="text" id="merk" name="merk" value="<?php echo e($motor['merk']); ?>" required>
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label for="tahun">Tahun Pembuatan <span class="required">*</span></label>
                <input type="number" id="tahun" name="tahun" min="2000" max="2099" value="<?php echo (int) $motor['tahun']; ?>" required>
            </div>
            <div class="form-group">
                <label for="plat_nomor">Plat Nomor <span class="required">*</span></label>
                <input type="text" id="plat_nomor" name="plat_nomor" value="<?php echo e($motor['plat_nomor']); ?>" required>
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label for="tipe_cc">Kapasitas Mesin (CC)</label>
                <input type="text" id="tipe_cc" name="tipe_cc" value="<?php echo e($motor['tipe_cc'] ?? '125 cc'); ?>">
            </div>
            <div class="form-group">
                <label for="transmisi">Tipe Transmisi</label>
                <select id="transmisi" name="transmisi">
                    <option value="Matic" <?php echo ($motor['transmisi'] ?? '') === 'Matic' ? 'selected' : ''; ?>>Matic (Otomatis)</option>
                    <option value="Matic Maxi" <?php echo ($motor['transmisi'] ?? '') === 'Matic Maxi' ? 'selected' : ''; ?>>Matic Maxi (Besar/Touring)</option>
                    <option value="Matic Retro" <?php echo ($motor['transmisi'] ?? '') === 'Matic Retro' ? 'selected' : ''; ?>>Matic Retro / Klasik</option>
                    <option value="Manual / Kopling" <?php echo ($motor['transmisi'] ?? '') === 'Manual / Kopling' ? 'selected' : ''; ?>>Manual / Kopling</option>
                </select>
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label for="tarif_harian">Tarif Sewa Harian (Rp) <span class="required">*</span></label>
                <input type="number" id="tarif_harian" name="tarif_harian" min="0" step="5000" value="<?php echo (float) $motor['tarif_harian']; ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status Ketersediaan</label>
                <select id="status" name="status" required>
                    <option value="tersedia" <?php echo $motor['status'] === 'tersedia' ? 'selected' : ''; ?>>Tersedia (Siap Sewa)</option>
                    <option value="disewa" <?php echo $motor['status'] === 'disewa' ? 'selected' : ''; ?>>Sedang Disewa</option>
                    <option value="servis" <?php echo $motor['status'] === 'servis' ? 'selected' : ''; ?>>Dalam Servis</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="gambar">URL Gambar Motor</label>
            <input type="url" id="gambar" name="gambar" value="<?php echo e($motor['gambar'] ?? ''); ?>" placeholder="https://images.unsplash.com/...">
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi &amp; Keunggulan Motor</label>
            <textarea id="deskripsi" name="deskripsi" rows="3"><?php echo e($motor['deskripsi'] ?? ''); ?></textarea>
        </div>

        <div class="form-action">
            <button type="submit" class="button button-primary">Simpan Perubahan</button>
            <a href="list.php" class="button button-secondary">Batal</a>
        </div>
    </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>