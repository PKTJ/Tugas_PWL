CREATE DATABASE IF NOT EXISTS perpus;
USE perpus;
CREATE TABLE IF NOT EXISTS buku (
 id INT AUTO_INCREMENT PRIMARY KEY,
 judul VARCHAR(15) NOT NULL,
 penulis VARCHAR(100) NOT NULL,
 tahun_terbit VARCHAR(100)
);
INSERT INTO buku (judul, penulis, tahun_terbit) VALUES
('Laut Bercerita', 'Leila S. Chudori', '2017'),
('Bumi Manusia', 'Pramoedya Ananta Toer', '1980'),
('Filosofi Teras', 'Henry Menampiring', '2018'),
('The Midnight Library', 'Matt Haig', '2020'),
('Atomic Habits', 'James Clear', '2018');