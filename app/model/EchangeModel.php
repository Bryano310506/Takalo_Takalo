<?php

namespace app\model;

use PDO;

class EchangeModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createEchange($id_status, $id_objet_emetteur, $id_objet_recepteur) {
        $stmt = $this->db->prepare("INSERT INTO echanges (id_objet_emetteur, id_objet_recepteur, id_status, date_debut) VALUES (:id_objet_emetteur, :id_objet_recepteur, :id_status, NOW())");
        $stmt->bindParam(':id_objet_emetteur', $id_objet_emetteur, PDO::PARAM_INT);
        $stmt->bindParam(':id_objet_recepteur', $id_objet_recepteur, PDO::PARAM_INT);
        $stmt->bindParam(':id_status', $id_status, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function accepterEchange($id, $id_status) {
        $stmt = $this->db->prepare("UPDATE echanges SET id_status = :id_status, date_fin = NOW() WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':id_status', $id_status, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function refuserEchange($id, $id_status) {
        $stmt = $this->db->prepare("UPDATE echanges SET id_status = :id_status, date_fin = NOW() WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':id_status', $id_status, PDO::PARAM_INT);
        return $stmt->execute();
    }

}