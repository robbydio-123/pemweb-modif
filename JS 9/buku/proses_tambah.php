<?php
session_start();
require __DIR__ . "/../includes/auth.php";
require_admin();
require __DIR__ . "/../includes/koneksi.php";
$nama = trim($_POST["nama_motor"] ?? "");
$merk = trim($_POST["merk"] ?? "");
$tahun = filter_input(INPUT_POST, "tahun", FILTER_VALIDATE_INT);
$plat = trim($_POST["plat_nomor"] ?? "");
$tarif = filter_input(INPUT_POST, "tarif_harian", FILTER_VALIDATE_FLOAT);
$status = $_POST["status"] ?? "tersedia";
if (
    $nama === "" ||
    $merk === "" ||
    !$tahun ||
    $tahun < 1990 ||
    $tahun > 2100 ||
    $plat === "" ||
    $tarif === false ||
    $tarif < 0 ||
    !in_array($status, ["tersedia", "disewa", "servis"], true)
) {
    $_SESSION["flash"] = [
        "type" => "error",
        "pesan" => "Data motor tidak valid.",
    ];
    header("Location: tambah.php");
    exit();
}
try {
    $stmt = $pdo->prepare(
        "INSERT INTO motor (nama_motor, merk, tahun, plat_nomor, tarif_harian, status) VALUES (:nama, :merk, :tahun, :plat, :tarif, :status)",
    );
    $stmt->execute([
        "nama" => $nama,
        "merk" => $merk,
        "tahun" => $tahun,
        "plat" => $plat,
        "tarif" => $tarif,
        "status" => $status,
    ]);
    $_SESSION["flash"] = [
        "type" => "success",
        "pesan" => "Motor berhasil ditambahkan.",
    ];
} catch (PDOException $e) {
    $_SESSION["flash"] = [
        "type" => "error",
        "pesan" => "Plat nomor sudah digunakan.",
    ];
}
header("Location: list.php");
exit();