<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$page_title = "Login";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<section class="auth-box">
    <h2>Login Petugas</h2>
    <p class="auth-desc">Masuk untuk mengelola armada rental motor dan data pelanggan.</p>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo e($flash['type'] ?? 'info'); ?>">
            <?php echo e($flash['pesan'] ?? $flash); ?>
        </p>
    <?php endif; ?>

    <form method="post" action="proses_login.php" class="auth-form">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Masukkan username" required autocomplete="username">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Masukkan password" required autocomplete="current-password">
        </div>
        <div class="form-action">
            <button type="submit" class="button button-primary">Masuk</button>
        </div>
    </form>
    <div class="auth-footer-links">
        <p>Belum punya akun petugas? <a href="register.php">Daftar di sini</a></p>
        <p><small>Akun demo: username <code>petugas</code> / password <code>rental123</code></small></p>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
