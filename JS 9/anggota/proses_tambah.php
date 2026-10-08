<?php
session_start();

require __DIR__ . "/../includes/auth.php";
require_admin();
require __DIR__ . "/../includes/koneksi.php";

$nama = trim($_POST["nama"] ?? "");
$nomor = trim($_POST["no_pelanggan"] ?? "");
$alamat = trim($_POST["alamat"] ?? "");
$hp = trim($_POST["no_hp"] ?? "");

if ($nama === "" || $nomor === "" || $hp === "") {
    $_SESSION["flash"] = [
        "type" => "error",
        "pesan" => "Nama, nomor pelanggan, dan nomor HP wajib diisi.",
    ];
    header("Location: tambah.php");
    exit();
}

try {
    $stmt = $pdo->prepare(
        "INSERT INTO pelanggan (nama, no_pelanggan, alamat, no_hp) " .
            "VALUES (:nama, :nomor, :alamat, :hp)",
    );
    $stmt->execute([
        "nama" => $nama,
        "nomor" => $nomor,
        "alamat" => $alamat,
        "hp" => $hp,
    ]);

    $_SESSION["flash"] = [
        "type" => "success",
        "pesan" => "Pelanggan berhasil ditambahkan.",
    ];
} catch (PDOException $e) {
    $_SESSION["flash"] = [
        "type" => "error",
        "pesan" => "Nomor pelanggan sudah digunakan.",
    ];
}

header("Location: list.php");
exit();