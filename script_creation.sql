-- Suppression des tables si elles existent (pour pouvoir relancer le script)
DROP TABLE IF EXISTS MATERIEL;
DROP TABLE IF EXISTS CATEGORIE;

-- 1. Création de la table CATEGORIE
CREATE TABLE CATEGORIE (
    id_type INT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

-- 2. Création de la table MATERIEL
-- Note : id_parent fait référence à id_mat pour la hiérarchie
CREATE TABLE MATERIEL (
    id_mat INT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    annee INT,
    details TEXT,
    id_type INT NOT NULL,
    id_parent INT DEFAULT NULL,
    CONSTRAINT fk_type FOREIGN KEY (id_type) REFERENCES CATEGORIE(id_type),
    CONSTRAINT fk_parent FOREIGN KEY (id_parent) REFERENCES MATERIEL(id_mat)
);

-- 3. Insertion des catégories
INSERT INTO CATEGORIE (id_type, libelle) VALUES 
(1, 'PC'), (2, 'Écran'), (3, 'CPU'), (4, 'RAM'), (5, 'Disque'), 
(6, 'GPU'), (7, 'Carte réseau'), (8, 'OS'), (9, 'Batterie');

-- 4. Insertion du matériel (PC et Écrans - sans parents)
INSERT INTO MATERIEL (id_mat, nom, annee, details, id_type, id_parent) VALUES
(1, 'PC 1 – Unité centrale', 2016, NULL, 1, NULL),
(2, 'PC 2 – Unité centrale', 2017, NULL, 1, NULL),
(3, 'PC 3 – Portable', 2015, 'Inspiron 15-3558', 1, NULL),
(4, 'Écran A', 2012, 'HP LA1951g – 19’’ – 1280×1024 – 60 Hz', 2, NULL),
(5, 'Écran B', 2010, 'Dell E178FP – 17’’ – 1280×1024', 2, NULL),
(6, 'Écran C', 2009, 'Samsung 933SN – 18.5’’ – 1366×768', 2, NULL);

-- 5. Insertion des composants (liés à leur PC respectif via id_parent)
INSERT INTO MATERIEL (id_mat, nom, annee, details, id_type, id_parent) VALUES
(10, 'CPU PC1', 2016, 'Intel Core i3-6100', 3, 1),
(11, 'RAM PC1', 2016, '4 Go DDR4 (1×4 Go)', 4, 1),
(12, 'Disque PC1', 2016, 'HDD Seagate 500 Go', 5, 1),
(13, 'GPU PC1', 2016, 'Intel HD 530', 6, 1),
(14, 'Carte réseau PC1', 2016, '1 Gbps', 7, 1),
(15, 'OS PC1', 2016, 'Windows 10 Pro', 8, 1),
(20, 'CPU PC2', 2017, 'Intel Core i5-7500', 3, 2),
(21, 'RAM PC2', 2017, '8 Go DDR4 (2×4 Go)', 4, 2),
(22, 'Disque PC2', 2017, 'SSD A400 240 Go', 5, 2),
(23, 'GPU PC2', 2017, 'Intel HD 630', 6, 2),
(24, 'Carte réseau PC2', 2017, '1 Gbps', 7, 2),
(25, 'OS PC2', 2017, 'Pas d’OS', 8, 2),
(30, 'CPU PC3', 2015, 'Intel Core i3-5005U', 3, 3),
(31, 'RAM PC3', 2015, '4 Go DDR3L', 4, 3),
(32, 'Disque PC3', 2015, 'HDD WD Blue 500 Go', 5, 3),
(33, 'Batterie PC3', 2015, 'usée (≈ 40 min)', 9, 3),
(34, 'OS PC3', 2015, 'Windows 10 Pro', 8, 3);






SELECT 
    m.id_mat AS ID,
    m.nom AS Nom,
    m.annee AS Année,
    m.details AS Détails,
    c.libelle AS Type,
    p.nom AS "Appartient à"
FROM MATERIEL m
LEFT JOIN CATEGORIE c ON m.id_type = c.id_type
LEFT JOIN MATERIEL p ON m.id_parent = p.id_mat
ORDER BY m.id_mat;
