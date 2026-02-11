-- Migration pour ajouter les colonnes prenom, email et telephone à la table users
ALTER TABLE user
ADD COLUMN prenom VARCHAR(255) NOT NULL,
ADD COLUMN email VARCHAR(255) NOT NULL UNIQUE,
ADD COLUMN telephone VARCHAR(20);

-- Migration pour ajouter la table proprietaire_objet
ALTER TABLE proprietaire_objet
ADD COLUMN date_debut DATE,
ADD COLUMN date_fin DATE;

-- Migration pour ajouter la table historique_echange
ALTER TABLE historique_echange 
ADD COLUMN date_debut DATE,
ADD COLUMN date_fin DATE;