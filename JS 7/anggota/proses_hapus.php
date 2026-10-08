<?php
session_start();
require __DIR__ . "/../includes/auth.php";
require_admin();
$i = filter_input(INPUT_POST, "index", FILTER_VALIDATE_INT);
if ($i !== false && isset($_SESSION["pelanggan"][$i])) {
    array_splice($_SESSION["pelanggan"], $i, 1);
}
header("Location: list.php");
exit();