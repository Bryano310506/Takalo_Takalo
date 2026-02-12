<?php

namespace app\controllers;

use Flight;
use app\services\ObjetService;

class ObjetController {

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

}

?>