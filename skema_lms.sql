CREATE DATABASE IF NOT EXISTS db_lms_paw;
USE db_lms_paw;

CREATE TABLE mata_kuliah (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    dosen VARCHAR(100) NOT NULL,
    semester TINYINT UNSIGNED NOT NULL
);

CREATE TABLE materi (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mata_kuliah_id INT UNSIGNED NOT NULL,
    judul VARCHAR(150) NOT NULL,
    isi TEXT NOT NULL,
    tautan VARCHAR(255) NOT NULL,
    FOREIGN KEY (mata_kuliah_id) REFERENCES mata_kuliah (id) ON DELETE RESTRICT
);

CREATE TABLE tugas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mata_kuliah_id INT UNSIGNED NOT NULL,
    judul VARCHAR(150) NOT NULL,
    instruksi TEXT NOT NULL,
    tenggat DATE NOT NULL,
    status VARCHAR(20) NOT NULL,
    FOREIGN KEY (mata_kuliah_id) REFERENCES mata_kuliah (id) ON DELETE RESTRICT
);

-- Data Awal Mata Kuliah
INSERT INTO mata_kuliah (kode, nama, dosen, semester) VALUES
('ALP-101', 'Algoritma dan Pemrograman', 'Dosen ALP', 1),
('PAW-101', 'Pengembangan Aplikasi Website', 'Dosen PAW', 3);

-- Data Awal Materi
INSERT INTO materi (mata_kuliah_id, judul, isi, tautan) VALUES
(2, 'Pengantar PHP & MySQL', 'Materi dasar mengenai koneksi database dan operasi CRUD dasar dengan PHP.', 'https://www.php.net');

-- Data Awal Tugas
INSERT INTO tugas (mata_kuliah_id, judul, instruksi, tenggat, status) VALUES
(2, 'Latihan CRUD', 'Buatlah program CRUD sederhana sesuai modul praktikum.', '2026-12-31', 'Aktif');