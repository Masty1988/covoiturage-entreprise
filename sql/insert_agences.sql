-- ============================================
-- Script d'insertion des agences
-- Application: Touche pas au Klaxon
-- ============================================

INSERT INTO agences (nom) VALUES
('Paris'),
('Lyon'),
('Marseille'),
('Toulouse'),
('Nice'),
('Nantes'),
('Strasbourg'),
('Montpellier'),
('Bordeaux'),
('Lille'),
('Rennes'),
('Reims');

-- Vérification
SELECT * FROM agences ORDER BY nom;