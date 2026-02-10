-- Donnees de la base Takalo Takalo

-- Insertion des roles
INSERT INTO role (code, libelle) VALUES
('ADMIN', 'Administrateur'), 
('USER', 'Utilisateur');

-- Insertion des utilisateurs
INSERT INTO user (nom, mdp, id_role) VALUES
('Admin', 'password123', 1),
('User1', 'password123', 2),
('User2', 'password123', 2);

-- Insertion des categories
INSERT INTO categorie (libelle) VALUES
('Electronique'),
('Meubles'),
('Vêtements');

-- Insertion des objets
INSERT INTO objets (titre, description, id_categorie, prix) VALUES
('Téléphone', 'Un smartphone en bon état', 1, 150.00),
('Table', 'Une table en bois massif', 2, 200.00),
('Veste', 'Une veste en cuir', 3, 80.00);

-- Insertion des photos
INSERT INTO photos (id_objet, nom) VALUES
(1, 'telephone.jpg'),
(2, 'table.jpg'),
(3, 'veste.jpg');

-- Insertion des status
INSERT INTO status (libelle) VALUES
('Disponible'),
('Echange'),
('En attente');

-- Insertion des proprietaire_objet
INSERT INTO proprietaire_objet (id_user, id_objet, date_echange) VALUES
(1, 1, '2026-02-10 10:00:00'),
(2, 2, '2026-02-11 15:30:00'),
(3, 3, '2026-02-12 20:45:00');

