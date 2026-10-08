<?php
session_start();
require __DIR__ . "/../includes/auth.php";
require_admin();
$m = [
    "nama_motor" => trim($_POST["nama_motor"] ?? ""),
    "merk" => trim($_POST["merk"] ?? ""),
    "tahun" => (int) ($_POST["tahun"] ?? 0),
    "plat_nomor" => trim($_POST["plat_nomor"] ?? ""),
    "tarif_harian" => (float) ($_POST["tarif_harian"] ?? -1),
    "status" => $_POST["status"] ?? "",
];
if (
    $m["nama_motor"] === "" ||
    $m["merk"] === "" ||
    $m["tahun"] < 1990 ||
    $m["plat_nomor"] === "" ||
    $m["tarif_harian"] < 0 ||
    !in_array($m["status"], ["tersedia", "disewa", "servis"], true)
) {
    die("Data motor tidak valid.");
}
$_SESSION["motor"][] = $m;
header("Location: list.php");
exit();