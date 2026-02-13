<?php

namespace app\model;
use PDO;

class ObjetModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllObjets() {
        $stmt = $this->db->query("SELECT o.*, c.libelle as categorie_libelle FROM objets o LEFT JOIN categorie c ON o.id_categorie = c.id ORDER BY o.titre ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getObjetById($id) {
        $stmt = $this->db->prepare("SELECT o.*, c.libelle as categorie_libelle FROM objets o LEFT JOIN categorie c ON o.id_categorie = c.id WHERE o.id_objet = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByCategorie($idCategorie) {
        $stmt = $this->db->prepare("SELECT o.*, c.libelle as categorie_libelle FROM objets o LEFT JOIN categorie c ON o.id_categorie = c.id WHERE o.id_categorie = :id_categorie ORDER BY o.titre ASC");
        $stmt->bindParam(':id_categorie', $idCategorie, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createObjet($titre, $description, $prix, $id_categorie) {
        $stmt = $this->db->prepare("INSERT INTO objets (titre, description, prix, id_categorie) VALUES (:titre, :description, :prix, :id_categorie)");
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':prix', $prix);
        $stmt->bindParam(':id_categorie', $id_categorie, PDO::PARAM_INT);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function updateObjet($id, $titre, $description, $prix, $id_categorie) {
        $stmt = $this->db->prepare("UPDATE objets SET titre = :titre, description = :description, prix = :prix, id_categorie = :id_categorie WHERE id_objet = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':prix', $prix);
        $stmt->bindParam(':id_categorie', $id_categorie, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteObjet($id) {
        // Delete related records
        $stmt1 = $this->db->prepare("DELETE FROM proprietaire_objet WHERE id_objet = :id");
        $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt1->execute();
        
        $stmt2 = $this->db->prepare("DELETE FROM photos WHERE id_objet = :id");
        $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt2->execute();
        
        $stmt = $this->db->prepare("DELETE FROM objets WHERE id_objet = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function existsByTitre($titre) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM objets WHERE titre = :titre");
        $stmt->bindParam(':titre', $titre);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function countByCategorie($idCategorie) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM objets WHERE id_categorie = :id_categorie");
        $stmt->bindParam(':id_categorie', $idCategorie, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function search($query) {
        $stmt = $this->db->prepare("SELECT o.*, c.libelle as categorie_libelle FROM objets o LEFT JOIN categorie c ON o.id_categorie = c.id WHERE o.titre LIKE :query OR o.description LIKE :query ORDER BY o.titre ASC");
        $likeQuery = '%' . $query . '%';
        $stmt->bindParam(':query', $likeQuery);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>