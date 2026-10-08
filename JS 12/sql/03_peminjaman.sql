-- Skema Database: peminjaman (Jobsheet 12 Integrasi Modul Transaksi)
-- Proyek: Rental Motor Lowokwaru Malang

CREATE TABLE IF NOT EXISTS peminjaman (
    id SERIAL PRIMARY KEY,
    motor_id INTEGER NOT NULL REFERENCES motor(id) ON DELETE RESTRICT,
    pelanggan_id INTEGER NOT NULL REFERENCES pelanggan(id) ON DELETE RESTRICT,
    buku_id INTEGER REFERENCES motor(id) ON DELETE RESTRICT,
    anggota_id INTEGER REFERENCES pelanggan(id) ON DELETE RESTRICT,
    tanggal_pinjam DATE NOT NULL DEFAULT CURRENT_DATE,
    tanggal_kembali DATE,
    durasi_hari INTEGER NOT NULL DEFAULT 1,
    total_biaya NUMERIC(12, 2) NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'dipinjam' CHECK (status IN ('dipinjam', 'kembali', 'selesai'))
);
