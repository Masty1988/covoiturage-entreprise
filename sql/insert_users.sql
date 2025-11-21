-- ============================================
-- Insertion des utilisateurs
-- Mot de passe par défaut : "password123"
-- Hash : $2y$10$ewbSJ3axBab5kovCKAQUH.pbeJcPxaeXTVOkrgAnHxWdNe0J2Utb6
-- ============================================
USE covoiturage_entreprise;

-- Admin (pour les tests)
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, role) VALUES
('Admin', 'Super', 'admin@entreprise.fr', '$2y$10$ewbSJ3axBab5kovCKAQUH.pbeJcPxaeXTVOkrgAnHxWdNe0J2Utb6', '0600000000', 'admin');

-- Employés (données fournies)
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, role) VALUES
('Martin', 'Alexandre', 'alexandre.martin@email.fr', '$2y$10$ewbSJ3axBab5kovCKAQUH.pbeJcPxaeXTVOkrgAnHxWdNe0J2Utb6', '0612345678', 'employe'),
('Dubois', 'Sophie', 'sophie.dubois@email.fr', '$2y$10$ewbSJ3axBab5kovCKAQUH.pbeJcPxaeXTVOkrgAnHxWdNe0J2Utb6', '0698765432', 'employe'),
('Bernard', 'Julien', 'julien.bernard@email.fr', '$2y$10$ewbSJ3axBab5kovCKAQUH.pbeJcPxaeXTVOkrgAnHxWdNe0J2Utb6', '0622446688', 'employe'),
('Moreau', 'Camille', 'camille.moreau@email.fr', '$2y$10$ewbSJ3axBab5kovCKAQUH.pbeJcPxaeXTVOkrgAnHxWdNe0J2Utb6', '0611223344', 'employe'),
('Lefèvre', 'Lucie', 'lucie.lefevre@email.fr', '$2y$10$ewbSJ3axBab5kovCKAQUH.pbeJcPxaeXTVOkrgAnHxWdNe0J2Utb6', '0777889900', 'employe'),
('Leroy', 'Thomas', 'thomas.leroy@email.fr', '$2y$10$ewbSJ3axBab5kovCKAQUH.pbeJcPxaeXTVOkrgAnHxWdNe0J2Utb6', '0655443322', 'employe'),
('Roux', 'Chloé', 'chloe.roux@email.fr', '$2y$10$ewbSJ3axBab5kovCKAQUH.pbeJcPxaeXTVOkrgAnHxWdNe0J2Utb6', '0633221199', 'employe'),
('Petit', 'Maxime', 'maxime.petit@email.fr', '$2y$10$ewbSJ3axBab5kovCKAQUH.pbeJcPxaeXTVOkrgAnHxWdNe0J2Utb6', '0766778899', 'employe'),
('Garnier', 'Laura', 'laura.garnier@email.fr', '$2y$10$ewbSJ3axBab5kovCKAQUH.pbeJcPxaeXTVOkrgAnHxWdNe0J2Utb6', '0688776655', 'employe'),
('Dupuis', 'Antoine', 'antoine.dupuis@email.fr', '$2y$10$ewbSJ3axBab5kovCKAQUH.pbeJcPxaeXTVOkrgAnHxWdNe0J2Utb6', '0744556677', 'employe'),
('Lefebvre', 'Emma', 'emma.lefebvre@email.fr', '$2y$10$ewbSJ3axBab5kovCKAQUH.pbeJcPxaeXTVOkrgAnHxWdNe0J2Utb6', '0699887766', 'employe'),
('Fontaine', 'Louis', 'louis.fontaine@email.fr', '$2y$10$ewbSJ3axBab5kovCKAQUH.pbeJcPxaeXTVOkrgAnHxWdNe0J2Utb6', '0655667788', 'employe'),
('Chevalier', 'Clara', 'clara.chevalier@email.fr', '$2y$10$ewbSJ3axBab5kovCKAQUH.pbeJcPxaeXTVOkrgAnHxWdNe0J2Utb6', '0788990011', 'employe'),
('Robin', 'Nicolas', 'nicolas.robin@email.fr', '$2y$10$ewbSJ3axBab5kovCKAQUH.pbeJcPxaeXTVOkrgAnHxWdNe0J2Utb6', '0644332211', 'employe'),
('Gauthier', 'Marine', 'marine.gauthier@email.fr', '$2y$10$ewbSJ3axBab5kovCKAQUH.pbeJcPxaeXTVOkrgAnHxWdNe0J2Utb6', '0677889922', 'employe'),
('Fournier', 'Pierre', 'pierre.fournier@email.fr', '$2y$10$ewbSJ3axBab5kovCKAQUH.pbeJcPxaeXTVOkrgAnHxWdNe0J2Utb6', '0722334455', 'employe'),
('Girard', 'Sarah', 'sarah.girard@email.fr', '$2y$10$ewbSJ3axBab5kovCKAQUH.pbeJcPxaeXTVOkrgAnHxWdNe0J2Utb6', '0688665544', 'employe'),
('Lambert', 'Hugo', 'hugo.lambert@email.fr', '$2y$10$ewbSJ3axBab5kovCKAQUH.pbeJcPxaeXTVOkrgAnHxWdNe0J2Utb6', '0611223366', 'employe'),
('Masson', 'Julie', 'julie.masson@email.fr', '$2y$10$ewbSJ3axBab5kovCKAQUH.pbeJcPxaeXTVOkrgAnHxWdNe0J2Utb6', '0733445566', 'employe'),
('Henry', 'Arthur', 'arthur.henry@email.fr', '$2y$10$ewbSJ3axBab5kovCKAQUH.pbeJcPxaeXTVOkrgAnHxWdNe0J2Utb6', '0666554433', 'employe');