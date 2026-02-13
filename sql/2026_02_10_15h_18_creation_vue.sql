DROP DATABASE IF EXISTS takalo_takalo;

-- Création de la base de données Takalo Takalo
CREATE DATABASE IF NOT EXISTS takalo_takalo;

USE takalo_takalo;

-- Table role
CREATE TABLE
    role (
        id INT AUTO_INCREMENT PRIMARY KEY,
        libelle VARCHAR(255) NOT NULL UNIQUE
    );

-- Table user
CREATE TABLE
    user (
        id_user INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(255) NOT NULL,
        mdp VARCHAR(255) NOT NULL,
        id_role INT NOT NULL,
        date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (id_role) REFERENCES role (id)
    );

-- Table categorie
CREATE TABLE
    categorie (
        id INT AUTO_INCREMENT PRIMARY KEY,
        libelle VARCHAR(255) NOT NULL UNIQUE
    );

-- Table objets
CREATE TABLE
    objets (
        id_objet INT AUTO_INCREMENT PRIMARY KEY,
        titre VARCHAR(255) NOT NULL,
        description TEXT,
        id_categorie INT NOT NULL,
        prix DECIMAL(10, 2),
        date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (id_categorie) REFERENCES categorie (id)
    );

-- Table photos
CREATE TABLE
    photos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_objet INT NOT NULL,
        nom VARCHAR(255) NOT NULL,
        date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (id_objet) REFERENCES objets (id_objet) ON DELETE CASCADE
    );

-- Table status (pour les états des échanges)
CREATE TABLE
    status (
        id INT AUTO_INCREMENT PRIMARY KEY,
        code VARCHAR(20) NOT NULL UNIQUE,
        libelle VARCHAR(255) NOT NULL UNIQUE
        -- Valeurs : 'en attente', 'accepté', 'refusé', 'rejeté', 'complété'
    );

-- Table proprietaire_objet (qui possède quel objet actuellement)
CREATE TABLE
    proprietaire_objet (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_user INT NOT NULL,
        id_objet INT NOT NULL,
        id_echange INT,
        date_debut DATETIME DEFAULT CURRENT_TIMESTAMP,
        date_fin DATETIME,
        FOREIGN KEY (id_user) REFERENCES user (id_user),
        FOREIGN KEY (id_objet) REFERENCES objets (id_objet),
        UNIQUE (id_objet) -- Un objet ne peut avoir qu'un propriétaire
    );

-- Table historique_echange (proposition d'échange entre deux utilisateurs)
CREATE TABLE
    historique_echange (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_emetteur INT NOT NULL, -- Celui qui propose l'échange
        id_recepteur INT NOT NULL, -- Celui qui reçoit la proposition
        id_objet_propose INT NOT NULL, -- L'objet proposé par l'émetteur
        id_objet_demande INT NOT NULL, -- L'objet demandé (appartenant au récepteur)
        id_status INT NOT NULL,
        date_debut DATETIME DEFAULT CURRENT_TIMESTAMP, -- Date de la proposition
        date_fin DATETIME, -- Date d'acceptation/refus/rejet
        FOREIGN KEY (id_emetteur) REFERENCES user (id_user),
        FOREIGN KEY (id_recepteur) REFERENCES user (id_user),
        FOREIGN KEY (id_objet_propose) REFERENCES objets (id_objet),
        FOREIGN KEY (id_objet_demande) REFERENCES objets (id_objet),
        FOREIGN KEY (id_status) REFERENCES status (id)
    );

CREATE VIEW
    v_historique_echange_status AS
SELECT
    he.id AS id_echange,
    he.id_emetteur,
    he.id_recepteur,
    he.id_objet_propose,
    he.id_objet_demande,
    he.id_status,
    he.date_debut,
    he.date_fin,
    s.id AS status_id,
    s.code AS status_code,
    s.libelle AS status_libelle
FROM
    historique_echange he
    JOIN status s ON he.id_status = s.id;

CREATE VIEW
    v_historique_echange_status AS
SELECT
    he.id AS id_echange,
    he.id_emetteur,
    he.id_recepteur,
    he.id_objet_propose,
    he.id_objet_demande,
    he.id_status,
    he.date_debut,
    he.date_fin,
    s.id AS status_id,
    s.code AS status_code,
    s.libelle AS status_libelle
FROM
    historique_echange he
    JOIN status s ON he.id_status = s.id;

CREATE VIEW
    v_current_objet_user as
SELECT
    id_user,
    id_objet
FROM
    proprietaire_objet
WHERE
    date_fin is NULL;


-- ============================================================
-- VUE POUR LES DÉTAILS COMPLETS DES ÉCHANGES
-- ============================================================

DROP VIEW IF EXISTS v_echange_details;

CREATE VIEW v_echange_details AS
SELECT
    -- Identifiants principaux
    he.id AS id_echange,
    he.id_emetteur,
    he.id_recepteur,
    he.id_status,
    
    -- Informations de l'émetteur et du récepteur
    u_emetteur.nom AS nom_emetteur,
    u_recepteur.nom AS nom_recepteur,
    
    -- Objet PROPOSÉ (par l'émetteur)
    obj_propose.id_objet AS id_objet_propose,
    obj_propose.titre AS titre_objet_propose,
    obj_propose.description AS description_objet_propose,
    obj_propose.prix AS prix_objet_propose,
    cat_propose.libelle AS categorie_objet_propose,
    
    -- Objet DEMANDÉ (du récepteur)
    obj_demande.id_objet AS id_objet_demande,
    obj_demande.titre AS titre_objet_demande,
    obj_demande.description AS description_objet_demande,
    obj_demande.prix AS prix_objet_demande,
    cat_demande.libelle AS categorie_objet_demande,
    
    -- Statut et dates
    s.code AS status_code,
    s.libelle AS status_libelle,
    he.date_debut AS date_proposition,
    he.date_fin AS date_reponse

FROM historique_echange he
JOIN user u_emetteur ON he.id_emetteur = u_emetteur.id_user
JOIN user u_recepteur ON he.id_recepteur = u_recepteur.id_user
JOIN objets obj_propose ON he.id_objet_propose = obj_propose.id_objet
JOIN objets obj_demande ON he.id_objet_demande = obj_demande.id_objet
JOIN categorie cat_propose ON obj_propose.id_categorie = cat_propose.id
JOIN categorie cat_demande ON obj_demande.id_categorie = cat_demande.id
JOIN status s ON he.id_status = s.id;

SELECT * FROM v_echange_details WHERE 