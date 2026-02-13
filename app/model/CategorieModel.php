<?php

namespace app\model;
use PDO;

class CategorieModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT id, libelle FROM categorie ORDER BY libelle ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT id, libelle FROM categorie WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function existsByLibelle($libelle) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM categorie WHERE libelle = :libelle");
        $stmt->bindParam(':libelle', $libelle);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function create($libelle) {
        if ($this->existsByLibelle($libelle)) {
            return null;
        }
        $stmt = $this->db->prepare("INSERT INTO categorie (libelle) VALUES (:libelle)");
        $stmt->bindParam(':libelle', $libelle);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function update($id, $libelle) {
        $stmt = $this->db->prepare("UPDATE categorie SET libelle = :libelle WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':libelle', $libelle);
        return $stmt->execute();
    }

    public function delete($id) {
        // Check if objects are associated
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM objets WHERE id_categorie = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            return false;
        }
        $stmt = $this->db->prepare("DELETE FROM categorie WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

?>