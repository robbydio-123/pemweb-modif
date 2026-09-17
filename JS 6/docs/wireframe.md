# Wireframe & User Flow — Rental Motor Lowokwaru

Sub-CPMK: Merancang UI/UX aplikasi (proyek).

Halaman yang sudah ada (Beranda, Daftar/Tambah Motor, Daftar/Tambah Pelanggan) dilengkapi dengan alur penyewaan dan pengembalian motor.

## Aktor
- **Pelanggan**: menyewa motor sesuai ketersediaan armada.
- **Petugas**: mengelola seluruh data motor, pelanggan, dan transaksi penyewaan.

## User Flow — Penyewaan Motor

```
[Petugas] -> [Dashboard] -> [Pilih menu "Sewa Motor"]
        -> [Pilih Pelanggan] -> [Pilih Motor (status tersedia)]
        -> [Simpan] -> [Status motor menjadi disewa] -> [Kembali ke Dashboard]
```

## User Flow — Pengembalian Motor

```
[Dashboard] -> [Menu "Pengembalian"] -> [Cari transaksi aktif (pelanggan/motor)]
        -> [Tandai "Dikembalikan"] -> [Hitung lama sewa, total biaya, dan denda]
        -> [Kembali ke Dashboard]
```

## Wireframe: Halaman Login

```
+--------------------------------------+
|        Rental Motor Lowokwaru        |
|--------------------------------------|
|                                      |
|        [ Login Petugas ]            |
|                                      |
|   Username : [______________]       |
|   Password : [______________]       |
|                                      |
|          [   Masuk   ]              |
|                                      |
|   Belum punya akun? Daftar di sini  |
+--------------------------------------+
```

## Wireframe: Dashboard Petugas

```
+-----------------------------------------------------+
| Rental Motor      Beranda | Motor | Pelanggan | Penyewaan |
|-------------------------------------------------------|
|  [Total Motor]   [Total Pelanggan]   [Sedang Disewa]    |
|                                                         |
|  Aksi Cepat:                                           |
|  [ + Sewa Motor ]   [ + Pengembalian Motor ]            |
|                                                         |
|  Transaksi Terbaru                                     |
|  --------------------------------------------------    |
|  Pelanggan | Motor | Tgl Sewa | Status                  |
+-----------------------------------------------------+
```

## Wireframe: Form Peminjaman

```
+--------------------------------------+
|  Form Penyewaan Motor                |
|--------------------------------------|
|  Pelanggan : [ dropdown pilih pelanggan ]|
|  Motor     : [ dropdown, hanya tersedia ]|
|  Tanggal Sewa : [ auto: hari ini ]     |
|                                      |
|          [  Simpan Penyewaan  ]    |
+--------------------------------------+
```

## Wireframe: Form Pengembalian

```
+--------------------------------------+
|  Pengembalian Motor                  |
|--------------------------------------|
|  Cari transaksi aktif:               |
|  [ nama pelanggan / plat nomor _____ ]|
|                                      |
|  Pelanggan | Motor | Tgl Sewa | [Kembalikan] |
+--------------------------------------+
```

## Wireframe: Riwayat Peminjaman per Anggota

```
+--------------------------------------+
|  Riwayat Penyewaan — Siti Aminah     |
|--------------------------------------|
|  Motor           | Sewa     | Kembali | Status      |
|  Honda Vario      | 01/07    | 10/07   | Selesai     |
|  Yamaha NMAX      | 15/07    | -       | Disewa      |
+--------------------------------------+
```

## Konsistensi dengan Desain yang Sudah Berjalan
- Warna aksen, tipografi navbar, dan gaya tabel/kartu mengikuti `assets/css/style.css` yang sudah dibangun sejak Jobsheet 2-3.
- Navbar menyediakan menu **Penyewaan** untuk mencatat sewa dan pengembalian motor.
- Edge case yang perlu ditangani: motor berstatus disewa tidak boleh dipilih; denda keterlambatan dihitung Rp25.000 per hari.