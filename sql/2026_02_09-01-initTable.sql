-- Création de la base de données Takalo Takalo
CREATE DATABASE IF NOT EXISTS takalo_takalo;
USE takalo_takalo;

-- Table role
CREATE TABLE role (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL
);

-- Table user
CREATE TABLE user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    mdp VARCHAR(255) NOT NULL,
    id_role INT NOT NULL,
    FOREIGN KEY (id_role) REFERENCES role(id)
);

-- Table categorie
CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL
);

-- Table objets
CREATE TABLE objets (
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    id_categorie INT NOT NULL,
    prix DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_categorie) REFERENCES categorie(id)
);

-- Table photos
CREATE TABLE photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_objet) REFERENCES objets(id_objet)
);

-- Table status
CREATE TABLE status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL
);

-- Table proprietaire_objet
CREATE TABLE proprietaire_objet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_objet INT NOT NULL,
    date_echange DATETIME NOT NULL,
    FOREIGN KEY (id_user) REFERENCES user(id_user),
    FOREIGN KEY (id_objet) REFERENCES objets(id_objet)
);

-- Table historique_echange
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
