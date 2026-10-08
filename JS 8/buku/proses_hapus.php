<?php
session_start();
require __DIR__ . "/../includes/auth.php";
require_admin();
require __DIR__ . "/../includes/koneksi.php";
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
if (!$id) {
    $_SESSION["flash"] = [
        "type" => "error",
        "pesan" => "ID motor tidak valid.",
    ];
    header("Location: list.php");
    exit();
}
$stmt = $pdo->prepare("DELETE FROM motor WHERE id = :id");
$stmt->execute(["id" => $id]);
$_SESSION["flash"] = [
    "type" => "success",
    "pesan" => "Motor berhasil dihapus.",
];
header("Location: list.php");
exit();