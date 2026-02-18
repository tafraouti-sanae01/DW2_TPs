-- Base de données pour le système de gestion des pétitions
-- Créer la base de données si elle n'existe pas
CREATE DATABASE IF NOT EXISTS petition CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE petition;

-- Table des pétitions
CREATE TABLE IF NOT EXISTS petitions (
    IDP INT AUTO_INCREMENT PRIMARY KEY,
    TitreP VARCHAR(255) NOT NULL,
    DescriptionP TEXT NOT NULL,
    DateAjoutP DATE NOT NULL DEFAULT (CURRENT_DATE),
    DateFinP DATE NOT NULL,
    NomPorteurP VARCHAR(100) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    INDEX idx_email (Email),
    INDEX idx_date_fin (DateFinP),
    INDEX idx_date_ajout (DateAjoutP)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des signatures
CREATE TABLE IF NOT EXISTS signatures (
    IDS INT AUTO_INCREMENT PRIMARY KEY,
    IDP INT NOT NULL,
    NomS VARCHAR(100) NOT NULL,
    PrenomS VARCHAR(100) NOT NULL,
    PaysS VARCHAR(100) NOT NULL,
    EmailS VARCHAR(100) NOT NULL UNIQUE,
    DateS DATE NOT NULL DEFAULT (CURRENT_DATE),
    HeureS TIME NOT NULL DEFAULT (CURRENT_TIME),
    FOREIGN KEY (IDP) REFERENCES petitions(IDP) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_idp (IDP),
    INDEX idx_email_sig (EmailS),
    INDEX idx_date_heure (DateS, HeureS)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insérer quelques pétitions de test
INSERT INTO petitions (TitreP, DescriptionP, DateFinP, NomPorteurP, Email) VALUES
('Pour un environnement plus propre', 'Cette pétition vise à encourager des pratiques écologiques dans notre ville. Nous demandons la mise en place de plus de poubelles de tri et de zones vertes.', '2025-12-31', 'Marie Dupont', 'marie.dupont@example.com'),
('Amélioration des transports publics', 'Nous demandons l\'augmentation de la fréquence des bus et l\'ajout de nouvelles lignes pour desservir les quartiers périphériques.', '2025-11-30', 'Ahmed El Fassi', 'ahmed.elfassi@example.com'),
('Protection des animaux', 'Pour une meilleure protection des animaux de la rue et la création de refuges municipaux.', '2025-10-31', 'Sophie Martin', 'sophie.martin@example.com'),
('Sécurité routière', 'Installation de passages piétons sécurisés et de feux de signalisation aux carrefours dangereux.', '2025-09-30', 'Karim Alaoui', 'karim.alaoui@example.com'),
('Bibliothèque municipale', 'Demande d\'extension des horaires d\'ouverture de la bibliothèque municipale et d\'achat de nouveaux livres.', '2025-08-31', 'Fatima Zahra', 'fatima.zahra@example.com');

-- Insérer quelques signatures de test
INSERT INTO signatures (IDP, NomS, PrenomS, PaysS, EmailS) VALUES
-- Signatures pour la pétition 1 (Environnement)
(1, 'Benali', 'Youssef', 'Maroc', 'youssef.benali@example.com'),
(1, 'Cohen', 'Sarah', 'France', 'sarah.cohen@example.com'),
(1, 'El Amrani', 'Laila', 'Maroc', 'laila.elamrani@example.com'),
(1, 'Dupuis', 'Jean', 'Belgique', 'jean.dupuis@example.com'),
(1, 'Tazi', 'Omar', 'Maroc', 'omar.tazi@example.com'),

-- Signatures pour la pétition 2 (Transports)
(2, 'Mansouri', 'Rachid', 'Maroc', 'rachid.mansouri@example.com'),
(2, 'Bernard', 'Claire', 'France', 'claire.bernard@example.com'),
(2, 'Idrissi', 'Nadia', 'Maroc', 'nadia.idrissi@example.com'),

-- Signatures pour la pétition 3 (Animaux)
(3, 'Aziz', 'Samira', 'Maroc', 'samira.aziz@example.com'),
(3, 'Laurent', 'Marc', 'France', 'marc.laurent@example.com'),
(3, 'Belkacem', 'Amina', 'Maroc', 'amina.belkacem@example.com'),
(3, 'Morel', 'Julie', 'France', 'julie.morel@example.com'),

-- Signatures pour la pétition 4 (Sécurité)
(4, 'Chakir', 'Hassan', 'Maroc', 'hassan.chakir@example.com'),
(4, 'Roux', 'Pierre', 'France', 'pierre.roux@example.com'),

-- Signatures pour la pétition 5 (Bibliothèque)
(5, 'Alami', 'Zineb', 'Maroc', 'zineb.alami@example.com');

-- Vues pour faciliter les requêtes

-- Vue pour obtenir les pétitions avec le nombre de signatures
CREATE OR REPLACE VIEW v_petitions_signatures AS
SELECT 
    p.*,
    COUNT(s.IDS) AS NombreSignatures,
    CASE 
        WHEN p.DateFinP < CURDATE() THEN 'Expirée'
        ELSE 'Active'
    END AS Statut
FROM petitions p
LEFT JOIN signatures s ON p.IDP = s.IDP
GROUP BY p.IDP;

-- Vue pour les statistiques globales
CREATE OR REPLACE VIEW v_statistiques_globales AS
SELECT 
    (SELECT COUNT(*) FROM petitions) AS TotalPetitions,
    (SELECT COUNT(*) FROM signatures) AS TotalSignatures,
    (SELECT COUNT(*) FROM petitions WHERE DateFinP >= CURDATE()) AS PetitionsActives,
    (SELECT COUNT(*) FROM petitions WHERE DateFinP < CURDATE()) AS PetitionsExpirees;

-- Requêtes utiles pour le débogage

-- Afficher toutes les pétitions avec leurs signatures
-- SELECT * FROM v_petitions_signatures ORDER BY NombreSignatures DESC;

-- Afficher les statistiques globales
-- SELECT * FROM v_statistiques_globales;

-- Trouver la pétition avec le plus de signatures
-- SELECT * FROM v_petitions_signatures ORDER BY NombreSignatures DESC LIMIT 1;

-- Afficher les 10 dernières signatures
-- SELECT s.*, p.TitreP FROM signatures s 
-- JOIN petitions p ON s.IDP = p.IDP 
-- ORDER BY s.DateS DESC, s.HeureS DESC LIMIT 10;

-- Compter les signatures par pétition
-- SELECT p.TitreP, COUNT(s.IDS) as Signatures 
-- FROM petitions p 
-- LEFT JOIN signatures s ON p.IDP = s.IDP 
-- GROUP BY p.IDP 
-- ORDER BY Signatures DESC;

-- Vérifier les emails en double (ne devrait pas exister)
-- SELECT Email, COUNT(*) FROM petitions GROUP BY Email HAVING COUNT(*) > 1;
-- SELECT EmailS, COUNT(*) FROM signatures GROUP BY EmailS HAVING COUNT(*) > 1;