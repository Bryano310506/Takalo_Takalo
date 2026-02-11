-- 1. Nettoyage
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS historique_echange;
DROP TABLE IF EXISTS proprietaire_objet;
DROP TABLE IF EXISTS status;
DROP TABLE IF EXISTS photos;
DROP TABLE IF EXISTS objets;
DROP TABLE IF EXISTS categorie;
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS role;
SET FOREIGN_KEY_CHECKS = 1;

-- 2. Création des tables (Ta structure exacte)
CREATE TABLE role (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL
);

CREATE TABLE user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    mdp VARCHAR(255) NOT NULL,
    id_role INT NOT NULL,
    FOREIGN KEY (id_role) REFERENCES role(id)
);

CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL
);

CREATE TABLE objets (
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    id_categorie INT NOT NULL,
    prix DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_categorie) REFERENCES categorie(id)
);

CREATE TABLE photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_objet) REFERENCES objets(id_objet)
);

CREATE TABLE status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL,
    code VARCHAR(20) UNIQUE NOT NULL
);

CREATE TABLE proprietaire_objet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_objet INT NOT NULL,
    date_echange DATETIME NOT NULL,
    FOREIGN KEY (id_user) REFERENCES user(id_user),
    FOREIGN KEY (id_objet) REFERENCES objets(id_objet)
);

CREATE TABLE historique_echange (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_objet1 INT NOT NULL,
    id_objet2 INT NOT NULL,
    id_status INT NOT NULL,
    date_echange DATETIME,
    FOREIGN KEY (id_objet1) REFERENCES objets(id_objet),
    FOREIGN KEY (id_objet2) REFERENCES objets(id_objet),
    FOREIGN KEY (id_status) REFERENCES status(id)
);

-- 3. Insertion des données de test
INSERT INTO role (libelle) VALUES ('Administrateur'), ('Utilisateur');

INSERT INTO user (nom, mdp, id_role) VALUES 
('Jean', '123', 1),
('Maria', '456', 2),
('Rakoto', '789', 2);

INSERT INTO categorie (libelle) VALUES ('Électronique'), ('Mode'), ('Loisirs');

INSERT INTO status (libelle, code) VALUES 
('En attente', 'WAITING'),
('Accepté', 'ACCEPTED'),
('Refusé', 'REJECTED');

INSERT INTO objets (titre, description, id_categorie, prix) VALUES 
('iPhone 12', 'Bon état', 1, 350.00),
('VTT', 'Vélo rouge', 3, 200.00),
('Clavier RGB', 'Mécanique', 1, 80.00);

INSERT INTO photos (id_objet, nom) VALUES (1, 'iphone.jpg'), (2, 'vtt.png');

INSERT INTO proprietaire_objet (id_user, id_objet, date_echange) VALUES 
(2, 1, NOW()), -- Maria possède iPhone
(3, 2, NOW()), -- Rakoto possède VTT
(3, 3, NOW()); -- Rakoto possède Clavier

-- Échange : Objet 1 (Émetteur) vs Objet 2 (Récepteur)
INSERT INTO historique_echange (id_objet1, id_objet2, id_status, date_echange) VALUES 
(2, 1, (SELECT id FROM status WHERE code = 'WAITING'), NOW());