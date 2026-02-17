CREATE DATABASE IF NOT EXISTS ap4_materiel
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE ap4_materiel;

DROP TABLE IF EXISTS materiel;

CREATE TABLE materiel (
    id INT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    annee INT NOT NULL,
    details TEXT NULL,
    type VARCHAR(100) NOT NULL,
    parent INT NULL,
    FOREIGN KEY (parent) REFERENCES materiel(id)
);

INSERT INTO materiel (id, nom, annee, details, type, parent) VALUES
(1, 'PC 1 – Unité centrale', 2016, NULL, 'PC', NULL),
(2, 'PC 2 – Unité centrale', 2017, NULL, 'PC', NULL),
(3, 'PC 3 – Portable', 2015, 'Inspiron 15-3558', 'PC', NULL),

(4, 'Écran A', 2012, 'HP LA1951g – 19’’ – 1280×1024 – 60 Hz', 'Écran', NULL),
(5, 'Écran B', 2010, 'Dell E178FP – 17’’ – 1280×1024', 'Écran', NULL),
(6, 'Écran C', 2009, 'Samsung 933SN – 18.5’’ – 1366×768', 'Écran', NULL),

(10, 'CPU PC1', 2016, 'Intel Core i3-6100', 'CPU', 1),
(11, 'RAM PC1', 2016, '4 Go DDR4 (1×4 Go)', 'RAM', 1),
(12, 'Disque PC1', 2016, 'HDD Seagate 500 Go', 'Disque', 1),
(13, 'GPU PC1', 2016, 'Intel HD 530', 'GPU', 1),
(14, 'Carte réseau PC1', 2016, '1 Gbps', 'Carte réseau', 1),
(15, 'OS PC1', 2016, 'Windows 10 Pro', 'OS', 1),

(20, 'CPU PC2', 2017, 'Intel Core i5-7500', 'CPU', 2),
(21, 'RAM PC2', 2017, '8 Go DDR4 (2×4 Go)', 'RAM', 2),
(22, 'Disque PC2', 2017, 'SSD A400 240 Go', 'Disque', 2),
(23, 'GPU PC2', 2017, 'Intel HD 630', 'GPU', 2),
(24, 'Carte réseau PC2', 2017, '1 Gbps', 'Carte réseau', 2),
(25, 'OS PC2', 2017, 'Pas d’OS', 'OS', 2),

(30, 'CPU PC3', 2015, 'Intel Core i3-5005U', 'CPU', 3),
(31, 'RAM PC3', 2015, '4 Go DDR3L', 'RAM', 3),
(32, 'Disque PC3', 2015, 'HDD WD Blue 500 Go', 'Disque', 3),
(33, 'Batterie PC3', 2015, 'usée (≈ 40 min)', 'Batterie', 3),
(34, 'OS PC3', 2015, 'Windows 10 Pro', 'OS', 3);
