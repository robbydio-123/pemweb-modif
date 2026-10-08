<?php
session_start();
require __DIR__ . "/../includes/auth.php";
require_admin();
$i = filter_input(INPUT_GET, "edit", FILTER_VALIDATE_INT);
$p =
    $i !== false && $i !== null && isset($_SESSION["pelanggan"][$i])
        ? $_SESSION["pelanggan"][$i]
        : [];
$edit = $p !== [];
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title><?php echo $edit
    ? "Edit"
    : "Tambah"; ?> Pelanggan</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <main>
        <section>
            <h2><?php echo $edit
     ? "Edit"
     : "Tambah"; ?> Pelanggan</h2>
            <form method="post" action="<?php echo $edit
     ? "proses_ubah.php"
     : "proses_tambah.php"; ?>"><?php if (
    $edit
): ?><input type="hidden" name="index" value="<?php echo $i; ?>"><?php endif; ?><p>
                    <label>Nama</label><input name="nama" value="<?php echo htmlspecialchars(
    $p["nama"] ?? "",
); ?>" required></p>
                <p><label>No. Pelanggan</label><input name="no_pelanggan" value="<?php echo htmlspecialchars(
    $p["no_pelanggan"] ?? "",
); ?>" required></p>
                <p><label>Alamat</label><input name="alamat" value="<?php echo htmlspecialchars(
    $p["alamat"] ?? "",
); ?>"></p>
                <p><label>No. HP</label><input name="no_hp" value="<?php echo htmlspecialchars(
    $p["no_hp"] ?? "",
); ?>" required></p><button>Simpan</button>
            </form>
        </section>
    </main>
</body>

</html>