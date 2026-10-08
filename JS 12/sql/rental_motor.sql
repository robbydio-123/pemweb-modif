-- Skema Lengkap Database: rental_motor
-- Proyek: Rental Motor Lowokwaru Malang (Jobsheet 12)

CREATE TABLE IF NOT EXISTS motor (
    id SERIAL PRIMARY KEY,
    nama_motor VARCHAR(100) NOT NULL,
    merk VARCHAR(100) NOT NULL,
    tahun INTEGER NOT NULL CHECK (tahun BETWEEN 1990 AND 2100),
    plat_nomor VARCHAR(20) NOT NULL UNIQUE,
    tarif_harian NUMERIC(12, 2) NOT NULL CHECK (tarif_harian >= 0),
    status VARCHAR(20) NOT NULL DEFAULT 'tersedia' CHECK (status IN ('tersedia', 'disewa', 'servis')),
    gambar VARCHAR(500),
    deskripsi TEXT,
    tipe_cc VARCHAR(20) DEFAULT '125 cc',
    transmisi VARCHAR(20) DEFAULT 'Matic'
);

CREATE TABLE IF NOT EXISTS pelanggan (
    id SERIAL PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    no_pelanggan VARCHAR(50) NOT NULL UNIQUE,
    alamat VARCHAR(255),
    no_hp VARCHAR(30) NOT NULL
);

CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(30) NOT NULL DEFAULT 'petugas' CHECK (role IN ('petugas', 'admin', 'customer'))
);

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

-- Seed Data Motor dengan Foto Berkualitas & Spesifikasi Menarik
INSERT INTO motor (nama_motor, merk, tahun, plat_nomor, tarif_harian, status, tipe_cc, transmisi, deskripsi, gambar) VALUES
('Beat Street', 'Honda', 2023, 'N 1201 AB', 85000, 'tersedia', '110 cc', 'Matic', 'Honda Beat Street lincah dan sangat hemat bahan bakar. Cocok untuk keliling kota Malang, kampus, dan kulineran santai.', 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=800&q=80'),
('Vario 160 CBS', 'Honda', 2024, 'N 3303 EF', 120000, 'tersedia', '160 cc', 'Matic', 'Honda Vario 160 bertenaga eSP+ 4 katup dengan bagasi lapang 18 liter, USB charger handphone, dan tarikan halus untuk rute tanjakan.', 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=800&q=80'),
('Scoopy Stylish', 'Honda', 2023, 'N 4404 GH', 100000, 'tersedia', '110 cc', 'Matic', 'Desain retro modern yang stylish dan kekinian. Dilengkapi Smart Key System, gantungan barang luas, dan posisi duduk nyaman.', 'https://images.unsplash.com/photo-1599819811279-d5ad9cccf838?auto=format&fit=crop&w=800&q=80'),
('NMAX Connected ABS', 'Yamaha', 2023, 'N 2202 CD', 150000, 'disewa', '155 cc', 'Matic Maxi', 'Yamaha NMAX 155 Connected suspensi empuk, posisi berkendara rileks, sangat nyaman untuk touring ke Bromo, Batu, dan pantai Malang Selatan.', 'https://images.unsplash.com/photo-1571188654248-7a89213915f7?auto=format&fit=crop&w=800&q=80'),
('Aerox 155 CyberCity', 'Yamaha', 2023, 'N 5505 IJ', 135000, 'tersedia', '155 cc', 'Matic Sporty', 'Desain sporty agresif dengan mesin VVA bertenaga tinggi. Sangat stabil untuk manuver jalan perkotaan maupun jalan menanjak.', 'https://images.unsplash.com/photo-1609630875171-b1321377ee65?auto=format&fit=crop&w=800&q=80'),
('Vespa Sprint 150 i-Get', 'Vespa', 2024, 'N 8899 VP', 220000, 'tersedia', '150 cc', 'Matic Iconic', 'Skuter premium Italia yang mewah dan elegan. Sangat instagrammable untuk foto liburan keliling spot aesthetic di Malang & Kota Batu.', 'https://images.unsplash.com/photo-1622185135505-2d795003994a?auto=format&fit=crop&w=800&q=80'),
('PCX 160 RoadSync', 'Honda', 2024, 'N 7788 PC', 160000, 'tersedia', '160 cc', 'Matic Premium', 'Honda PCX 160 kemewahan berkendara dengan bagasi ekstra besar 30L, windshield tinggi, dan mesin halus minim getaran.', 'https://images.unsplash.com/photo-1558980664-3a031cf67ea8?auto=format&fit=crop&w=800&q=80')
ON CONFLICT (plat_nomor) DO NOTHING;

-- Seed Pelanggan
INSERT INTO pelanggan (nama, no_pelanggan, alamat, no_hp) VALUES
('Siti Aminah', 'P001', 'Jl. Sigura-gura No. 15, Lowokwaru, Malang', '081200000001'),
('Budi Santoso', 'P002', 'Jl. Bendungan Sutami No. 22, Lowokwaru, Malang', '081300000002'),
('Citra Lestari', 'P003', 'Jl. Soekarno Hatta No. 88, Lowokwaru, Malang', '081400000003')
ON CONFLICT (no_pelanggan) DO NOTHING;

-- Seed Petugas (password: rental123)
INSERT INTO users (nama, username, password, role) VALUES
('Petugas Lowokwaru', 'petugas', '$2y$10$w0u36oQjZt11YF617k6BquG0X9r8vN3bL9Wz3gHj6.Xw6c8.5.5.y', 'petugas')
ON CONFLICT (username) DO NOTHING;