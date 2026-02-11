<?php

namespace app\models;

class ObjetModel {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllObjet() {
        $sql = "SELECT * FROM objets";
        return $this->db->query($sql)->fetchAll();
    }
}