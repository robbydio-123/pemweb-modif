<?php
session_start();
require __DIR__ . "/../includes/auth.php";
require_admin();
require __DIR__ . "/../includes/koneksi.php";
$page_title = "Daftar Pelanggan";
$daftarPelanggan = $pdo
    ->query("SELECT * FROM pelanggan ORDER BY id DESC")
    ->fetchAll(PDO::FETCH_ASSOC);
require __DIR__ . "/../includes/header.php";
?><section>
    <h2>Daftar Pelanggan</h2>
    <p class="page-description">Kelola data pelanggan rental motor.</p>
    <?php if (!empty($_SESSION["flash"])):

    $flash = $_SESSION["flash"];
    unset($_SESSION["flash"]);
    ?><p class="flash flash-<?php echo htmlspecialchars(
    $flash["type"],
); ?>"><?php echo htmlspecialchars($flash["pesan"]); ?></p><?php
endif; ?>
    <p><a class="button" href="tambah.php">Tambah Pelanggan</a></p>
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
            <tbody>
                <?php foreach (
    $daftarPelanggan
    as $pelanggan
): ?><tr>
                    <td><?php echo htmlspecialchars(
    $pelanggan["no_pelanggan"],
); ?></td>
                    <td><?php echo htmlspecialchars(
    $pelanggan["nama"],
); ?></td>
                    <td><?php echo htmlspecialchars(
    $pelanggan["alamat"] ?? "",
); ?></td>
                    <td><?php echo htmlspecialchars(
    $pelanggan["no_hp"],
); ?></td>
                    <td class="action-buttons"><a class="action-edit" href="tambah.php?edit=<?php echo (int) $pelanggan[
    "id"
]; ?>">Edit</a>
                        <form method="post" action="proses_hapus.php"
                            onsubmit="return confirm('Hapus pelanggan ini?');"><input type="hidden"
                                name="id" value="<?php echo (int) $pelanggan[
    "id"
]; ?>"><button type="submit" class="btn-hapus">Hapus</button></form>
                    </td>
                </tr><?php endforeach; ?>
                <?php if (
    !$daftarPelanggan
): ?><tr>
                    <td colspan="5">Belum ada data pelanggan.</td>
                </tr><?php endif; ?></tbody>
        </table>
    </div>
</section><?php require __DIR__ .
    "/../includes/footer.php"; ?>