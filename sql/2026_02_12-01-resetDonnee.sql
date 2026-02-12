-- Script de suppression et réinsertion de données pour les tables : categorie, objets, proprietaire_objet, historique_echange
-- Basé sur les schémas (images) dans le dossier img

USE takalo_takalo;

-- Suppression de toutes les données dans l'ordre pour respecter les contraintes de clés étrangères
DELETE FROM historique_echange;
DELETE FROM proprietaire_objet;
DELETE FROM photos;  -- Bien que non mentionnée, nécessaire car référence objets
DELETE FROM objets;
DELETE FROM categorie;

-- Réinsertion des données

-- Insertion des catégories
INSERT INTO categorie (libelle) VALUES
('Electronique'),
('Meubles'),
('Vêtements');

-- Insertion des objets (un par image dans img)
INSERT INTO objets (titre, description, id_categorie, prix) VALUES
('Produit 1 Variant', 'Description du produit 1 variant', 1, 100.00),
('Produit 2', 'Description du produit 2', 1, 150.00),
('Produit 3', 'Description du produit 3', 1, 200.00),
('Produit 4', 'Description du produit 4', 1, 50.00),
('Produit 5', 'Description du produit 5', 2, 75.00),
('Produit 7', 'Description du produit 7', 2, 120.00),
('Produit 8', 'Description du produit 8', 2, 90.00),
('Produit 10', 'Description du produit 10', 3, 180.00),
('Produit 11', 'Description du produit 11', 3, 250.00),
('Produit 12', 'Description du produit 12', 3, 300.00);

-- Insertion des photos (utilisant les noms des fichiers dans img)
INSERT INTO photos (id_objet, nom) VALUES
(1, 'product-1-variant.webp'),
(2, 'product-2.webp'),
(3, 'product-3.webp'),
(4, 'product-4.webp'),
(5, 'product-5.webp'),
(6, 'product-7.webp'),
(7, 'product-8.webp'),
(8, 'product-10.webp'),
(9, 'product-11.webp'),
(10, 'product-12.webp');

-- Insertion des propriétaires d'objets (assignation à des utilisateurs existants)
INSERT INTO proprietaire_objet (id_user, id_objet, date_debut) VALUES
(2, 1, '2026-02-13'),
(2, 2, '2026-02-13'),
(3, 3, '2026-02-13'),
(1, 4, '2026-02-13'),
(2, 5, '2026-02-13'),
(3, 6, '2026-02-13'),
(1, 7, '2026-02-13'),
(2, 8, '2026-02-13'),
(3, 9, '2026-02-13'),
(1, 10, '2026-02-13');

-- Insertion des historiques d'échange (exemples d'échanges)
INSERT INTO historique_echange (id_objet1, id_objet2, id_status, date_debut, date_fin) VALUES
(1, 2, 2, '2026-02-13', '2026-02-14'),
(3, 4, 2, '2026-02-13', NULL),
(5, 6, 3, '2026-02-13', NULL),
(7, 8, 2, '2026-02-13', '2026-02-15'),
(9, 10, 1, '2026-02-13', NULL);