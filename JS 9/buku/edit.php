<?php
session_start();
require __DIR__ . "/../includes/auth.php";
require_admin();
require __DIR__ . "/../includes/koneksi.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    $_SESSION["flash"] = [
        "type" => "error",
        "pesan" => "Motor yang akan diubah tidak valid.",
    ];
    header("Location: list.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM motor WHERE id = :id");
$stmt->execute(["id" => $id]);
$motor = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$motor) {
    $_SESSION["flash"] = [
        "type" => "error",
        "pesan" => "Data motor tidak ditemukan.",
    ];
    header("Location: list.php");
    exit();
}

$page_title = "Edit Motor";
require __DIR__ . "/../includes/header.php";
$flash = $_SESSION["flash"] ?? null;
unset($_SESSION["flash"]);
?>
<section>
    <h2>Edit Motor</h2>
    <p class="page-description">Perbarui informasi armada motor rental Lowokwaru.</p>

    <?php if ($flash): ?>
    <p class="flash flash-<?php echo htmlspecialchars(
            $flash["type"] ?? "error",
        ); ?>"><?php echo htmlspecialchars($flash["pesan"] ?? ""); ?></p>
    <?php endif; ?>

    <form method="post" action="proses_ubah.php">
        <input type="hidden" name="id" value="<?php echo (int) $motor[
            "id"
        ]; ?>">
        <p>
            <label for="nama_motor">Nama Motor</label><br>
            <input type="text" id="nama_motor" name="nama_motor" maxlength="100" value="<?php echo htmlspecialchars(
                $motor["nama_motor"],
            ); ?>" required>
        </p>
        <p>
            <label for="merk">Merk</label><br>
            <input type="text" id="merk" name="merk" maxlength="100" value="<?php echo htmlspecialchars(
                $motor["merk"],
            ); ?>" required>
        </p>
        <p>
            <label for="tahun">Tahun</label><br>
            <input type="number" id="tahun" name="tahun" min="1990" max="2100" value="<?php echo (int) $motor[
                "tahun"
            ]; ?>" required>
        </p>
        <p>
            <label for="plat_nomor">Plat Nomor</label><br>
            <input type="text" id="plat_nomor" name="plat_nomor" maxlength="20" value="<?php echo htmlspecialchars(
                $motor["plat_nomor"],
            ); ?>" required>
        </p>
        <p>
            <label for="tarif_harian">Tarif per Hari (Rp)</label><br>
            <input type="number" id="tarif_harian" name="tarif_harian" min="0" step="1000" value="<?php echo htmlspecialchars(
                (string) $motor["tarif_harian"],
            ); ?>" required>
        </p>
        <p>
            <label for="status">Status</label><br>
            <select id="status" name="status" required>
                <option value="tersedia" <?php echo $motor["status"] ===
                "tersedia"
                    ? "selected"
                    : ""; ?>>Tersedia</option>
                <option value="disewa" <?php echo $motor["status"] === "disewa"
                    ? "selected"
                    : ""; ?>>Disewa</option>
                <option value="servis" <?php echo $motor["status"] === "servis"
                    ? "selected"
                    : ""; ?>>Servis</option>
            </select>
        </p>
        <p>
            <button type="submit">Simpan Perubahan</button>
            <a class="button" href="list.php">Batal</a>
        </p>
    </form>
</section>
<?php require __DIR__ . "/../includes/footer.php"; ?>