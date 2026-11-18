-- Base de données : Gestion RH - Université de N'Djamena
CREATE DATABASE IF NOT EXISTS gestion_rh_n_djamena 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestion_rh_n_djamena;

-- Table Employés
CREATE TABLE IF NOT EXISTS employes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    poste VARCHAR(100) NOT NULL,
    departement VARCHAR(100) NOT NULL,
    salaire_base DECIMAL(10,2) NOT NULL,
    date_embauche DATE NOT NULL,
    id_superieur INT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_superieur) REFERENCES employes(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table Congés
CREATE TABLE IF NOT EXISTS conges (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_employe INT NOT NULL,
    type ENUM('annuel','maladie','exceptionnel') NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    motif TEXT NOT NULL,
    statut ENUM('en_attente','valide','refuse') DEFAULT 'en_attente',
    id_validateur INT NULL,
    FOREIGN KEY (id_employe) REFERENCES employes(id) ON DELETE CASCADE,
    FOREIGN KEY (id_validateur) REFERENCES employes(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table Paie
CREATE TABLE IF NOT EXISTS paie (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_employe INT NOT NULL,
    mois TINYINT NOT NULL CHECK(mois BETWEEN 1 AND 12),
    annee INT NOT NULL,
    salaire_base DECIMAL(10,2) NOT NULL,
    primes DECIMAL(10,2) DEFAULT 0.00,
    deductions DECIMAL(10,2) DEFAULT 0.00,
    salaire_net DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_employe) REFERENCES employes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_fiche_paie (id_employe, mois, annee)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Compte administrateur par défaut (mot de passe : admin123)
INSERT INTO employes (nom, prenom, email, poste, departement, salaire_base, date_embauche, id_superieur, mot_de_passe)
VALUES ('ADMIN', 'Directeur', 'admin@univ-ndjamena.td', 'Directeur Général', 'Direction', 250000.00, '2020-01-01', NULL, '$2y$10$EixZaYb4xU58Gpq1R0yWbeb00LU5qUaK6x7bT2C0sQ8rT2zq17u5y');

