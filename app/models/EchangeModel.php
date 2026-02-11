<?php

namespace app\models;

class EchangeModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllEchangeAttente($id_user)
    {
        $sql = "SELECT
                    *
                FROM
                    historique_echange he
                    JOIN status s on he.id_status = s.id
                WHERE s.code = ?";
    }
    public function getAllEchange($id_user, $id_status) {}
    public function acceptEchange($id_echange) {}
    public function refuserEchange($id_echange) {}
}
