<?php

namespace app\services;

use app\model\ObjetModel;
use app\model\PhotoModel;
use app\model\ProprietaireObjetModel;

class ObjetService {
    private $objetModel;
    private $photoModel;
    private $proprietaireObjetModel;

    public function __construct($db) {
        $this->objetModel = new ObjetModel($db);
        $this->photoModel = new PhotoModel($db);
        $this->proprietaireObjetModel = new ProprietaireObjetModel($db);
    }

    public function getObjetByUserId($id_user) {
        $proprietaireObjetModel = $this->proprietaireObjetModel->getObjetsByUserId($id_user);
        $objets = [];
        foreach ($proprietaireObjetModel as $proprietaireObjet) {
            $objet = $this->objetModel->getObjetById($proprietaireObjet['id_objet']);
            if ($objet) {
                $photos = $this->photoModel->getPhotosByObjetId($objet['id_objet']);
                $objet['photos'] = $photos;
                $objets[] = $objet; 
            }
        }
        return $objets;
    }
    
    public function insertObjet($id_user, $titre, $description, $id_categorie, $prix, $photos) {
        $id_objet = $this->objetModel->createObjet($titre, $description, $id_categorie, $prix);
        if ($id_objet) {
            if ($this->proprietaireObjetModel->createProprietaireObjet($id_user, $id_objet)) {
                foreach ($photos as $photo) {
                    
                    $this->photoModel->createPhoto($id_objet, $photo);
                }
                return true;
            }
        }
        return false;
    }

    public function uploadPhotos($files) {
        $uploadedPhotos = [];
        $uploadDir = __DIR__ . '/../../public/assets/img/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        foreach ($files['tmp_name'] as $key => $tmp_name) {
            if ($files['error'][$key] === UPLOAD_ERR_OK) {
                $name = basename($files['name'][$key]);
                $target = $uploadDir . $name;
                
                if (move_uploaded_file($tmp_name, $target)) {
                    $uploadedPhotos[] = $name;
                }
            }
        }
        return $uploadedPhotos;
    }

    public function getObjetById($id) {
        $objet = $this->objetModel->getObjetById($id);
        if ($objet) {
            $photos = $this->photoModel->getPhotosByObjetId($id);
            $objet['photos'] = $photos;
            return $objet;
        }
        return null;
    }

    public function deleteObjet($id) { 
        $photos = $this->photoModel->getPhotosByObjetId($id); 
        foreach ($photos as $photo) { 
            $filePath = __DIR__ . '/../../public/assets/img/' . $photo['nom']; 
            if (file_exists($filePath)) { 
                unlink($filePath);
            } 
        }
        return $this->objetModel->deleteObjet($id);
    }

    public function updateObjet($id, $titre, $description, $id_categorie, $prix, $photos) { 
        $existingObjet = $this->objetModel->getObjetById($id); 
        if (!$existingObjet) { 
            return false; 
        } 
        if ($this->objetModel->updateObjet($id, $titre, $description, $id_categorie, $prix)) { 
            if ($photos) { 
                $this->photoModel->deletePhoto($id); 
                foreach ($photos as $photo) { 
                    $this->photoModel->createPhoto($id, $photo); 
                } 
            } 
            return true;
        } 
        return false;
    }

}

?>