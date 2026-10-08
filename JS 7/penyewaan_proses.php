<?php
session_start();
require __DIR__ . "/includes/auth.php";
require_admin();
$i = filter_input(INPUT_POST, "index", FILTER_VALIDATE_INT);
$aksi = $_POST["aksi"] ?? "";
if (
    isset($_SESSION["penyewaan"][$i]) &&
    $_SESSION["penyewaan"][$i]["status"] === "menunggu" &&
    in_array($aksi, ["dikonfirmasi", "ditolak"], true)
) {
    $_SESSION["penyewaan"][$i]["status"] = $aksi;
    if ($aksi === "ditolak") {
        foreach ($_SESSION["motor"] as &$m) {
            if ($m["nama_motor"] === $_SESSION["penyewaan"][$i]["motor"]) {
                $m["status"] = "tersedia";
            }
        }
        unset($m);
    }
}
header("Location: penyewaan_list.php");
exit();