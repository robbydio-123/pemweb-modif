<?php
require __DIR__ . "/../includes/auth.php";
require_login();
require __DIR__ . "/../includes/koneksi.php";
$page_title = "Pesanan Penyewaan";
$pesanan = $pdo
    ->query(
        "SELECT p.*, m.nama_motor FROM penyewaan p JOIN motor m ON m.id=p.motor_id ORDER BY p.id DESC",
    )
    ->fetchAll(PDO::FETCH_ASSOC);
require __DIR__ . "/../includes/header.php";
?><section>
    <h2>Pesanan Penyewaan</h2>
    <p class="page-description">Konfirmasi pesanan pelanggan melalui WhatsApp admin:
        <strong>12345678910</strong>.</p>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Motor</th>
                    <th>Penyewa</th>
                    <th>WhatsApp</th>
                    <th>KTP</th>
                    <th>Durasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody><?php
foreach ($pesanan as $p): ?><tr>
                    <td><?php echo htmlspecialchars(
    $p["nama_motor"],
); ?></td>
                    <td><?php echo htmlspecialchars(
    $p["nama_penyewa"],
); ?></td>
                    <td><?php echo htmlspecialchars(
    $p["no_whatsapp"],
); ?></td>
                    <td><?php echo htmlspecialchars(
    $p["no_ktp"],
); ?></td>
                    <td><?php echo (int) $p[
    "durasi_hari"
]; ?> hari</td>
                    <td><?php echo htmlspecialchars(
     ucfirst($p["status"]),
 ); ?></td>
                    <td><?php if (
    $p["status"] === "menunggu"
): ?><form method="post" action="proses.php"><input type="hidden" name="id" value="<?php echo (int) $p[
    "id"
]; ?>"><button name="aksi" value="dikonfirmasi">Konfirmasi</button><button name="aksi"
                                value="ditolak">Tolak</button></form>
                        <?php else: ?>Selesai<?php endif; ?></td>
                </tr><?php endforeach;
if (!$pesanan): ?><tr>
                    <td colspan="7">Belum ada pesanan.</td>
                </tr><?php endif;
?></tbody>
        </table>
    </div>
</section><?php require __DIR__ .
    "/../includes/footer.php"; ?>