CREATE DATABASE IF NOT EXISTS `db_debang_guest` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_debang_guest`;

-- 1. Tabel Pendaftar Baru (Calon Mahasiswa)
CREATE TABLE IF NOT EXISTS `pendaftar` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_lengkap` VARCHAR(150) NOT NULL,
    `no_wa` VARCHAR(30) NOT NULL,
    `no_wa_ortu` VARCHAR(30) NOT NULL,
    `tempat_tinggal` TEXT NOT NULL,
    `jurusan` VARCHAR(100) NOT NULL,
    `tanggal_daftar` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabel 9 Kamar
CREATE TABLE IF NOT EXISTS `kamar` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nomor_kamar` VARCHAR(10) NOT NULL UNIQUE,
    `status` ENUM('tersedia', 'terisi') DEFAULT 'tersedia',
    `nama_penghuni` VARCHAR(150) DEFAULT NULL,
    `no_wa_penghuni` VARCHAR(30) DEFAULT NULL,
    `jatuh_tempo` DATE DEFAULT NULL,
    `status_bayar` ENUM('lunas', 'jatuh_tempo') DEFAULT 'lunas'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabel Catatan Keuangan (Kas)
CREATE TABLE IF NOT EXISTS `keuangan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `tanggal` DATE NOT NULL,
    `keterangan` VARCHAR(255) NOT NULL,
    `tipe` ENUM('in', 'out') NOT NULL,
    `nominal` DECIMAL(12, 2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inisialisasi Data 9 Kamar Debang Guest
INSERT INTO `kamar` (`nomor_kamar`, `status`, `nama_penghuni`, `no_wa_penghuni`, `jatuh_tempo`, `status_bayar`) VALUES
('Kamar 01', 'terisi', 'Dimas Prasetyo', '6281234567801', '2026-11-15', 'lunas'),
('Kamar 02', 'tersedia', NULL, NULL, NULL, 'lunas'),
('Kamar 03', 'terisi', 'Rian Maulana', '6281234567803', '2026-11-03', 'jatuh_tempo'),
('Kamar 04', 'tersedia', NULL, NULL, NULL, 'lunas'),
('Kamar 05', 'tersedia', NULL, NULL, NULL, 'lunas'),
('Kamar 06', 'terisi', 'Farhan Hidayat', '6281234567806', '2026-11-20', 'lunas'),
('Kamar 07', 'tersedia', NULL, NULL, NULL, 'lunas'),
('Kamar 08', 'tersedia', NULL, NULL, NULL, 'lunas'),
('Kamar 09', 'tersedia', NULL, NULL, NULL, 'lunas')
ON DUPLICATE KEY UPDATE `nomor_kamar` = `nomor_kamar`;