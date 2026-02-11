DROP DATABASE IF EXISTS takalo_takalo;

-- Création de la base de données Takalo Takalo
CREATE DATABASE IF NOT EXISTS takalo_takalo;
USE takalo_takalo;

-- Table role
CREATE TABLE role (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL UNIQUE
);

-- Table user
CREATE TABLE user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    mdp VARCHAR(255) NOT NULL,
    id_role INT NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_role) REFERENCES role(id)
);

-- Table categorie
CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL UNIQUE
);

-- Table objets
CREATE TABLE objets (
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    id_categorie INT NOT NULL,
    prix DECIMAL(10, 2),
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categorie) REFERENCES categorie(id)
);

-- Table photos
CREATE TABLE photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_objet) REFERENCES objets(id_objet) ON DELETE CASCADE
);

-- Table status (pour les états des échanges)
CREATE TABLE status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL UNIQUE,
    libelle VARCHAR(255) NOT NULL UNIQUE
    -- Valeurs : 'en attente', 'accepté', 'refusé', 'rejeté', 'complété'
);

-- Table proprietaire_objet (qui possède quel objet actuellement)
CREATE TABLE proprietaire_objet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_objet INT NOT NULL,
    id_echange INT,
    date_debut DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_fin DATETIME,
    FOREIGN KEY (id_user) REFERENCES user(id_user),
    FOREIGN KEY (id_objet) REFERENCES objets(id_objet),
    UNIQUE(id_objet) -- Un objet ne peut avoir qu'un propriétaire
);

-- Table historique_echange (proposition d'échange entre deux utilisateurs)
CREATE TABLE historique_echange (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_emetteur INT NOT NULL, -- Celui qui propose l'échange
    id_recepteur INT NOT NULL, -- Celui qui reçoit la proposition
    id_objet_propose INT NOT NULL, -- L'objet proposé par l'émetteur
    id_objet_demande INT NOT NULL, -- L'objet demandé (appartenant au récepteur)
    id_status INT NOT NULL,
    date_debut DATETIME DEFAULT CURRENT_TIMESTAMP, -- Date de la proposition
    date_fin DATETIME, -- Date d'acceptation/refus/rejet
    FOREIGN KEY (id_emetteur) REFERENCES user(id_user),
    FOREIGN KEY (id_recepteur) REFERENCES user(id_user),
    FOREIGN KEY (id_objet_propose) REFERENCES objets(id_objet),
    FOREIGN KEY (id_objet_demande) REFERENCES objets(id_objet),
    FOREIGN KEY (id_status) REFERENCES status(id)
);