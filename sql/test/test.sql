-- ========== INSERTION DES DONNÉES DE TEST ==========

-- Insertion des rôles
INSERT INTO role (libelle) VALUES
('Admin'),
('Utilisateur'),
('Modérateur');

-- Insertion des utilisateurs
INSERT INTO user (nom, mdp, id_role, date_creation) VALUES
('Jean Dupont', 'pass123', 2, '2024-01-15 10:00:00'),
('Marie Martin', 'pass456', 2, '2024-01-20 14:30:00'),
('Pierre Bernard', 'pass789', 2, '2024-02-01 09:15:00'),
('Sophie Leclerc', 'pass012', 2, '2024-02-10 16:45:00'),
('Michel Admin', 'adminpass', 1, '2024-01-01 08:00:00');

-- Insertion des catégories
INSERT INTO categorie (libelle) VALUES
('Électronique'),
('Livres'),
('Vêtements'),
('Meubles'),
('Sports'),
('Jeux vidéo'),
('Musique'),
('Autres');

-- Insertion des objets
INSERT INTO objets (titre, description, id_categorie, prix, date_creation) VALUES
-- Électronique
('iPhone 12', 'iPhone 12 noir, bon état, avec boîte', 1, 500.00, '2024-01-15 10:15:00'),
('MacBook Pro', 'MacBook Pro 13 pouces 2020, très bon état', 1, 1200.00, '2024-01-20 14:45:00'),
('Casque Sony', 'Casque sans fil Sony WH-1000XM4, excellent état', 1, 250.00, '2024-02-01 09:30:00'),

-- Livres
('Harry Potter T1', 'Harry Potter à l\'école des sorciers, édition française', 2, 15.00, '2024-01-16 11:00:00'),
('Le Seigneur des Anneaux', 'Trilogie complète en bon état', 2, 30.00, '2024-02-02 10:00:00'),
('Python pour débutants', 'Livre de programmation Python, 2023', 2, 25.00, '2024-02-05 15:20:00'),

-- Vêtements
('Jean Levis', 'Jean Levis 501 bleu, taille 32, excellent état', 3, 40.00, '2024-01-22 13:00:00'),
('Veste cuir', 'Veste en cuir noir, taille M, très bon état', 3, 80.00, '2024-02-03 11:30:00'),
('Baskets Nike', 'Nike Air Max, taille 42, comme neuf', 3, 60.00, '2024-02-08 14:00:00'),

-- Meubles
('Bureau en bois', 'Bureau en bois massif, 120x60cm, bon état', 4, 150.00, '2024-01-18 09:45:00'),
('Chaise de bureau', 'Chaise de bureau ergonomique, noire, bon état', 4, 50.00, '2024-02-04 10:15:00'),
('Lampe de bureau', 'Lampe LED de bureau, blanc, très bon état', 4, 25.00, '2024-02-06 16:00:00'),

-- Sports
('Vélo VTT', 'Vélo VTT 26 pouces, bon état général', 5, 200.00, '2024-01-25 12:00:00'),
('Skateboard', 'Skateboard complet, bon état', 5, 50.00, '2024-02-07 13:30:00'),
('Raquette tennis', 'Raquette Wilson, bon état', 5, 35.00, '2024-02-09 11:00:00');

-- Insertion des photos
INSERT INTO photos (id_objet, nom, date_ajout) VALUES
(1, 'iphone12_front.jpg', '2024-01-15 10:20:00'),
(1, 'iphone12_back.jpg', '2024-01-15 10:20:00'),
(2, 'macbook_closed.jpg', '2024-01-20 14:50:00'),
(3, 'casque_sony.jpg', '2024-02-01 09:35:00'),
(7, 'jean_levis.jpg', '2024-01-22 13:05:00'),
(8, 'veste_cuir.jpg', '2024-02-03 11:35:00'),
(13, 'velo_vtt.jpg', '2024-01-25 12:05:00');

-- Insertion des statuts
INSERT INTO status (code, libelle) VALUES
('PENDING', 'En attente'),
('ACCEPTED', 'Accepté'),
('REFUSED', 'Refusé'),
('REJECTED', 'Rejeté'),
('COMPLETED', 'Complété');

-- Insertion des propriétaires d'objets
INSERT INTO proprietaire_objet (id_user, id_objet, date_debut) VALUES
(1, 1, '2024-01-15 10:15:00'),
(2, 2, '2024-01-20 14:45:00'),
(3, 3, '2024-02-01 09:30:00'),
(1, 4, '2024-01-16 11:00:00'),
(2, 5, '2024-02-02 10:00:00'),
(4, 6, '2024-02-05 15:20:00'),
(1, 7, '2024-01-22 13:00:00'),
(3, 8, '2024-02-03 11:30:00'),
(4, 9, '2024-02-08 14:00:00'),
(2, 10, '2024-01-18 09:45:00'),
(1, 11, '2024-02-04 10:15:00'),
(3, 12, '2024-02-06 16:00:00'),
(2, 13, '2024-01-25 12:00:00'),
(4, 14, '2024-02-07 13:30:00'),
(1, 15, '2024-02-09 11:00:00');

-- Insertion de l'historique des échanges
INSERT INTO historique_echange (id_emetteur, id_recepteur, id_objet_propose, id_objet_demande, id_status, date_debut, date_fin) VALUES
(1, 2, 1, 2, 2, '2024-02-10 10:00:00', '2024-02-11 14:30:00'),
(3, 4, 3, 9, 1, '2024-02-11 09:15:00', NULL),
(2, 1, 10, 7, 3, '2024-02-09 16:00:00', '2024-02-09 17:00:00'),
(1, 3, 4, 8, 5, '2024-01-30 11:00:00', '2024-02-01 15:30:00'),
(4, 2, 14, 13, 4, '2024-02-08 10:00:00', '2024-02-08 11:00:00'),
(3, 4, 12, 15, 1, '2024-02-11 13:45:00', NULL),
(1, 3, 11, 3, 2, '2024-02-10 14:00:00', '2024-02-11 10:00:00');