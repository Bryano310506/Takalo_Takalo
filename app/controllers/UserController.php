<?php

namespace app\controllers;

use Flight;
use app\model\UserModel;
use app\model\CategorieModel;
use app\model\ObjetModel;
use Exception;

class UserController {

    /**
     * Affiche la page de gestion des utilisateurs (admin)
     */
    public function showGestionUsers() {
        try {
            $pdo = Flight::db();
            $userModel = new UserModel($pdo);
            $users = $userModel->getAll();
            $roles = $userModel->getAllRoles();
            Flight::render('admin/users/gestion', [
                'users' => $users,
                'roles' => $roles,
                'title' => 'Gestion des utilisateurs'
            ]);
        } catch (Exception $e) {
            error_log("Erreur gestion utilisateurs: " . $e->getMessage());
            Flight::render('admin/users/gestion', [
                'users' => [],
                'roles' => [],
                'title' => 'Gestion des utilisateurs',
                'error' => 'Une erreur est survenue lors du chargement des utilisateurs'
            ]);
        }
    }

    /**
     * Affiche le formulaire d'ajout d'utilisateur
     */
    public function showAjoutUser() {
        try {
            $pdo = Flight::db();
            $userModel = new UserModel($pdo);
            $roles = $userModel->getAllRoles();
            Flight::render('admin/users/ajout', [
                'roles' => $roles,
                'title' => 'Ajouter un utilisateur'
            ]);
        } catch (Exception $e) {
            error_log("Erreur affichage ajout utilisateur: " . $e->getMessage());
            Flight::render('admin/users/ajout', [
                'roles' => [],
                'title' => 'Ajouter un utilisateur',
                'error' => 'Une erreur est survenue lors du chargement des rôles'
            ]);
        }
    }

    /**
     * Affiche le formulaire de modification d'utilisateur
     */
    public function showModifierUser($id) {
        try {
            $pdo = Flight::db();
            $userModel = new UserModel($pdo);
            $user = $userModel->getUserById($id);
            $roles = $userModel->getAllRoles();

            if (!$user) {
                Flight::halt(404, 'Utilisateur non trouvé');
                return;
            }

            Flight::render('admin/users/modifier', [
                'user' => $user,
                'roles' => $roles,
                'title' => 'Modifier un utilisateur'
            ]);
        } catch (Exception $e) {
            error_log("Erreur modification utilisateur: " . $e->getMessage());
            Flight::halt(500, 'Erreur lors du chargement de l\'utilisateur');
        }
    }

    /**
     * Insère un nouvel utilisateur
     */
    public function insertUser() {
        $nom = $_POST['nom'] ?? '';
        $mdp = $_POST['mdp'] ?? '';
        $confirmation = $_POST['confirmation'] ?? '';
        $idRole = $_POST['id_role'] ?? '';

        if (empty(trim($nom)) || empty($mdp) || empty($idRole)) {
            $_SESSION['error'] = 'Le nom, le mot de passe et le rôle sont obligatoires';
            Flight::redirect('/admin/users/ajout');
            return;
        }

        if ($mdp !== $confirmation) {
            $_SESSION['error'] = 'Les mots de passe ne correspondent pas';
            Flight::redirect('/admin/users/ajout');
            return;
        }

        if (strlen($mdp) < 6) {
            $_SESSION['error'] = 'Le mot de passe doit contenir au moins 6 caractères';
            Flight::redirect('/admin/users/ajout');
            return;
        }

        try {
            $pdo = Flight::db();
            $userModel = new UserModel($pdo);
            if ($userModel->existsByName($nom)) {
                $_SESSION['error'] = 'Cet utilisateur existe déjà';
                Flight::redirect('/admin/users/ajout');
                return;
            }
            $id = $userModel->createUser($nom, '', '', $mdp, '', $idRole);
            if ($id) {
                $_SESSION['success'] = 'Utilisateur ajouté avec succès';
            } else {
                $_SESSION['error'] = 'Erreur lors de l\'ajout';
            }
        } catch (Exception $e) {
            error_log("Erreur insertion utilisateur: " . $e->getMessage());
            $_SESSION['error'] = 'Une erreur est survenue lors de l\'ajout';
        }

        Flight::redirect('/admin/users');
    }

    /**
     * Met à jour un utilisateur
     */
    public function updateUser() {
        $id = $_POST['id'] ?? '';
        $nom = $_POST['nom'] ?? '';
        $idRole = $_POST['id_role'] ?? '';
        $mdp = $_POST['mdp'] ?? '';
        $confirmation = $_POST['confirmation'] ?? '';

        if (empty($id) || empty(trim($nom)) || empty($idRole)) {
            $_SESSION['error'] = 'L\'ID, le nom et le rôle sont obligatoires';
            Flight::redirect('/admin/users/modifier/' . $id);
            return;
        }

        if (!empty($mdp) && $mdp !== $confirmation) {
            $_SESSION['error'] = 'Les mots de passe ne correspondent pas';
            Flight::redirect('/admin/users/modifier/' . $id);
            return;
        }

        if (!empty($mdp) && strlen($mdp) < 6) {
            $_SESSION['error'] = 'Le mot de passe doit contenir au moins 6 caractères';
            Flight::redirect('/admin/users/modifier/' . $id);
            return;
        }

        try {
            $pdo = Flight::db();
            $userModel = new UserModel($pdo);
            $currentUser = $userModel->getUserById($id);
            if (!$currentUser) {
                $_SESSION['error'] = 'Utilisateur non trouvé';
                Flight::redirect('/admin/users');
                return;
            }
            $prenom = $currentUser['prenom'] ?? '';
            $email = $currentUser['email'] ?? '';
            $telephone = $currentUser['telephone'] ?? '';
            $mdpToUpdate = !empty($mdp) ? $mdp : null;
            $success = $userModel->updateUser($id, $nom, $prenom, $email, $telephone, $idRole, $mdpToUpdate);
            if ($success) {
                $_SESSION['success'] = 'Utilisateur mis à jour avec succès';
            } else {
                $_SESSION['error'] = 'Erreur lors de la mise à jour ou nom déjà utilisé';
            }
        } catch (Exception $e) {
            error_log("Erreur mise à jour utilisateur: " . $e->getMessage());
            $_SESSION['error'] = 'Une erreur est survenue lors de la mise à jour';
        }

        Flight::redirect('/admin/users');
    }

    /**
     * Supprime un utilisateur
     */
    public function deleteUser($id) {
        try {
            $pdo = Flight::db();
            $userModel = new UserModel($pdo);
            $success = $userModel->deleteUser($id);
            if ($success) {
                $_SESSION['success'] = 'Utilisateur supprimé avec succès';
            } else {
                $_SESSION['error'] = 'Impossible de supprimer cet utilisateur (des objets y sont peut-être associés)';
            }
        } catch (Exception $e) {
            error_log("Erreur suppression utilisateur: " . $e->getMessage());
            $_SESSION['error'] = 'Une erreur est survenue lors de la suppression';
        }

        Flight::redirect('/admin/users');
    }

    /**
     * Affiche le tableau de bord avec les statistiques
     */
    public function showDashboard() {
        try {
            $pdo = Flight::db();
            $userModel = new UserModel($pdo);
            $categorieModel = new CategorieModel($pdo);
            $objetModel = new ObjetModel($pdo);

            // Statistiques utilisateurs
            $userStats = $userModel->getStatistics();

            // Statistiques catégories
            $categories = $categorieModel->getAll();
            $categorieStats = [
                'total_categories' => count($categories),
                'categories' => $categories
            ];

            // Statistiques objets
            $objets = $objetModel->getAllObjets();
            $objetStats = [
                'total_objets' => count($objets),
                'objets_by_categorie' => []
            ];

            // Regrouper les objets par catégorie
            foreach ($objets as $objet) {
                $categorieLibelle = $objet['categorie_libelle'] ?: 'Non catégorisé';
                if (!isset($objetStats['objets_by_categorie'][$categorieLibelle])) {
                    $objetStats['objets_by_categorie'][$categorieLibelle] = 0;
                }
                $objetStats['objets_by_categorie'][$categorieLibelle]++;
            }

            // Statistiques générales
            $generalStats = [
                'total_items' => $userStats['total_users'] + $categorieStats['total_categories'] + $objetStats['total_objets'],
                'date' => date('d/m/Y H:i:s'),
                'year' => date('Y')
            ];

            Flight::render('admin/dashboard', [
                'userStats' => $userStats,
                'categorieStats' => $categorieStats,
                'objetStats' => $objetStats,
                'generalStats' => $generalStats,
                'title' => 'Tableau de bord - Statistiques'
            ]);

        } catch (Exception $e) {
            error_log("Erreur dashboard: " . $e->getMessage());
            Flight::render('admin/dashboard', [
                'userStats' => [],
                'categorieStats' => [],
                'objetStats' => [],
                'generalStats' => [],
                'title' => 'Tableau de bord - Statistiques',
                'error' => 'Une erreur est survenue lors du chargement des statistiques'
            ]);
        }
    }

    /**
     * API: Récupère tous les utilisateurs au format JSON
     */
    public function getAllUsers() {
        try {
            $pdo = Flight::db();
            $userModel = new UserModel($pdo);
            $users = $userModel->getAll();
            Flight::json(['success' => true, 'data' => $users]);
        } catch (Exception $e) {
            error_log("Erreur API utilisateurs: " . $e->getMessage());
            Flight::json(['success' => false, 'error' => 'Erreur lors de la récupération des utilisateurs'], 500);
        }
    }

    /**
     * API: Récupère les statistiques au format JSON
     */
    public function getStatistics() {
        try {
            $pdo = Flight::db();
            $userModel = new UserModel($pdo);
            $stats = $userModel->getStatistics();
            Flight::json(['success' => true, 'data' => $stats]);
        } catch (Exception $e) {
            error_log("Erreur API statistiques: " . $e->getMessage());
            Flight::json(['success' => false, 'error' => 'Erreur lors de la récupération des statistiques'], 500);
        }
    }
}

?>