<?php
$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '5432';
$db   = getenv('DB_NAME') ?: 'rental_motor';
$user = getenv('DB_USER') ?: 'postgres';
$pass = getenv('DB_PASSWORD');

if ($pass === false) {
    die('Koneksi database gagal: environment variable DB_PASSWORD belum diatur.');
}


try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$db",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}