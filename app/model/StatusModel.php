<?php

namespace app\model;

use PDO;

class StatusModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllStatus() {
        $stmt = $this->db->query("SELECT * FROM status");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStatusById($id) {
        $stmt = $this->db->prepare("SELECT * FROM status WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


}

?>