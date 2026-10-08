<?php
session_start();
require __DIR__ . "/../includes/auth.php";
require_admin();
require __DIR__ . "/../includes/koneksi.php";
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$aksi = $_POST["aksi"] ?? "";
if (!$id || !in_array($aksi, ["dikonfirmasi", "ditolak"], true)) {
    header("Location: list.php");
    exit();
}
$stmt = $pdo->prepare(
    "SELECT motor_id FROM penyewaan WHERE id=:id AND status=:status",
);
$stmt->execute(["id" => $id, "status" => "menunggu"]);
$pesanan = $stmt->fetch(PDO::FETCH_ASSOC);
if ($pesanan) {
    $pdo->beginTransaction();
    $pdo->prepare("UPDATE penyewaan SET status=:status WHERE id=:id")->execute([
        "status" => $aksi,
        "id" => $id,
    ]);
    if ($aksi === "ditolak") {
        $pdo->prepare(
            "UPDATE motor SET status='tersedia' WHERE id=:id",
        )->execute(["id" => $pesanan["motor_id"]]);
    }
    $pdo->commit();
}
header("Location: list.php");
exit();