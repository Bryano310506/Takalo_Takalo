<?php

namespace app\controllers;

use Flight;
use app\services\ObjetService;
use app\model\ObjetModel;
use app\model\CategorieModel;
use Exception;

class ObjetController {

    // CRUD methods from Bry (service-based)
    function getAllObjetsByuser($id_user) {
        $objService = new ObjetService(Flight::db());
        return $objService->getObjetByUserId($id_user);
    }

    function getObjetById($id) {
        $objService = new ObjetService(Flight::db());
        return $objService->getObjetById($id);
    }

    function insertObjet($id_user, $titre, $description, $id_categorie, $prix, $files) {
        $objService = new ObjetService(Flight::db());
        $photos = $objService->uploadPhotos($files);
        return $objService->insertObjet($id_user, $titre, $description, $id_categorie, $prix, $photos);
    }

    function deleteObjet($id) {
        $objService = new ObjetService(Flight::db());
        return $objService->deleteObjet($id);
    }

    function updateObjet($id, $titre, $description, $id_categorie, $prix, $files) {
        $objService = new ObjetService(Flight::db());
        $photos = null;
        if ($files) {
            $photos = $objService->uploadPhotos($files);
        }
        return $objService->updateObjet($id, $titre, $description, $id_categorie, $prix, $photos);
    }

    // View methods from Sharon, adapted to Flight and using models
    public function showListeObjets() {
        try {
            $pdo = Flight::db();
            $objetModel = new ObjetModel($pdo);
            $objets = $objetModel->getAllObjets();
            Flight::render('objets/liste', [
                'objets' => $objets,
                'title' => 'Liste des objets'
            ]);
        } catch (Exception $e) {
            error_log("Erreur affichage objets: " . $e->getMessage());
            Flight::render('objets/liste', [
                'objets' => [],
                'title' => 'Liste des objets',
                'error' => 'Une erreur est survenue lors du chargement des objets'
            ]);
        }
    }

    public function showGestionObjets() {
        try {
            $pdo = Flight::db();
            $objetModel = new ObjetModel($pdo);
            $categorieModel = new CategorieModel($pdo);
            $objets = $objetModel->getAllObjets();
            $categories = $categorieModel->getAll();
            Flight::render('admin/objets/gestion', [
                'objets' => $objets,
                'categories' => $categories,
                'title' => 'Gestion des objets'
            ]);
        } catch (Exception $e) {
            error_log("Erreur gestion objets: " . $e->getMessage());
            Flight::render('admin/objets/gestion', [
                'objets' => [],
                'categories' => [],
                'title' => 'Gestion des objets',
                'error' => 'Une erreur est survenue lors du chargement des objets'
            ]);
        }
    }

    public function showAjoutObjet() {
        try {
            $pdo = Flight::db();
            $categorieModel = new CategorieModel($pdo);
            $categories = $categorieModel->getAll();
            Flight::render('admin/objets/ajout', [
                'categories' => $categories,
                'title' => 'Ajouter un objet'
            ]);
        } catch (Exception $e) {
            error_log("Erreur affichage ajout objet: " . $e->getMessage());
            Flight::render('admin/objets/ajout', [
                'categories' => [],
                'title' => 'Ajouter un objet',
                'error' => 'Une erreur est survenue lors du chargement des catégories'
            ]);
        }
    }

    public function showModifierObjet($id) {
        try {
            $pdo = Flight::db();
            $objetModel = new ObjetModel($pdo);
            $categorieModel = new CategorieModel($pdo);
            $objet = $objetModel->getObjetById($id);
            $categories = $categorieModel->getAll();

            if (!$objet) {
                Flight::halt(404, 'Objet non trouvé');
                return;
            }

            Flight::render('admin/objets/modifier', [
                'objet' => $objet,
                'categories' => $categories,
                'title' => 'Modifier un objet'
            ]);
        } catch (Exception $e) {
            error_log("Erreur modification objet: " . $e->getMessage());
            Flight::halt(500, 'Erreur lors du chargement de l\'objet');
        }
    }

    // Insert and update from Sharon, adapted
    public function insertObjetFromForm() {
        $nom = $_POST['nom'] ?? '';
        $description = $_POST['description'] ?? '';
        $idCategorie = $_POST['id_categorie'] ?? '';
        $prix = $_POST['prix'] ?? 0;

        if (empty(trim($nom)) || empty($idCategorie)) {
            $_SESSION['error'] = 'Le nom et la catégorie sont obligatoires';
            Flight::redirect('/admin/objets/ajout');
            return;
        }

        try {
            $pdo = Flight::db();
            $objetModel = new ObjetModel($pdo);
            if ($objetModel->existsByTitre($nom)) {
                $_SESSION['error'] = 'Cet objet existe déjà';
                Flight::redirect('/admin/objets/ajout');
                return;
            }
            $id = $objetModel->createObjet($nom, $description, $prix, $idCategorie);
            if ($id) {
                $_SESSION['success'] = 'Objet ajouté avec succès';
            } else {
                $_SESSION['error'] = 'Erreur lors de l\'ajout';
            }
        } catch (Exception $e) {
            error_log("Erreur insertion objet: " . $e->getMessage());
            $_SESSION['error'] = 'Une erreur est survenue lors de l\'ajout';
        }

        Flight::redirect('/admin/objets');
    }

    public function updateObjetFromForm() {
        $id = $_POST['id'] ?? '';
        $nom = $_POST['nom'] ?? '';
        $description = $_POST['description'] ?? '';
        $idCategorie = $_POST['id_categorie'] ?? '';
        $prix = $_POST['prix'] ?? 0;

        if (empty($id) || empty(trim($nom)) || empty($idCategorie)) {
            $_SESSION['error'] = 'Tous les champs sont obligatoires';
            Flight::redirect('/admin/objets/modifier/' . $id);
            return;
        }

        try {
            $pdo = Flight::db();
            $objetModel = new ObjetModel($pdo);
            $success = $objetModel->updateObjet($id, $nom, $description, $prix, $idCategorie);
            if ($success) {
                $_SESSION['success'] = 'Objet mis à jour avec succès';
            } else {
                $_SESSION['error'] = 'Erreur lors de la mise à jour';
            }
        } catch (Exception $e) {
            error_log("Erreur mise à jour objet: " . $e->getMessage());
            $_SESSION['error'] = 'Une erreur est survenue lors de la mise à jour';
        }

        Flight::redirect('/admin/objets');
    }

    public function deleteObjetFromForm($id) {
        try {
            $pdo = Flight::db();
            $objetModel = new ObjetModel($pdo);
            $success = $objetModel->deleteObjet($id);
            if ($success) {
                $_SESSION['success'] = 'Objet supprimé avec succès';
            } else {
                $_SESSION['error'] = 'Erreur lors de la suppression';
            }
        } catch (Exception $e) {
            error_log("Erreur suppression objet: " . $e->getMessage());
            $_SESSION['error'] = 'Une erreur est survenue lors de la suppression';
        }

        Flight::redirect('/admin/objets');
    }

    public function showObjetsByCategorie($idCategorie) {
        try {
            $pdo = Flight::db();
            $objetModel = new ObjetModel($pdo);
            $categorieModel = new CategorieModel($pdo);
            $objets = $objetModel->getByCategorie($idCategorie);
            $categorie = $categorieModel->getById($idCategorie);

            if (!$categorie) {
                Flight::halt(404, 'Catégorie non trouvée');
                return;
            }

            Flight::render('objets/categorie', [
                'objets' => $objets,
                'categorie' => $categorie,
                'title' => 'Objets - ' . htmlspecialchars($categorie['libelle'])
            ]);
        } catch (Exception $e) {
            error_log("Erreur affichage objets par catégorie: " . $e->getMessage());
            Flight::halt(500, 'Erreur lors du chargement des objets');
        }
    }

    // API methods from Sharon
    public function getAllObjetsApi() {
        try {
            $pdo = Flight::db();
            $objetModel = new ObjetModel($pdo);
            $objets = $objetModel->getAllObjets();
            Flight::json(['success' => true, 'data' => $objets]);
        } catch (Exception $e) {
            error_log("Erreur API objets: " . $e->getMessage());
            Flight::json(['success' => false, 'error' => 'Erreur lors de la récupération des objets'], 500);
        }
    }

    public function searchObjets() {
        $query = $_GET['q'] ?? '';

        try {
            $pdo = Flight::db();
            $objetModel = new ObjetModel($pdo);
            $objets = $objetModel->search($query);
            Flight::json(['success' => true, 'data' => $objets]);
        } catch (Exception $e) {
            error_log("Erreur recherche objets: " . $e->getMessage());
            Flight::json(['success' => false, 'error' => 'Erreur lors de la recherche'], 500);
        }
    }
}

?>