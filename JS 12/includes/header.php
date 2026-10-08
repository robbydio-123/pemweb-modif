<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$sudahLogin = isset($_SESSION['user_id']);

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/csrf.php';

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
    <title>Rental Motor Lowokwaru Malang<?php echo isset($page_title) ? ' | ' . e($page_title) : ''; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base; ?>assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="header-container">
            <div class="brand">
                <a href="<?php echo $base; ?>index.php" class="brand-link">
                    <span class="brand-badge">🛵</span>
                    <span class="brand-title">
                        <strong class="brand-name">LOWOKWARU</strong>
                        <span class="brand-subtitle">MOTOR RENTAL MALANG</span>
                    </span>
                </a>
            </div>
            <button type="button" id="nav-toggle-btn" class="nav-toggle-btn" aria-label="Buka Menu">
                <span></span><span></span><span></span>
            </button>
            <nav id="main-nav" class="nav-menu">
                <ul class="nav-list">
                    <li><a href="<?php echo $base; ?>index.php" class="<?php echo ($page_title ?? '') === 'Beranda' ? 'active' : ''; ?>">Beranda</a></li>
                    <li><a href="<?php echo $base; ?>buku/list.php" class="<?php echo ($page_title ?? '') === 'Katalog Motor' || ($page_title ?? '') === 'Daftar Motor' ? 'active' : ''; ?>">Katalog Motor</a></li>
                    <?php if ($sudahLogin): ?>
                    <li><a href="<?php echo $base; ?>buku/tambah.php" class="<?php echo ($page_title ?? '') === 'Tambah Motor' ? 'active' : ''; ?>">Tambah Motor</a></li>
                    <li><a href="<?php echo $base; ?>anggota/list.php" class="<?php echo ($page_title ?? '') === 'Daftar Pelanggan' ? 'active' : ''; ?>">Pelanggan</a></li>
                    <li><a href="<?php echo $base; ?>peminjaman/tambah.php" class="<?php echo ($page_title ?? '') === 'Sewa Motor Baru' ? 'active' : ''; ?>">Sewa Baru</a></li>
                    <li><a href="<?php echo $base; ?>peminjaman/kembali.php" class="<?php echo ($page_title ?? '') === 'Pengembalian Motor' ? 'active' : ''; ?>">Pengembalian</a></li>
                    <li><a href="<?php echo $base; ?>peminjaman/riwayat.php" class="<?php echo ($page_title ?? '') === 'Riwayat Sewa' ? 'active' : ''; ?>">Riwayat</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <div class="auth-toolbar">
                <?php if ($sudahLogin): ?>
                    <span class="user-pill">
                        <span class="online-indicator"></span>
                        Petugas: <strong><?php echo e($_SESSION['nama'] ?? 'Petugas'); ?></strong>
                    </span>
                    <a href="<?php echo $base; ?>auth/logout.php" class="btn-logout">Logout</a>
                <?php else: ?>
                    <a href="<?php echo $base; ?>auth/login.php" class="btn-nav-login">Login Petugas</a>
                    <a href="https://wa.me/6281234567890?text=Halo%20Rental%20Motor%20Lowokwaru,%20saya%20ingin%20tanya%20sewa%20motor" target="_blank" class="btn-nav-wa">💬 WhatsApp</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="main-content">