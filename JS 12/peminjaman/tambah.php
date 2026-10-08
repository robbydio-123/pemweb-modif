<?php
require __DIR__ . '/../includes/auth.php';
$page_title = "Sewa Motor Baru";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$daftarPelanggan = $pdo->query("SELECT * FROM pelanggan ORDER BY nama ASC")->fetchAll(PDO::FETCH_ASSOC);

// Hanya tampilkan motor yang statusnya 'tersedia'
$daftarMotor = $pdo->query("SELECT * FROM motor WHERE status = 'tersedia' ORDER BY nama_motor ASC")->fetchAll(PDO::FETCH_ASSOC);

$selectedMotorId = (int) ($_GET['motor_id'] ?? 0);
?>
<section class="form-container transaction-card">
    <div class="card-header-styled">
        <span class="badge-icon">📝</span>
        <div>
            <h2>Input Transaksi Sewa Motor</h2>
            <p class="page-description">Catat peminjaman/penyewaan armada motor untuk pelanggan.</p>
        </div>
    </div>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo e($flash['type'] ?? 'info'); ?>">
            <?php echo e($flash['pesan'] ?? $flash); ?>
        </p>
    <?php endif; ?>

    <?php if (empty($daftarMotor)): ?>
        <div class="alert alert-warning">
            <p>⚠️ <strong>Semua unit motor saat ini sedang disewa atau dalam servis.</strong></p>
            <p>Tidak ada armada motor dengan status <em>Tersedia</em> saat ini. Silakan proses pengembalian unit terlebih dahulu atau tambah motor baru.</p>
            <p class="mt-2"><a href="kembali.php" class="button button-sm button-primary">Ke Menu Pengembalian</a></p>
        </div>
    <?php else: ?>
        <form method="post" action="proses_tambah.php" class="crud-form" id="form-sewa">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label for="pelanggan_id">Pilih Pelanggan <span class="required">*</span></label>
                <select id="pelanggan_id" name="pelanggan_id" required>
                    <option value="">-- Pilih Pelanggan Terdaftar --</option>
                    <?php foreach ($daftarPelanggan as $pelanggan): ?>
                        <option value="<?php echo (int) $pelanggan['id']; ?>">
                            <?php echo e($pelanggan['nama']); ?> (<?php echo e($pelanggan['no_pelanggan']); ?> - <?php echo e($pelanggan['no_hp']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="form-hint">Pelanggan belum terdaftar? <a href="../anggota/tambah.php" target="_blank">Tambah pelanggan baru di sini</a>.</small>
            </div>

            <div class="form-group">
                <label for="motor_id">Pilih Unit Motor (Hanya Unit Tersedia) <span class="required">*</span></label>
                <select id="motor_id" name="motor_id" required>
                    <option value="" data-tarif="0">-- Pilih Armada Motor --</option>
                    <?php foreach ($daftarMotor as $motor): ?>
                        <option value="<?php echo (int) $motor['id']; ?>"
                                data-tarif="<?php echo (float) $motor['tarif_harian']; ?>"
                                <?php echo $selectedMotorId === (int) $motor['id'] ? 'selected' : ''; ?>>
                            <?php echo e($motor['nama_motor']); ?> (<?php echo e($motor['plat_nomor']); ?>) - Rp <?php echo number_format((float) $motor['tarif_harian'], 0, ',', '.'); ?>/hari
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="tanggal_pinjam">Tanggal Mulai Sewa <span class="required">*</span></label>
                    <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" value="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <div class="form-group">
                    <label for="durasi_hari">Durasi Sewa (Hari) <span class="required">*</span></label>
                    <input type="number" id="durasi_hari" name="durasi_hari" min="1" max="30" value="1" required>
                </div>
            </div>

            <div class="cost-calculator-card">
                <div class="calc-label">Estimasi Total Biaya Sewa:</div>
                <div class="calc-amount" id="display-total">Rp 0</div>
                <small class="calc-sub">Dihitung otomatis: tarif harian &times; durasi sewa</small>
            </div>

            <div class="form-action">
                <button type="submit" class="button button-primary">💾 Proses & Simpan Transaksi</button>
                <a href="../buku/list.php" class="button button-secondary">Batal</a>
            </div>
        </form>
    <?php endif; ?>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const motorSelect = document.getElementById('motor_id');
    const durasiInput = document.getElementById('durasi_hari');
    const displayTotal = document.getElementById('display-total');

    function hitungTotal() {
        if (!motorSelect || !durasiInput || !displayTotal) return;
        const selectedOpt = motorSelect.options[motorSelect.selectedIndex];
        const tarif = selectedOpt ? parseFloat(selectedOpt.getAttribute('data-tarif') || 0) : 0;
        const durasi = parseInt(durasiInput.value) || 1;
        const total = tarif * durasi;
        displayTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    if (motorSelect && durasiInput) {
        motorSelect.addEventListener('change', hitungTotal);
        durasiInput.addEventListener('input', hitungTotal);
        hitungTotal();
    }
});
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>
