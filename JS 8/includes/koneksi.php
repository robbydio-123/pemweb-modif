<?php
$host = getenv("DB_HOST") ?: "127.0.0.1";
$port = getenv("DB_PORT") ?: "5432";
$db = getenv("DB_NAME") ?: "rental_motor";
$user = getenv("DB_USER") ?: "postgres";
$pass = getenv("DB_PASSWORD");
$sslmode = getenv("DB_SSLMODE") ?: "prefer";

if ($pass === false || $pass === "") {
    http_response_code(500);
    die(
        "Koneksi database gagal: environment variable DB_PASSWORD belum diatur."
    );
}

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$db;sslmode=$sslmode",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
    );
} catch (PDOException $e) {
    error_log("Koneksi PostgreSQL gagal: " . $e->getMessage());
    http_response_code(500);
    die(
        "Koneksi database gagal. Periksa konfigurasi DB_HOST, DB_PORT, DB_NAME, DB_USER, dan DB_PASSWORD."
    );
}