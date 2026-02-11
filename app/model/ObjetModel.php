<?php

namespace app\model;
use PDO;

class ObjetModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllObjets() {
        $stmt = $this->db->query("SELECT * FROM objets");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getObjetById($id) {
        $stmt = $this->db->prepare("SELECT * FROM objets WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createObjet($titre, $description, $id_categorie, $prix) {
        $stmt = $this->db->prepare("INSERT INTO objets (titre, description, id_categorie, prix) VALUES (:titre, :description, :id_categorie, :prix)");
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id_categorie', $id_categorie, PDO::PARAM_INT);
        $stmt->bindParam(':prix', $prix);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function updateObjet($id, $titre, $description, $id_categorie, $prix) {
        $stmt = $this->db->prepare("UPDATE objets SET titre = :titre, description = :description, id_categorie = :id_categorie, prix = :prix WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id_categorie', $id_categorie, PDO::PARAM_INT);
        $stmt->bindParam(':prix', $prix);
        return $stmt->execute();
    }

    public function deleteObjet($id) {
        $stmt = $this->db->prepare("DELETE FROM objets WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

}

?>