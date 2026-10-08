<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$page_title = "Registrasi Petugas";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<section class="auth-box">
    <h2>Registrasi Petugas Rental</h2>
    <p class="auth-desc">Daftarkan akun petugas baru untuk mengelola Rental Motor Lowokwaru.</p>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo htmlspecialchars($flash['type'] ?? 'info'); ?>">
            <?php echo htmlspecialchars($flash['pesan'] ?? $flash); ?>
        </p>
    <?php endif; ?>

    <form method="post" action="proses_register.php" class="auth-form">
        <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" placeholder="Contoh: Budi Santoso" required>
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Contoh: budi_rental" required autocomplete="username">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Minimal 6 karakter" required minlength="6" autocomplete="new-password">
        </div>
        <div class="form-action">
            <button type="submit" class="button button-primary">Daftar Akun</button>
        </div>
    </form>
    <div class="auth-footer-links">
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
