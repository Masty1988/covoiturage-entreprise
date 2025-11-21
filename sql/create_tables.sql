-- ============================================
-- Base de données : Covoiturage Entreprise
-- ============================================

DROP DATABASE IF EXISTS covoiturage_entreprise;
CREATE DATABASE covoiturage_entreprise CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE covoiturage_entreprise;

-- ============================================
-- Table : agences (villes)
-- ============================================
CREATE TABLE agences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_ville VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- Table : utilisateurs (employés + admin)
-- ============================================
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    role ENUM('employe', 'admin') DEFAULT 'employe',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- Table : trajets
-- ============================================
CREATE TABLE trajets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agence_depart_id INT NOT NULL,
    agence_arrivee_id INT NOT NULL,
    date_heure_depart DATETIME NOT NULL,
    date_heure_arrivee DATETIME NOT NULL,
    places_totales INT NOT NULL,
    places_disponibles INT NOT NULL,
    conducteur_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Clés étrangères
    FOREIGN KEY (agence_depart_id) REFERENCES agences(id) ON DELETE CASCADE,
    FOREIGN KEY (agence_arrivee_id) REFERENCES agences(id) ON DELETE CASCADE,
    FOREIGN KEY (conducteur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    
    -- Contraintes
    CHECK (places_disponibles <= places_totales),
    CHECK (places_disponibles >= 0),
    CHECK (date_heure_arrivee > date_heure_depart),
    CHECK (agence_depart_id != agence_arrivee_id)
) ENGINE=InnoDB;

-- ============================================
-- Index pour optimiser les requêtes
-- ============================================
CREATE INDEX idx_trajets_depart ON trajets(date_heure_depart);
CREATE INDEX idx_trajets_places ON trajets(places_disponibles);
CREATE INDEX idx_trajets_conducteur ON trajets(conducteur_id);