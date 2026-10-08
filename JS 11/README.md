# Jobsheet 11 — Keamanan Web Dasar (Rental Motor Lowokwaru)

Sub-CPMK: Menerapkan prinsip keamanan web dasar pada aplikasi Rental Motor Lowokwaru.

## Perubahan dari Jobsheet 10
- Tambah `includes/helpers.php` (`e()` untuk `htmlspecialchars` dengan UTF-8 & ENT_QUOTES) dan `includes/csrf.php` (`csrf_token()`, `csrf_field()`, `csrf_verify()`), keduanya di-`require_once` dari `includes/header.php`.
- **XSS**: seluruh output data dari database/`$_GET` (nama motor, merk, tahun, plat nomor, tarif, status, nama pelanggan, alamat, no HP, nilai pencarian, nama petugas di navbar) dibungkus fungsi `e()`.
- **CSRF**: token tersembunyi `csrf_field()` ditambahkan ke semua form POST (Tambah/Edit/Hapus Motor & Pelanggan, Login, Register); setiap `proses_*.php` dan `hapus.php` memanggil `csrf_verify()` sebelum menyentuh database.
- **Session fixation**: `session_regenerate_id(true)` dipanggil di `auth/proses_login.php` setelah verifikasi password berhasil.
- **SQL Injection**: diaudit ulang (seluruh query menggunakan PDO prepared statement dengan parameter binding).
- Tambah `docs/security-checklist.md` — dokumen audit lengkap dengan rincian mitigasi per kerentanan.

## Cara Menjalankan
```bash
php -S localhost:8000
```

## Cara Menguji Keamanan
1. **CSRF**: Login sebagai petugas, lalu coba kirim request POST via cURL tanpa token:
   ```bash
   curl -X POST http://localhost:8000/buku/proses_tambah.php -d "nama_motor=Test"
   ```
   Hasil: HTTP 403 Forbidden ("Permintaan ditolak: token CSRF tidak valid atau kedaluwarsa").
2. **XSS**: Tambah motor dengan nama `<script>alert('XSS')</script>` → di Daftar Motor teks tampil apa adanya secara aman, script tidak dieksekusi oleh browser.
3. **Session Fixation**: Cek session ID sebelum dan sesudah login → ID berubah berkat `session_regenerate_id(true)`.
