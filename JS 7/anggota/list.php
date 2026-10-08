<?php
session_start();
require __DIR__ . "/../includes/auth.php";
require_admin();
$pelanggan = $_SESSION["pelanggan"] ?? [];
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Daftar Pelanggan - Rental Motor</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <header><a class="brand" href="../index.php">
            <h1>Rental Motor</h1>
        </a>
        <nav>
            <ul>
                <li><a href="../index.php">Beranda</a></li>
                <li><a href="../buku/list.php">Daftar Motor</a></li>
                <li><a href="list.php">Daftar Pelanggan</a></li>
                <li><a href="tambah.php">Tambah Pelanggan</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h2>Daftar Pelanggan</h2>
            <p><a href="tambah.php">Tambah Pelanggan</a></p>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No. Pelanggan</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No. HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody><?php foreach (
    $pelanggan
    as $i => $p
): ?><tr>
                            <td><?php echo htmlspecialchars(
    $p["no_pelanggan"],
); ?></td>
                            <td><?php echo htmlspecialchars(
    $p["nama"],
); ?></td>
                            <td><?php echo htmlspecialchars(
    $p["alamat"],
); ?></td>
                            <td><?php echo htmlspecialchars(
    $p["no_hp"],
); ?></td>
                            <td><a href="tambah.php?edit=<?php echo $i; ?>">Edit</a>
                                <form method="post" action="proses_hapus.php"
                                    style="display:inline"><input type="hidden" name="index"
                                        value="<?php echo $i; ?>"><button>Hapus</button></form>
                            </td>
                        </tr><?php endforeach; ?></tbody>
                </table>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; <?php echo date(
    "Y",
); ?> Rental Motor</p>
    </footer>
</body>

</html>