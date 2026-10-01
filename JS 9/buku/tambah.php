<?php
session_start(); require __DIR__ . '/../includes/auth.php'; require_admin(); require __DIR__ . '/../includes/koneksi.php';
$editId = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT); $motor = [];
if ($editId) { $stmt = $pdo->prepare('SELECT * FROM motor WHERE id = :id'); $stmt->execute(['id' => $editId]); $motor = $stmt->fetch(PDO::FETCH_ASSOC) ?: []; }
$isEdit = !empty($motor); $page_title = $isEdit ? 'Edit Motor' : 'Tambah Motor'; require __DIR__ . '/../includes/header.php';
?><section><h2><?php echo $page_title; ?></h2>
<?php if (!empty($_SESSION['flash'])): $flash = $_SESSION['flash']; unset($_SESSION['flash']); ?><p class="flash flash-<?php echo htmlspecialchars($flash['type']); ?>"><?php echo htmlspecialchars($flash['pesan']); ?></p><?php endif; ?>
<form method="post" action="<?php echo $isEdit ? 'proses_ubah.php' : 'proses_tambah.php'; ?>"><?php if ($isEdit): ?><input type="hidden" name="id" value="<?php echo (int) $motor['id']; ?>"><?php endif; ?>
<p><label for="nama_motor">Nama Motor</label><br><input type="text" id="nama_motor" name="nama_motor" value="<?php echo htmlspecialchars($motor['nama_motor'] ?? ''); ?>" required></p>
<p><label for="merk">Merk</label><br><input type="text" id="merk" name="merk" value="<?php echo htmlspecialchars($motor['merk'] ?? ''); ?>" required></p>
<p><label for="tahun">Tahun</label><br><input type="number" id="tahun" name="tahun" min="1990" max="2100" value="<?php echo htmlspecialchars((string) ($motor['tahun'] ?? '')); ?>" required></p>
<p><label for="plat_nomor">Plat Nomor</label><br><input type="text" id="plat_nomor" name="plat_nomor" value="<?php echo htmlspecialchars($motor['plat_nomor'] ?? ''); ?>" required></p>
<p><label for="tarif_harian">Tarif per Hari</label><br><input type="number" id="tarif_harian" name="tarif_harian" min="0" step="1000" value="<?php echo htmlspecialchars((string) ($motor['tarif_harian'] ?? '')); ?>" required></p>
<p><label for="status">Status</label><br><select id="status" name="status"><option value="tersedia" <?php echo ($motor['status'] ?? '') === 'tersedia' ? 'selected' : ''; ?>>Tersedia</option><option value="disewa" <?php echo ($motor['status'] ?? '') === 'disewa' ? 'selected' : ''; ?>>Disewa</option><option value="servis" <?php echo ($motor['status'] ?? '') === 'servis' ? 'selected' : ''; ?>>Servis</option></select></p><p><button type="submit">Simpan</button></p></form></section><?php require __DIR__ . '/../includes/footer.php'; ?>