# Jobsheet 12 — Integrasi Modul Transaksi & Rental Motor Lowokwaru Premium

Sub-CPMK: Mengintegrasikan front-end dan back-end proyek secara utuh dengan modul transaksi persewaan sepeda motor.

## Perubahan dari Jobsheet 11
- Tambah `sql/03_peminjaman.sql` — tabel `peminjaman` yang menghubungkan entitas `motor`, `pelanggan`, dan `users`.
- Tambah modul **Transaksi Penyewaan Motor** (`peminjaman/`):
  - `peminjaman/tambah.php` + `proses_tambah.php`:
    - Pilih pelanggan terdaftar + unit motor (hanya unit dengan `status = 'tersedia'`).
    - Input tanggal sewa & durasi hari dengan **kalkulator estimasi total biaya otomatis**.
    - Menggunakan **Database Transaction** (`$pdo->beginTransaction()`, `$pdo->commit()`, `$pdo->rollBack()`).
    - Menggunakan query `SELECT ... FOR UPDATE` pada baris motor untuk mengunci baris data dan mencegah *race condition* (mencegah dua pelanggan menyewa motor yang sama secara bersamaan).
    - Status armada motor otomatis berubah menjadi `disewa`.
  - `peminjaman/kembali.php` + `proses_kembali.php`:
    - Menampilkan seluruh penyewaan aktif (`status = 'dipinjam'`).
    - Form pencarian cepat pelanggan/motor.
    - Tombol "Kembalikan" yang memulihkan status motor kembali menjadi `tersedia` dalam satu database transaction berpasangan.
  - `peminjaman/riwayat.php`:
    - Histori transaksi lengkap per pelanggan atau seluruh transaksi terbaru dengan JOIN `peminjaman`, `motor`, dan `pelanggan`.
- **Desain UI/UX Premium Rental Motor Lowokwaru**:
  - Banner Hero spektakuler dengan visual atraktif, USP (Gratis 2 Helm SNI + 2 Jas Hujan, Free Antar Jemput Stasiun/Kampus UB & Polinema).
  - Tampilan Katalog Motor modern berbasis kartu (`buku/list.php`) dengan foto motor tajam beresolusi tinggi, tag spesifikasi (CC, transmisi, plat nomor), badge ketersediaan glowing, dan tombol langsung booking WhatsApp.
  - Dashboard statistik real-time di `index.php` (Total Armada, Motor Siap Sewa, Sedang Disewa [live count dari tabel `peminjaman`], Total Pelanggan, Transaksi Selesai).
  - Section Testimoni pelanggan nyata & panduan cara sewa 3 langkah mudah.
  - Navbar lengkap dengan menu dinamis (Sewa Baru, Pengembalian, Riwayat).

## Cara Menjalankan
Jalankan server PHP lokal:
```bash
php -S localhost:8000
```

## Pengujian End-to-End
1. **Login Petugas**: Buka `http://localhost:8000/auth/login.php`, masuk dengan user `petugas` / `rental123`.
2. **Lihat Katalog Motor**: Buka `http://localhost:8000/buku/list.php` → perhatikan tampilan kartu motor berfoto dan spesifikasinya.
3. **Catat Sewa Baru**: Buka menu **Sewa Baru** (`peminjaman/tambah.php`) → pilih pelanggan dan motor yang tersedia → ubah durasi hari dan lihat kalkulator total biaya → simpan transaksi.
4. **Cek Perubahan Status**:
   - Di Katalog Motor: unit yang disewa otomatis berubah statusnya menjadi *Sedang Disewa*.
   - Di Beranda (`index.php`): kartu *Sedang Disewa* bertambah 1.
5. **Proses Pengembalian**: Buka menu **Pengembalian** (`peminjaman/kembali.php`) → klik tombol *Selesai & Kembalikan*.
6. **Verifikasi Pengembalian**:
   - Status unit di katalog kembali menjadi *Tersedia*.
   - Transaksi tercatat di menu **Riwayat** (`peminjaman/riwayat.php`) dengan status *Selesai*.
