<?php
session_start();
require __DIR__ . "/../includes/auth.php";
require_admin();
$i = filter_input(INPUT_POST, "index", FILTER_VALIDATE_INT);
$p = [
    "nama" => trim($_POST["nama"] ?? ""),
    "no_pelanggan" => trim($_POST["no_pelanggan"] ?? ""),
    "alamat" => trim($_POST["alamat"] ?? ""),
    "no_hp" => trim($_POST["no_hp"] ?? ""),
];
if (
    $i === false ||
    !isset($_SESSION["pelanggan"][$i]) ||
    $p["nama"] === "" ||
    $p["no_pelanggan"] === "" ||
    $p["no_hp"] === ""
) {
    die("Data pelanggan tidak valid.");
}
$_SESSION["pelanggan"][$i] = $p;
header("Location: list.php");
exit();