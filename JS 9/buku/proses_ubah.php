<?php
session_start();
require __DIR__ . "/../includes/auth.php";
require_admin();
require __DIR__ . "/../includes/koneksi.php";
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$nama = trim($_POST["nama_motor"] ?? "");
$merk = trim($_POST["merk"] ?? "");
$tahun = filter_input(INPUT_POST, "tahun", FILTER_VALIDATE_INT);
$plat = trim($_POST["plat_nomor"] ?? "");
$tarif = filter_input(INPUT_POST, "tarif_harian", FILTER_VALIDATE_FLOAT);
$status = $_POST["status"] ?? "";
if (
    !$id ||
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
    header("Location: list.php");
    exit();
}
try {
    $stmt = $pdo->prepare(
        "UPDATE motor SET nama_motor=:nama, merk=:merk, tahun=:tahun, plat_nomor=:plat, tarif_harian=:tarif, status=:status WHERE id=:id",
    );
    $stmt->execute([
        "id" => $id,
        "nama" => $nama,
        "merk" => $merk,
        "tahun" => $tahun,
        "plat" => $plat,
        "tarif" => $tarif,
        "status" => $status,
    ]);
    $_SESSION["flash"] = [
        "type" => "success",
        "pesan" => "Motor berhasil diubah.",
    ];
} catch (PDOException $e) {
    $_SESSION["flash"] = [
        "type" => "error",
        "pesan" => "Plat nomor sudah digunakan.",
    ];
}
header("Location: list.php");
exit();