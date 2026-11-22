-- TP7 : Centre de formation (formations, etudiants, modules)
-- À importer dans phpMyAdmin (onglet "Importer") ou à exécuter dans l'onglet SQL.

CREATE DATABASE IF NOT EXISTS tp7_crud
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE tp7_crud;

DROP TABLE IF EXISTS modules;
DROP TABLE IF EXISTS etudiants;
DROP TABLE IF EXISTS formations;

-- Table parent
CREATE TABLE formations (
  id_formation INT UNSIGNED NOT NULL AUTO_INCREMENT,
  titre        VARCHAR(120) NOT NULL,
  duree_mois   TINYINT UNSIGNED NOT NULL,
  niveau       VARCHAR(50) NOT NULL,
  PRIMARY KEY (id_formation)
) ENGINE=InnoDB;

-- Un étudiant appartient à UNE formation (1 formation -> N étudiants)
CREATE TABLE etudiants (
  id_etudiant  INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nom          VARCHAR(80)  NOT NULL,
  prenom       VARCHAR(80)  NOT NULL,
  email        VARCHAR(150) NOT NULL,
  id_formation INT UNSIGNED NOT NULL,
  PRIMARY KEY (id_etudiant),
  CONSTRAINT fk_etudiants_formation
    FOREIGN KEY (id_formation) REFERENCES formations (id_formation)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Un module appartient à UNE formation (1 formation -> N modules)
CREATE TABLE modules (
  id_module      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  intitule       VARCHAR(120) NOT NULL,
  volume_horaire SMALLINT UNSIGNED NOT NULL,
  id_formation   INT UNSIGNED NOT NULL,
  PRIMARY KEY (id_module),
  CONSTRAINT fk_modules_formation
    FOREIGN KEY (id_formation) REFERENCES formations (id_formation)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Données de test
INSERT INTO formations (titre, duree_mois, niveau) VALUES
  ('Développement Web Full Stack', 12, 'Licence'),
  ('Cybersécurité', 10, 'Licence'),
  ('Data Analyst', 8, 'Certification');

INSERT INTO etudiants (nom, prenom, email, id_formation) VALUES
  ('Benali', 'Sara', 'sara.benali@example.com', 1),
  ('Alaoui', 'Youssef', 'youssef.alaoui@example.com', 1),
  ('Idrissi', 'Meryem', 'meryem.idrissi@example.com', 2),
  ('Tazi', 'Omar', 'omar.tazi@example.com', 3);

INSERT INTO modules (intitule, volume_horaire, id_formation) VALUES
  ('HTML et CSS', 30, 1),
  ('PHP et MySQL', 45, 1),
  ('Réseaux et sécurité', 40, 2),
  ('Cryptographie', 35, 2),
  ('Statistiques et Excel', 30, 3),
  ('Visualisation de données', 25, 3);
