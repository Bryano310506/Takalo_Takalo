<?php

namespace app\controllers;

use Flight;
use Throwable;
use app\model\UserModel;
use app\services\Validator;
use app\services\UserService;
use Exception;

class AuthController {

    // Registration methods from Bry
    public function showRegister() {
        Flight::render('auth/register', [
            'values' => ['nom'=>'','prenom'=>'','email'=>'','telephone'=>''],
            'errors' => ['nom'=>'','prenom'=>'','email'=>'','password'=>'','confirm_password'=>'','telephone'=>''],
            'success' => false
        ]);
    }

    public function validateRegisterAjax() {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $req = Flight::request();

            $input = [
                'nom' => $req->data->nom,
                'prenom' => $req->data->prenom,
                'email' => $req->data->email,
                'password' => $req->data->password,
                'confirm_password' => $req->data->confirm_password,
                'telephone' => $req->data->telephone,
                'id_role' => $req->data->id_role,
            ];

            // Essayer de créer le repo pour vérifier si l'email existe déjà
            $model = null;
            try {
                $pdo = Flight::db();
                $model = new UserModel($pdo);
            } catch (Throwable $dbError) {
                // Base de données non disponible, on continue sans vérification d'email
            }

            $res = Validator::validateRegister($input, $model);

            Flight::json([
                'ok' => $res['ok'],
                'errors' => $res['errors'],
                'values' => $res['values'],
            ]);

        } catch (Throwable $e) {
            http_response_code(500);
            Flight::json([
                'ok' => false,
                'errors' => ['_global' => 'Erreur serveur lors de la validation: ' . $e->getMessage()],
                'values' => []
            ]);
        }
    }

    public function postRegister() {
        $pdo  = Flight::db();
        $model = new UserModel($pdo);
        $svc = new UserService($model);

        $req = Flight::request();

        $input = [
            'nom' => $req->data->nom,
            'prenom' => $req->data->prenom,
            'email' => $req->data->email,
            'password' => $req->data->password,
            'confirm_password' => $req->data->confirm_password,
            'telephone' => $req->data->telephone,
            'id_role' => $req->data->id_role,
        ];

        $res = Validator::validateRegister($input);

        if ($res['ok']) {
            $svc->register($res['values'], (string)$input['password']);
            Flight::render('auth/register', [
                'values' => ['nom'=>'','prenom'=>'','email'=>'','telephone'=>''],
                'errors' => ['nom'=>'','prenom'=>'','email'=>'','password'=>'','confirm_password'=>'','telephone'=>''],
                'success' => true
            ]);
            return;
        }

        Flight::render('auth/register', [
            'values' => $res['values'],
            'errors' => $res['errors'],
            'success' => false
        ]);
    }

    // Login methods from Sharon, adapted to Flight
    public function showLogin() {
        Flight::render('admin/login', [
            'title' => 'Connexion - Takalo Takalo'
        ]);
    }

    public function login() {
        $req = Flight::request();
        $data = $req->data;

        $nom = $data['nom'] ?? '';
        $mdp = $data['mdp'] ?? '';

        if (empty($nom) || empty($mdp)) {
            Flight::render('login', [
                'title' => 'Connexion - Takalo Takalo',
                'error' => 'Veuillez remplir tous les champs'
            ]);
            return;
        }

        $pdo = Flight::db();
        $model = new UserModel($pdo);
        $user = $model->authAdmin($nom, $mdp);

        if ($user) {
            // Démarrer la session et stocker les infos utilisateur
            $_SESSION['user'] = $user;
            $_SESSION['logged_in'] = true;

            // Rediriger vers la page de gestion des catégories
            Flight::redirect('/admin/dashboard');
        } else {
            Flight::render('admin/login', [
                'title' => 'Connexion - Takalo Takalo',
                'error' => 'Vous n\'êtes pas autorisé à accéder à cette page',
                'nom' => $nom
            ]);
        }
    }

    public function logout() {
        session_destroy();

        Flight::redirect('/');
    }

    public function isLoggedIn(): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public function getCurrentUser(): ?array {
        if (!$this->isLoggedIn()) {
            return null;
        }

        return $_SESSION['user'] ?? null;
    }

    public function requireAuth() {
        if (!$this->isLoggedIn()) {
            Flight::redirect('/login');
            exit;
        }
    }

    // User verification methods from Bry
    public function verificationUser($email, $password) {
        $pdo = Flight::db();
        $model = new UserModel($pdo);

        $user = $model->getUserByEmail($email);

        if ($user && password_verify($password, $user['mdp'])) {
            return true;
        }
        return false;
    }

    public function getUser($email, $password) {
        $pdo = Flight::db();
        $model = new UserModel($pdo);

        $user = $model->getUserByEmail($email);

        if ($user && password_verify($password, $user['mdp'])) {
            return $user;
        }
        return null;
    }

    // Additional user management from Sharon
    public function insertUser($id_role) {
        $req = Flight::request();
        $data = $req->data;

        $nom = $data['nom'] ?? '';
        $mdp = $data['mdp'] ?? '';

        if (empty($nom) || empty($mdp)) {
            Flight::json(['error' => 'Nom et mot de passe sont requis'], 400);
            return;
        }

        if (empty($id_role)) {
            Flight::json(['error' => 'ID rôle est requis'], 400);
            return;
        }

        try {
            $pdo = Flight::db();
            $model = new UserModel($pdo);

            $roleExists = $pdo->fetchRow("SELECT id FROM role WHERE id = ?", [$id_role]);

            if (!$roleExists) {
                Flight::json(['error' => 'Rôle spécifié n\'existe pas'], 400);
                return;
            }

            $userExists = $pdo->fetchRow("SELECT id_user FROM user WHERE nom = ?", [$nom]);

            if ($userExists) {
                Flight::json(['error' => 'Nom d\'utilisateur déjà utilisé'], 400);
                return;
            }

            $hashedPassword = password_hash($mdp, PASSWORD_DEFAULT);

            $pdo->runQuery("INSERT INTO user (nom, mdp, id_role) VALUES (?, ?, ?)", [$nom, $hashedPassword, $id_role]);

            $userId = $pdo->lastInsertId();

            Flight::json([
                'success' => true,
                'message' => 'Utilisateur créé avec succès',
                'id_user' => $userId
            ], 201);

        } catch (Exception $e) {
            Flight::json(['error' => 'Erreur lors de la création de l\'utilisateur'], 500);
        }
    }

    public function getUserById($id_user) {
        if (empty($id_user)) {
            Flight::json(['error' => 'ID utilisateur est requis'], 400);
            return;
        }

        try {
            $pdo = Flight::db();
            $user = $pdo->fetchRow(
                "SELECT u.id_user, u.nom, u.id_role, r.libelle as role_libelle 
                 FROM user u 
                 JOIN role r ON u.id_role = r.id 
                 WHERE u.id_user = ?", 
                [$id_user]
            );

            if ($user) {
                Flight::json([
                    'success' => true,
                    'user' => $user
                ], 200);
            } else {
                Flight::json(['error' => 'Utilisateur non trouvé'], 404);
            }

        } catch (Exception $e) {
            Flight::json(['error' => 'Erreur lors de la récupération de l\'utilisateur'], 500);
        }
    }
}

?>