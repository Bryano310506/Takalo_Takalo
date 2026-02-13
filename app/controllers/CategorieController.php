<?php

namespace app\controllers;

use Flight;
use app\model\CategorieModel;

class CategorieController {

    /**
     * Affiche la liste des catégories
     */
    public function showListeCategories() {
        try {
            $pdo = Flight::db();
            $categorieModel = new CategorieModel($pdo);
            $categories = $categorieModel->getAll();
            Flight::render('categories/liste', [
                'categories' => $categories,
                'title' => 'Liste des catégories'
            ]);
        } catch (Exception $e) {
            error_log("Erreur affichage catégories: " . $e->getMessage());
            Flight::render('categories/liste', [
                'categories' => [],
                'title' => 'Liste des catégories',
                'error' => 'Une erreur est survenue lors du chargement des catégories'
            ]);
        }
    }

    /**
     * Affiche la page de gestion des catégories (admin)
     */
    public function showGestionCategories() {
        try {
            $pdo = Flight::db();
            $categorieModel = new CategorieModel($pdo);
            $categories = $categorieModel->getAll();
            Flight::render('admin/categories/gestion', [
                'categories' => $categories,
                'title' => 'Gestion des catégories'
            ]);
        } catch (Exception $e) {
            error_log("Erreur gestion catégories: " . $e->getMessage());
            Flight::render('admin/categories/gestion', [
                'categories' => [],
                'title' => 'Gestion des catégories',
                'error' => 'Une erreur est survenue lors du chargement des catégories'
            ]);
        }
    }

    /**
     * Affiche le formulaire d'ajout de catégorie
     */
    public function showAjoutCategorie() {
        Flight::render('admin/categories/ajout', [
            'title' => 'Ajouter une catégorie'
        ]);
    }

    /**
     * Affiche le formulaire de modification de catégorie
     */
    public function showModifierCategorie($id) {
        try {
            $pdo = Flight::db();
            $categorieModel = new CategorieModel($pdo);
            $categorie = $categorieModel->getById($id);
            if (!$categorie) {
                Flight::halt(404, 'Catégorie non trouvée');
                return;
            }

            Flight::render('admin/categories/modifier', [
                'categorie' => $categorie,
                'title' => 'Modifier une catégorie'
            ]);
        } catch (Exception $e) {
            error_log("Erreur modification catégorie: " . $e->getMessage());
            Flight::halt(500, 'Erreur lors du chargement de la catégorie');
        }
    }

    /**
     * Insère une nouvelle catégorie
     */
    public function insertCategorie() {
        $libelle = $_POST['libelle'] ?? '';

        if (empty(trim($libelle))) {
            $_SESSION['error'] = 'Le libellé est obligatoire';
            Flight::redirect('/admin/categories/ajout');
            return;
        }

        try {
            $pdo = Flight::db();
            $categorieModel = new CategorieModel($pdo);
            $id = $categorieModel->create($libelle);
            if ($id) {
                $_SESSION['success'] = 'Catégorie ajoutée avec succès';
            } else {
                $_SESSION['error'] = 'Cette catégorie existe déjà ou erreur lors de l\'ajout';
            }
        } catch (Exception $e) {
            error_log("Erreur insertion catégorie: " . $e->getMessage());
            $_SESSION['error'] = 'Une erreur est survenue lors de l\'ajout';
        }

        Flight::redirect('/admin/categories');
    }

    /**
     * Met à jour une catégorie
     */
    public function updateCategorie() {
        $id = $_POST['id'] ?? '';
        $libelle = $_POST['libelle'] ?? '';

        if (empty($id) || empty(trim($libelle))) {
            $_SESSION['error'] = 'Tous les champs sont obligatoires';
            Flight::redirect('/admin/categories/modifier/' . $id);
            return;
        }

        try {
            $pdo = Flight::db();
            $categorieModel = new CategorieModel($pdo);
            $success = $categorieModel->update($id, $libelle);
            if ($success) {
                $_SESSION['success'] = 'Catégorie mise à jour avec succès';
            } else {
                $_SESSION['error'] = 'Erreur lors de la mise à jour';
            }
        } catch (Exception $e) {
            error_log("Erreur mise à jour catégorie: " . $e->getMessage());
            $_SESSION['error'] = 'Une erreur est survenue lors de la mise à jour';
        }

        Flight::redirect('/admin/categories');
    }

    /**
     * Supprime une catégorie
     */
    public function deleteCategorie($id) {
        try {
            $pdo = Flight::db();
            $categorieModel = new CategorieModel($pdo);
            $success = $categorieModel->delete($id);
            if ($success) {
                $_SESSION['success'] = 'Catégorie supprimée avec succès';
            } else {
                $_SESSION['error'] = 'Impossible de supprimer cette catégorie (des objets y sont peut-être associés)';
            }
        } catch (Exception $e) {
            error_log("Erreur suppression catégorie: " . $e->getMessage());
            $_SESSION['error'] = 'Une erreur est survenue lors de la suppression';
        }

        Flight::redirect('/admin/categories');
    }

    /**
     * API: Récupère toutes les catégories au format JSON
     */
    public function getAllCategories() {
        try {
            $pdo = Flight::db();
            $categorieModel = new CategorieModel($pdo);
            $categories = $categorieModel->getAll();
            Flight::json(['success' => true, 'data' => $categories]);
        } catch (Exception $e) {
            error_log("Erreur API catégories: " . $e->getMessage());
            Flight::json(['success' => false, 'error' => 'Erreur lors de la récupération des catégories'], 500);
        }
    }

    /**
     * API: Récupère une catégorie par son ID au format JSON
     */
    public function getCategorie($id) {
        try {
            $pdo = Flight::db();
            $categorieModel = new CategorieModel($pdo);
            $categorie = $categorieModel->getById($id);
            if ($categorie) {
                Flight::json(['success' => true, 'data' => $categorie]);
            } else {
                Flight::json(['success' => false, 'error' => 'Catégorie non trouvée'], 404);
            }
        } catch (Exception $e) {
            error_log("Erreur API catégorie: " . $e->getMessage());
            Flight::json(['success' => false, 'error' => 'Erreur lors de la récupération de la catégorie'], 500);
        }
    }
}

?>