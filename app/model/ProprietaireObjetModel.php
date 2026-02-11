<?php

namespace app\model;
use PDO;

class ProprietaireObjetModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getProprietaireByObjetId($id_objet) {
        $stmt = $this->db->prepare("SELECT id_user FROM proprietaire_objet WHERE id_objet = :id_objet AND date_fin IS NULL");
        $stmt->bindParam(':id_objet', $id_objet, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createProprietaireObjet($id_user, $id_objet) {
        $stmt = $this->db->prepare("INSERT INTO proprietaire_objet (id_user, id_objet, date_debut) VALUES (:id_user, :id_objet, NOW())");
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindParam(':id_objet', $id_objet, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function endProprietaireObjet($id_user, $id_objet) {
        $stmt = $this->db->prepare("UPDATE proprietaire_objet SET date_fin = NOW() WHERE id_user = :id_user AND id_objet = :id_objet AND date_fin IS NULL");
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindParam(':id_objet', $id_objet, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getObjetsByUserId($id_user) {
        $stmt = $this->db->prepare("SELECT id_objet FROM proprietaire_objet WHERE id_user = :id_user AND date_fin IS NULL");
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>