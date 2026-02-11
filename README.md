# Architecture du Projet - Flight PHP

## Vue d'ensemble de l'architecture

Ce projet utilise une architecture en couches avec le **Repository Pattern** pour séparer les responsabilités et maintenir un code propre et maintenable.

```
View → Controller → Repository → Model
```

---

## 🎯 Flux de données

### 1️⃣ **View (Vue)** - Interface utilisateur

**Rôle :** Afficher les données et capturer les interactions utilisateur

**Localisation :** `/views/`

**Exemple :** `views/users/index.php`

```php
<!-- La vue affiche les données reçues du controller -->
<h1>Liste des utilisateurs</h1>
<ul>
    <?php foreach ($users as $user): ?>
        <li><?= htmlspecialchars($user->name) ?> - <?= htmlspecialchars($user->email) ?></li>
    <?php endforeach; ?>
</ul>
```

**Responsabilités :**
- Afficher les données (HTML)
- Formulaires pour la saisie utilisateur
- ❌ Ne contient AUCUNE logique métier
- ❌ N'accède PAS directement à la base de données

---

### 2️⃣ **Controller (Contrôleur)** - Chef d'orchestre

**Rôle :** Gérer les requêtes HTTP et coordonner entre la vue et le repository

**Localisation :** `/controllers/`

**Exemple :** `controllers/UserController.php`

```php
<?php

class UserController {
    private $userRepository;
    
    public function __construct() {
        $this->userRepository = new UserRepository();
    }
    
    /**
     * Affiche la liste de tous les utilisateurs
     */
    public function index() {
        // 1. Demande les données au repository
        $users = $this->userRepository->findAll();
        
        // 2. Passe les données à la vue
        Flight::render('users/index', ['users' => $users]);
    }
    
    /**
     * Affiche un utilisateur spécifique
     */
    public function show($id) {
        // 1. Récupère l'utilisateur via le repository
        $user = $this->userRepository->findById($id);
        
        // 2. Vérifie si l'utilisateur existe
        if (!$user) {
            Flight::notFound();
            return;
        }
        
        // 3. Affiche la vue
        Flight::render('users/show', ['user' => $user]);
    }
    
    /**
     * Crée un nouvel utilisateur
     */
    public function store() {
        // 1. Récupère les données du formulaire
        $data = [
            'name' => Flight::request()->data->name,
            'email' => Flight::request()->data->email
        ];
        
        // 2. Valide les données (logique métier)
        if (empty($data['name']) || empty($data['email'])) {
            Flight::json(['error' => 'Champs requis'], 400);
            return;
        }
        
        // 3. Demande au repository de créer l'utilisateur
        $user = $this->userRepository->create($data);
        
        // 4. Redirige ou retourne une réponse
        Flight::redirect('/users');
    }
}
```

**Responsabilités :**
- ✅ Recevoir les requêtes HTTP
- ✅ Valider les données d'entrée
- ✅ Appeler le repository pour les opérations de données
- ✅ Préparer les données pour la vue
- ✅ Gérer les redirections et réponses HTTP
- ❌ Ne contient PAS de requêtes SQL
- ❌ Ne manipule PAS directement la base de données

---

### 3️⃣ **Repository** - Couche d'accès aux données

**Rôle :** Abstraire l'accès aux données et isoler la logique de persistance

**Localisation :** `/repositories/`

**Exemple :** `repositories/UserRepository.php`

```php
<?php

class UserRepository {
    private $db;
    
    public function __construct() {
        $this->db = Flight::db(); // Connexion à la base de données
    }
    
    /**
     * Récupère tous les utilisateurs
     */
    public function findAll() {
        $stmt = $this->db->query("SELECT * FROM users");
        $usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Transforme les données en objets Model
        $users = [];
        foreach ($usersData as $data) {
            $users[] = new User($data);
        }
        
        return $users;
    }
    
    /**
     * Récupère un utilisateur par son ID
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ? new User($data) : null;
    }
    
    /**
     * Crée un nouvel utilisateur
     */
    public function create($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, created_at) VALUES (?, ?, NOW())"
        );
        $stmt->execute([$data['name'], $data['email']]);
        
        // Récupère l'utilisateur créé
        $id = $this->db->lastInsertId();
        return $this->findById($id);
    }
    
    /**
     * Met à jour un utilisateur
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare(
            "UPDATE users SET name = ?, email = ? WHERE id = ?"
        );
        $stmt->execute([$data['name'], $data['email'], $id]);
        
        return $this->findById($id);
    }
    
    /**
     * Supprime un utilisateur
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Recherche des utilisateurs par email
     */
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ? new User($data) : null;
    }
}
```

**Responsabilités :**
- ✅ Contient TOUTES les requêtes SQL
- ✅ Gère la persistance des données
- ✅ Transforme les données en objets Model
- ✅ Fournit une interface claire pour accéder aux données
- ❌ Ne gère PAS la logique métier (validation, calculs)
- ❌ Ne gère PAS les réponses HTTP

---

### 4️⃣ **Model (Modèle)** - Représentation des données

**Rôle :** Représenter une entité métier avec ses propriétés et comportements

**Localisation :** `/models/`

**Exemple :** `models/User.php`

```php
<?php

class User {
    private $id;
    private $name;
    private $email;
    private $created_at;
    
    /**
     * Constructeur - hydrate l'objet avec les données
     */
    public function __construct($data = []) {
        if (isset($data['id'])) $this->id = $data['id'];
        if (isset($data['name'])) $this->name = $data['name'];
        if (isset($data['email'])) $this->email = $data['email'];
        if (isset($data['created_at'])) $this->created_at = $data['created_at'];
    }
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function getCreatedAt() {
        return $this->created_at;
    }
    
    // Setters
    public function setName($name) {
        $this->name = $name;
    }
    
    public function setEmail($email) {
        $this->email = $email;
    }
    
    /**
     * Méthodes métier - logique spécifique à l'entité
     */
    public function getFullName() {
        return ucfirst($this->name);
    }
    
    public function isEmailValid() {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Convertit l'objet en tableau (utile pour JSON)
     */
    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at
        ];
    }
}
```

**Responsabilités :**
- ✅ Représente une entité métier (User, Product, Order, etc.)
- ✅ Encapsule les propriétés avec getters/setters
- ✅ Contient des méthodes métier simples liées à l'entité
- ✅ Peut valider ses propres données
- ❌ Ne contient PAS de requêtes SQL
- ❌ Ne gère PAS la persistance en base de données

---

## 🔄 Exemple complet de flux

### Scénario : Afficher la liste des utilisateurs

```
1. L'utilisateur visite : /users

2. Route (index.php)
   Flight::route('GET /users', function() {
       $controller = new UserController();
       $controller->index();
   });

3. Controller (UserController.php)
   → Appelle le repository : $this->userRepository->findAll()

4. Repository (UserRepository.php)
   → Exécute la requête SQL : SELECT * FROM users
   → Transforme les résultats en objets User
   → Retourne un tableau de User au controller

5. Controller
   → Reçoit les objets User
   → Passe les données à la vue : Flight::render('users/index', ['users' => $users])

6. View (views/users/index.php)
   → Affiche les données dans du HTML
   → L'utilisateur voit la page
```

---

## 📊 Diagramme de flux

```
┌─────────────────────────────────────────────────────────────┐
│                    Requête utilisateur                       │
│                     GET /users/5                            │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│                    ROUTE (index.php)                        │
│  Flight::route('GET /users/@id', ...)                       │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│                  CONTROLLER                                 │
│  UserController::show($id)                                  │
│                                                             │
│  1. Reçoit la requête                                       │
│  2. Appelle repository→findById($id)      ────┐             │
│  3. Prépare les données pour la vue           │             │
│  4. Rend la vue                               │             │
└───────────────────────────────────────────────┼─────────────┘
                                                │
                                                ▼
                              ┌─────────────────────────────────┐
                              │         REPOSITORY              │
                              │  UserRepository::findById($id)  │
                              │                                 │
                              │  1. Prépare requête SQL    ──┐  │
                              │  2. Exécute la requête       │  │
                              │  3. Crée objet Model         │  │
                              │  4. Retourne au Controller   │  │
                              └──────────────────────────────┼──┘
                                                             │
                                                             ▼
                                           ┌──────────────────────────┐
                                           │        MODEL             │
                                           │  new User($data)         │
                                           │                          │
                                           │  - Propriétés            │
                                           │  - Getters/Setters       │
                                           │  - Méthodes métier       │
                                           └──────────────────────────┘
                                                             │
                                                             │ (retour)
                         ┌───────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│                      VIEW                                   │
│  views/users/show.php                                       │
│                                                             │
│  <h1><?= $user->getName() ?></h1>                           │
│  <p><?= $user->getEmail() ?></p>                            │
└─────────────────────────────────────────────────────────────┘
                         │
                         ▼
                  Réponse HTML
```

---

## 🎯 Avantages de cette architecture

### Séparation des responsabilités
- Chaque couche a un rôle précis
- Code plus facile à comprendre et maintenir

### Testabilité
- Chaque composant peut être testé indépendamment
- Mock des repositories pour tester les controllers

### Réutilisabilité
- Les repositories peuvent être utilisés par plusieurs controllers
- Les models sont indépendants de la persistance

### Flexibilité
- Changement de base de données ? → Modifier uniquement les repositories
- Changement d'interface ? → Modifier uniquement les vues
- Nouvelle logique métier ? → Modifier le controller

---

## 📁 Structure de dossiers recommandée

```
project/
│
├── index.php              # Point d'entrée + routes
├── .gitignore
├── composer.json
│
├── controllers/
│   ├── UserController.php
│   ├── ProductController.php
│   └── OrderController.php
│
├── repositories/
│   ├── UserRepository.php
│   ├── ProductRepository.php
│   └── OrderRepository.php
│
├── models/
│   ├── User.php
│   ├── Product.php
│   └── Order.php
│
├── views/
│   ├── users/
│   │   ├── index.php
│   │   ├── show.php
│   │   └── form.php
│   ├── products/
│   └── layout.php
│
├── config/
│   └── database.php
│
└── vendor/                # Dépendances Composer (ignoré par git)
```

---

## 💡 Bonnes pratiques

1. **Un repository par modèle** - UserRepository pour User, ProductRepository pour Product
2. **Controllers légers** - Déléguer la logique aux repositories et models
3. **Pas de SQL dans les controllers** - Toujours passer par le repository
4. **Vues pures** - Pas de logique métier dans les vues
5. **Nommage cohérent** - `findAll()`, `findById()`, `create()`, `update()`, `delete()`

---

## 🚀 Pour aller plus loin

- Ajouter une couche **Service** entre Controller et Repository pour la logique métier complexe
- Implémenter des **Interfaces** pour les repositories (meilleure testabilité)
- Utiliser un **ORM** comme Eloquent ou Doctrine
- Ajouter de la **validation** avec des classes dédiées