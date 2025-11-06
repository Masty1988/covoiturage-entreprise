-- ============================================
-- Script de création de la base de données
-- Application: Touche pas au Klaxon
-- ============================================

-- Suppression des tables si elles existent (pour réinitialisation)
DROP TABLE IF EXISTS trajets;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS agences;

-- ============================================
-- Table: agences
-- Description: Liste des agences (villes) de l'entreprise
-- ============================================
CREATE TABLE agences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_nom (nom)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: users
-- Description: Employés de l'entreprise
-- ============================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_admin (is_admin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: trajets
-- Description: Trajets de covoiturage proposés
-- ============================================
CREATE TABLE trajets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agence_depart_id INT NOT NULL,
    agence_arrivee_id INT NOT NULL,
    date_depart DATETIME NOT NULL,
    date_arrivee DATETIME NOT NULL,
    places_total INT NOT NULL,
    places_disponibles INT NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Contraintes de clés étrangères
    CONSTRAINT fk_trajet_agence_depart 
        FOREIGN KEY (agence_depart_id) 
        REFERENCES agences(id) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE,
    
    CONSTRAINT fk_trajet_agence_arrivee 
        FOREIGN KEY (agence_arrivee_id) 
        REFERENCES agences(id) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE,
    
    CONSTRAINT fk_trajet_user 
        FOREIGN KEY (user_id) 
        REFERENCES users(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    
    -- Contraintes de validation
    CONSTRAINT chk_agences_differentes 
        CHECK (agence_depart_id != agence_arrivee_id),
    
    CONSTRAINT chk_dates_coherentes 
        CHECK (date_arrivee > date_depart),
    
    CONSTRAINT chk_places_positives 
        CHECK (places_total > 0),
    
    CONSTRAINT chk_places_disponibles 
        CHECK (places_disponibles >= 0 AND places_disponibles <= places_total),
    
    -- Index pour optimiser les requêtes
    INDEX idx_date_depart (date_depart),
    INDEX idx_agence_depart (agence_depart_id),
    INDEX idx_agence_arrivee (agence_arrivee_id),
    INDEX idx_user (user_id),
    INDEX idx_places_disponibles (places_disponibles)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Vérification de la création des tables
-- ============================================
SHOW TABLES;