-- Run this schema after connecting to the target PostgreSQL database.

CREATE TABLE IF NOT EXISTS motor (
    id SERIAL PRIMARY KEY,
    nama_motor VARCHAR(100) NOT NULL,
    merk VARCHAR(100) NOT NULL,
    tahun INTEGER NOT NULL CHECK (tahun BETWEEN 1990 AND 2100),
    plat_nomor VARCHAR(20) NOT NULL UNIQUE,
    tarif_harian NUMERIC(12, 2) NOT NULL CHECK (tarif_harian >= 0),
    status VARCHAR(20) NOT NULL DEFAULT 'tersedia' CHECK (status IN ('tersedia', 'disewa', 'servis'))
);

CREATE TABLE IF NOT EXISTS pelanggan (
    id SERIAL PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    no_pelanggan VARCHAR(50) NOT NULL UNIQUE,
    alamat VARCHAR(255),
    no_hp VARCHAR(30) NOT NULL
);

CREATE TABLE IF NOT EXISTS penyewaan (
    id SERIAL PRIMARY KEY,
    motor_id INTEGER NOT NULL REFERENCES motor(id) ON DELETE RESTRICT,
    nama_penyewa VARCHAR(150) NOT NULL,
    no_whatsapp VARCHAR(30) NOT NULL,
    no_ktp VARCHAR(50) NOT NULL,
    alamat VARCHAR(255) NOT NULL,
    durasi_hari INTEGER NOT NULL CHECK (durasi_hari BETWEEN 1 AND 30),
    catatan VARCHAR(500),
    status VARCHAR(30) NOT NULL DEFAULT 'menunggu' CHECK (status IN ('menunggu', 'dikonfirmasi', 'ditolak')),
    dibuat_pada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO motor (nama_motor, merk, tahun, plat_nomor, tarif_harian, status) VALUES
('Beat Street', 'Honda', 2023, 'N 1201 AB', 85000, 'tersedia'),
('NMAX Connected', 'Yamaha', 2022, 'N 2202 CD', 150000, 'disewa'),
('Vario 160', 'Honda', 2024, 'N 3303 EF', 120000, 'tersedia'),
('Scoopy Stylish', 'Honda', 2023, 'N 4404 GH', 100000, 'tersedia'),
('Aerox 155', 'Yamaha', 2021, 'N 5505 IJ', 135000, 'servis')
ON CONFLICT (plat_nomor) DO NOTHING;

INSERT INTO pelanggan (nama, no_pelanggan, alamat, no_hp) VALUES
('Siti Aminah', 'P001', 'Malang', '081200000001'),
('Budi Santoso', 'P002', 'Batu', '081300000002'),
('Citra Lestari', 'P003', 'Kepanjen', '081400000003')
ON CONFLICT (no_pelanggan) DO NOTHING;