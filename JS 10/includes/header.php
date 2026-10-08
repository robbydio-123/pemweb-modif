<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$sudahLogin = isset($_SESSION['user_id']);

// Prefix relatif ke root proyek ini (bukan root domain)
$__jobsheetRoot = dirname(__DIR__);
$__scriptDir = dirname($_SERVER['SCRIPT_FILENAME']);
$__rel = ltrim(str_replace('\\', '/', substr($__scriptDir, strlen($__jobsheetRoot))), '/');
$base = $__rel === '' ? '' : str_repeat('../', substr_count($__rel, '/') + 1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rental Motor Lowokwaru<?php echo isset($page_title) ? ' | ' . $page_title : ''; ?></title>
    <link rel="stylesheet" href="<?php echo $base; ?>assets/css/style.css">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="brand">
                <a href="<?php echo $base; ?>index.php" class="brand-link">
                    <span class="brand-logo">🛵</span>
                    <span class="brand-text">
                        <strong>RENTAL MOTOR</strong>
                        <small>LOWOKWARU MALANG</small>
                    </span>
                </a>
            </div>
            <button type="button" id="nav-toggle-btn" class="nav-toggle-label" aria-label="Menu">&#9776;</button>
            <nav id="main-nav">
                <ul>
                    <li><a href="<?php echo $base; ?>index.php" class="<?php echo ($page_title ?? '') === 'Beranda' ? 'active' : ''; ?>">Beranda</a></li>
                    <li><a href="<?php echo $base; ?>buku/list.php" class="<?php echo ($page_title ?? '') === 'Daftar Motor' ? 'active' : ''; ?>">Daftar Motor</a></li>
                    <?php if ($sudahLogin): ?>
                    <li><a href="<?php echo $base; ?>buku/tambah.php" class="<?php echo ($page_title ?? '') === 'Tambah Motor' ? 'active' : ''; ?>">Tambah Motor</a></li>
                    <li><a href="<?php echo $base; ?>anggota/list.php" class="<?php echo ($page_title ?? '') === 'Daftar Pelanggan' ? 'active' : ''; ?>">Daftar Pelanggan</a></li>
                    <li><a href="<?php echo $base; ?>anggota/tambah.php" class="<?php echo ($page_title ?? '') === 'Tambah Pelanggan' ? 'active' : ''; ?>">Tambah Pelanggan</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <div class="auth-status">
                <?php if ($sudahLogin): ?>
                    <span class="user-badge">👤 <?php echo htmlspecialchars($_SESSION['nama'] ?? 'Petugas'); ?></span>
                    <a href="<?php echo $base; ?>auth/logout.php" class="btn-logout">Logout</a>
                <?php else: ?>
                    <a href="<?php echo $base; ?>auth/login.php" class="btn-login">Login</a>
                    <a href="<?php echo $base; ?>auth/register.php" class="btn-register">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="container">