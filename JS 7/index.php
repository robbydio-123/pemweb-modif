<?php
session_start();
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mode = $_POST["mode"] ?? "admin";

    if (
        $mode === "admin" &&
        ($_POST["username"] ?? "") === "petugas" &&
        ($_POST["password"] ?? "") === "rental123"
    ) {
        $_SESSION["login"] = "petugas";
        $_SESSION["role"] = "admin";
        header("Location: buku/list.php");
        exit();
    }

    if (
        $mode === "customer" &&
        trim($_POST["contact"] ?? "") !== "" &&
        strlen($_POST["customer_password"] ?? "") >= 6
    ) {
        $_SESSION["login"] = trim($_POST["contact"]);
        $_SESSION["role"] = "customer";
        header("Location: buku/list.php");
        exit();
    }

    if (
        $mode === "google" &&
        strtolower(trim($_POST["google_email"] ?? "")) ===
            "pelanggan@gmail.com" &&
        ($_POST["google_password"] ?? "") === "pelanggan123"
    ) {
        $_SESSION["login"] = "pelanggan@gmail.com";
        $_SESSION["role"] = "customer";
        $_SESSION["login_provider"] = "google-demo";
        header("Location: buku/list.php");
        exit();
    }

    $message = "Akun belum sesuai. Gunakan akun demo yang tersedia.";
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sewa Rental Motor Malang Lowokwaru | Masuk</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="login-page">
    <main class="login-shell">
        <section class="login-intro"><a class="login-brand" href="index.php"><span
                    class="brand-mark"><i
                        class="bi bi-bicycle"></i></span><span>LOKOWARU<br><strong>MOTOR
                        RENTAL</strong></span></a>
            <div class="intro-copy">
                <p class="eyebrow">RUANG KERJA PETUGAS</p>
                <h1>Semua perjalanan dimulai dari sini.</h1>
                <p>Kelola armada, pelanggan, dan penyewaan motor Lowokwaru dalam satu ruang kerja
                    yang rapi.</p>
            </div>
            <div class="intro-meta"><span><i class="bi bi-geo-alt-fill"></i> Malang, Jawa
                    Timur</span><span><i class="bi bi-shield-check"></i> Sistem internal
                    rental</span></div>
        </section>
        <section class="login-panel">
            <div class="panel-topline"><span>01</span><span>PORTAL AKSES</span></div>
            <div class="login-switcher"><button type="button" class="login-mode active"
                    data-mode="admin">Admin / Petugas</button><button type="button"
                    class="login-mode" data-mode="customer">Pelanggan</button></div>
            <div class="login-heading">
                <p class="eyebrow" id="login-eyebrow">SELAMAT DATANG KEMBALI</p>
                <h2 id="login-title">Masuk ke ruang kerja</h2>
                <p id="login-description">Gunakan akun petugas untuk melanjutkan pengelolaan rental.
                </p>
            </div>
            <form method="post" id="admin-form"><input type="hidden" name="mode" value="admin">
                <p><label>Username</label><span class="input-wrap"><i
                            class="bi bi-person"></i><input name="username"
                            placeholder="Masukkan username" required></span></p>
                <p><label>Kata sandi</label><span class="input-wrap"><i
                            class="bi bi-lock"></i><input type="password" name="password"
                            placeholder="Masukkan kata sandi" required></span></p>
                <div class="login-options"><label class="remember"><input type="checkbox"> Ingat
                        saya</label><a href="#demo-account">Bantuan masuk?</a></div><button
                    class="login-submit" type="submit">Masuk ke dashboard <i
                        class="bi bi-arrow-up-right"></i></button>
            </form>
            <form method="post" id="customer-form" hidden><input type="hidden" name="mode"
                    value="customer">
                <p><label>Email atau nomor telepon</label><span class="input-wrap"><i
                            class="bi bi-at"></i><input name="contact"
                            placeholder="nama@email.com atau 08..." required></span></p>
                <p><label>Kata sandi</label><span class="input-wrap"><i
                            class="bi bi-lock"></i><input type="password" name="customer_password"
                            placeholder="Minimal 6 karakter" minlength="6" required></span></p>
                <button class="login-submit" type="submit">Masuk sebagai pelanggan <i
                        class="bi bi-arrow-up-right"></i></button>
            </form>
            <div id="google-entry"><button type="button" class="social-login" id="google-start"><i
                        class="bi bi-google"></i> Lanjut dengan Google</button></div>
            <form method="post" id="google-form" hidden><input type="hidden" name="mode"
                    value="google">
                <p><label>Email Google</label><span class="input-wrap"><i
                            class="bi bi-google"></i><input type="email" name="google_email"
                            value="pelanggan@gmail.com" required></span></p>
                <p><label>Password Google</label><span class="input-wrap"><i
                            class="bi bi-lock"></i><input type="password" name="google_password"
                            placeholder="Masukkan password" required></span></p><button
                    class="social-login" type="submit"><i class="bi bi-google"></i> Konfirmasi
                    Google</button>
                <p class="google-demo-note">Demo: pelanggan@gmail.com / pelanggan123</p>
            </form><?php if (
    $message
): ?><p class="login-message error"><?php echo htmlspecialchars(
    $message,
); ?></p><?php endif; ?><div class="demo-account" id="demo-account"><i
                    class="bi bi-info-circle"></i><span>Petugas: <strong>petugas</strong> /
                    <strong>rental123</strong></span></div>
            <p class="login-footer">&copy; <?php echo date(
    "Y",
); ?> Lokowaru Motor Rental</p>
        </section>
    </main>
    <script>
        var entry = document.querySelector('#google-entry'),
            googleForm = document.querySelector('#google-form');

        function setMode(customer) {
            document.querySelector('#admin-form').hidden = customer;
            document.querySelector('#customer-form').hidden = !customer;
            entry.hidden = !customer;
            googleForm.hidden = true;
        }
        document.querySelectorAll('.login-mode').forEach(function(button) {
            button.addEventListener('click', function() {
                var customer = button.dataset.mode === 'customer';
                document.querySelectorAll('.login-mode').forEach(function(item) {
                    item.classList.toggle('active', item === button)
                });
                setMode(customer);
                document.querySelector('#login-eyebrow').textContent = customer ?
                    'AKSES PELANGGAN' : 'SELAMAT DATANG KEMBALI';
                document.querySelector('#login-title').textContent = customer ?
                    'Mulai perjalananmu' : 'Masuk ke ruang kerja';
                document.querySelector('#login-description').textContent =
                    customer ?
                    'Masuk untuk mencari motor dan mengatur penyewaanmu.' :
                    'Gunakan akun petugas untuk melanjutkan pengelolaan rental.'
            })
        });
        document.querySelector('#google-start').addEventListener('click', function() {
            entry.hidden = true;
            googleForm.hidden = false
        });
    </script>
</body>

</html>