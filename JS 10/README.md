# Jobsheet 10 — Autentikasi & Manajemen Sesi (Rental Motor Lowokwaru)

Sub-CPMK: Menerapkan autentikasi & manajemen sesi pengguna pada aplikasi Rental Motor Lowokwaru.

## Perubahan dari Jobsheet 9
- Tambah `sql/02_users.sql` — tabel `users` (id, nama, username, password, role).
- Tambah `auth/register.php` + `proses_register.php` (password disimpan dengan `password_hash()`, cek username duplikat), `auth/login.php` + `proses_login.php` (`password_verify()`), `auth/logout.php` (`session_destroy()`).
- Tambah `includes/auth.php` — guard clause: redirect ke `../auth/login.php` bila `$_SESSION['user_id']` belum ada. **Wajib di-include sebagai baris pertama** (sebelum `header.php`) agar `header('Location: ...')` masih bisa dipanggil sebelum ada output HTML.
- `includes/header.php`: `session_start()` diubah jadi `if (session_status() === PHP_SESSION_NONE)` agar tidak konflik dengan `auth.php` yang juga memulai session; navbar kini menampilkan nama petugas + Logout jika sudah login, atau link Login/Register jika belum.
- Halaman yang **dikunci** (butuh login petugas): `buku/tambah.php`, `buku/edit.php`, `buku/proses_tambah.php`, `buku/proses_edit.php`, `buku/hapus.php`, seluruh halaman `anggota/*` (data pelanggan).
- Halaman yang **tetap publik**: `index.php` (Beranda) dan `buku/list.php` (katalog armada motor bisa dilihat pelanggan/tamu tanpa login).

## Persiapan Database PostgreSQL
Jalankan skema:
```bash
psql -d rental_motor -f sql/01_motor_pelanggan.sql
psql -d rental_motor -f sql/02_users.sql
```

## Akun Demo Petugas
- Username: `petugas`
- Password: `rental123`

## Cara Menjalankan
```bash
php -S localhost:8000
```
Di Windows dengan XAMPP, buka **Terminal > Run Task > Run Jobsheet 10 PHP server** di VS Code, lalu akses `http://localhost:8000`. Task tersebut memakai PHP XAMPP dan mengaktifkan driver PostgreSQL untuk server lokal.

Jika menjalankan dari terminal secara manual, dari folder `JS 10` gunakan:
```powershell
& 'C:\xampp\php\php.exe' -d extension=php_pdo_pgsql.dll -S localhost:8000
```
Uji: akses `http://localhost:8000/buku/tambah.php` langsung tanpa login → harus redirect ke halaman Login. Daftar akun via Register, login, coba akses halaman yang sama → berhasil.
