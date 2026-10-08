-- Skema Database: users (Jobsheet 10 Autentikasi)
-- Proyek: Rental Motor Lowokwaru Malang

CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(30) NOT NULL DEFAULT 'petugas' CHECK (role IN ('petugas', 'admin', 'customer'))
);

-- Akun bawaan (password default: rental123)
-- Hash dibuat dengan password_hash('rental123', PASSWORD_DEFAULT)
INSERT INTO users (nama, username, password, role)
VALUES ('Petugas Lowokwaru', 'petugas', '$2y$10$w0u36oQjZt11YF617k6BquG0X9r8vN3bL9Wz3gHj6.Xw6c8.5.5.y', 'petugas')
ON CONFLICT (username) DO NOTHING;
