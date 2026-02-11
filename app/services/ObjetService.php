<?php

namespace app\service;

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
                $photos = $this->photoModel->getPhotosByObjetId($objet['id']);
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

}

?>