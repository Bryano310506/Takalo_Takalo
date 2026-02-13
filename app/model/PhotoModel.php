<?php

namespace app\model;
use PDO;

class PhotoModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getPhotosByObjetId($id_objet) {
        $stmt = $this->db->prepare("SELECT * FROM photos WHERE id_objet = :id_objet");
        $stmt->bindParam(':id_objet', $id_objet, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createPhoto($id_objet, $nom) {
        $stmt = $this->db->prepare("INSERT INTO photos (id_objet, nom) VALUES (:id_objet, :nom)");
        $stmt->bindParam(':id_objet', $id_objet, PDO::PARAM_INT);
        $stmt->bindParam(':nom', $nom);
        return $stmt->execute();
    }

    public function deletePhoto($id) {
        $stmt = $this->db->prepare("DELETE FROM photos WHERE id_objet = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

}

?>