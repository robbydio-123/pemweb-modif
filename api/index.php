<?php
$route = trim($_GET['route'] ?? 'index.php', '/');
$parts = explode('/', $route);
$appRoot = dirname(__DIR__) . '/JS 10';

if ($route === 'index.php') {
    $script = $appRoot . '/index.php';
} elseif (
    count($parts) === 2
    && in_array($parts[0], ['auth', 'anggota', 'buku', 'penyewaan'], true)
    && preg_match('/^[a-z0-9_-]+\.php$/D', $parts[1])
) {
    $script = $appRoot . '/' . $parts[0] . '/' . $parts[1];
} else {
    http_response_code(404);
    exit('Halaman tidak ditemukan.');
}

$resolvedScript = realpath($script);
$resolvedRoot = realpath($appRoot);
if ($resolvedScript === false || $resolvedRoot === false || !is_file($resolvedScript)) {
    http_response_code(404);
    exit('Halaman tidak ditemukan.');
}

$_SERVER['SCRIPT_FILENAME'] = $resolvedScript;
require $resolvedScript;
