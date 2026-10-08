# Security Audit Checklist — Rental Motor Lowokwaru (Jobsheet 11)

Dokumentasi audit keamanan web dasar untuk aplikasi Rental Motor Lowokwaru:

| No | Kategori | Potensi Risiko | Implementasi Perbaikan | Status |
|---|---|---|---|---|
| 1 | **XSS (Cross-Site Scripting)** | Penyerang menyuntikkan script berbahaya pada input nama motor, merk, nama pelanggan, alamat, atau parameter URL `q` pencarian. | Seluruh data output dinamis dibungkus dengan fungsi pembantu `e()` (`htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`) di `includes/helpers.php`. | **PASS** |
| 2 | **CSRF (Cross-Site Request Forgery)** | Penyerang memicu request POST tanpa izin user (misal menghapus motor atau mendaftarkan data) melalui situs pihak ketiga. | Implementasi token anti-CSRF acak `bin2hex(random_bytes(32))` via `includes/csrf.php`. Seluruh form POST wajib menyertakan `csrf_field()`, dan setiap script pemroses memanggil `csrf_verify()`. Request tanpa token valid menghasilkan error HTTP 403 Forbidden. | **PASS** |
| 3 | **Session Fixation** | Penyerang memaksakan ID sesi yang sudah diketahui sebelum pengguna melakukan login. | Pemanggilan `session_regenerate_id(true)` di `auth/proses_login.php` segera setelah `password_verify()` berhasil. | **PASS** |
| 4 | **SQL Injection** | Penyerang memanipulasi query database melalui input form atau parameter pencarian. | Audit menyeluruh: seluruh interaksi database menggunakan PDO Prepared Statements (`$pdo->prepare()`) dengan binding parameter aman, tidak ada konkatenasi query SQL langsung. | **PASS** |
| 5 | **Password Storage** | Kebocoran database membocorkan password pengguna/petugas dalam format plaintext. | Password di-hash menggunakan algoritma modern standar `password_hash($password, PASSWORD_DEFAULT)` (Bcrypt) dan diverifikasi via `password_verify()`. | **PASS** |
| 6 | **Otorisasi / Auth Guard** | Pengunjung umum mengakses menu administrasi tambah/edit/hapus armada motor dan pelanggan tanpa autentikasi. | Guard clause di `includes/auth.php` yang dipanggil pada baris paling pertama setiap halaman terproteksi. | **PASS** |
