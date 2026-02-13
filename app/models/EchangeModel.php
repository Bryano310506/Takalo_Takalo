<?php

namespace app\models;

use app\models\StatusModel;
use Flight;
use PDO;

class EchangeModel
{
    protected $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllEchangeAttente($id_user)
    {
        return $this->getAllEchange($id_user,"PENDING");
    }

    public function getEchangeAttenteDetail($id_recepteur) {
        $sql = "SELECT
                    id_echange, id_emetteur, id_recepteur, id_status,
                    nom_emetteur, nom_recepteur,
                    id_objet_propose, titre_objet_propose, description_objet_propose, prix_objet_propose, categorie_objet_propose,
                    id_objet_demande, titre_objet_demande, description_objet_demande, prix_objet_demande, categorie_objet_demande,
                    status_code, status_libelle,
                    date_proposition, date_reponse
                FROM
                    v_echange_details
                WHERE
                    id_recepteur = :id_recepteur 
                AND status_code = 'PENDING'"; // <-- La condition était manquante ici

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_recepteur' => $id_recepteur
        ]);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getAllEchange($id_user, $statut_code)
    {
        $sql = "SELECT
                    id_echange,
                    id_emetteur,
                    id_recepteur,
                    id_objet_propose,
                    id_objet_demande,
                    id_status,
                    date_debut,
                    date_fin,
                    status_code,
                    status_libelle
                FROM
                    v_historique_echange_status
                WHERE
                    status_code = :status_code
                    AND id_recepteur = :id_recepteur";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':status_code' => $statut_code,
            ':id_recepteur' => $id_user
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createPropositionEchange($id_emetteur, $id_recepteur, $id_objet_propose, $id_objet_demande)
    {
        // Récupérer l'ID du statut PENDING
        $statusModel = new StatusModel(Flight::db());
        $statusPending = $statusModel->getStatusByCode("PENDING");
        if (!$statusPending) {
            return false;
        }

        $sql = "INSERT INTO historique_echange 
                (id_emetteur, id_recepteur, id_objet_propose, id_objet_demande, id_status, date_debut)
                VALUES 
                (:id_emetteur, :id_recepteur, :id_objet_propose, :id_objet_demande, :id_status, NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_emetteur' => $id_emetteur,
            ':id_recepteur' => $id_recepteur,
            ':id_objet_propose' => $id_objet_propose,
            ':id_objet_demande' => $id_objet_demande,
            ':id_status' => $statusPending['id']
        ]);

        return $this->db->lastInsertId();
    }

    public function acceptEchange($id_echange)
    {
        // Récupérer l'ID du statut ACCEPTED
        $statusModel = new StatusModel(Flight::db());
        $statusAccepted = $statusModel->getStatusByCode("ACCEPTED");
        if (!$statusAccepted) {
            return false;
        }

        $sql = "UPDATE historique_echange 
                SET id_status = :id_status, date_fin = NOW()
                WHERE id = :id_echange";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            ':id_status' => $statusAccepted['id'],
            ':id_echange' => $id_echange
        ]);

        return $result;
    }

    public function refuserEchange($id_echange)
    {
        // Récupérer l'ID du statut REFUSED
        $statusModel = new StatusModel(Flight::db());
        $statusRefused = $statusModel->getStatusByCode("REFUSED");
        if (!$statusRefused) {
            return false;
        }

        $sql = "UPDATE historique_echange 
                SET id_status = :id_status, date_fin = NOW()
                WHERE id = :id_echange";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            ':id_status' => $statusRefused['id'],
            ':id_echange' => $id_echange
        ]);

        return $result;
    }

    public function rejeterEchange($id_echange)
    {
        // Récupérer l'ID du statut REJECTED
        $statusModel = new StatusModel(Flight::db());
        $statusRejected = $statusModel->getStatusByCode("REJECTED");
        if (!$statusRejected) {
            return false;
        }

        $sql = "UPDATE historique_echange 
                SET id_status = :id_status, date_fin = NOW()
                WHERE id = :id_echange";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            ':id_status' => $statusRejected['id'],
            ':id_echange' => $id_echange
        ]);

        return $result;
    }

    public function getEchangeById($id_echange)
    {
        $sql = "SELECT
                    id_echange,
                    id_emetteur,
                    id_recepteur,
                    id_objet_propose,
                    id_objet_demande,
                    id_status,
                    date_debut,
                    date_fin,
                    status_code,
                    status_libelle
                FROM
                    v_historique_echange_status
                WHERE
                    id_echange = :id_echange";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_echange' => $id_echange
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function completerEchange($id_echange)
    {
        // Récupérer l'ID du statut COMPLETED
        $statusModel = new StatusModel(Flight::db());
        $statusCompleted = $statusModel->getStatusByCode("COMPLETED");
        if (!$statusCompleted) {
            return false;
        }

        $sql = "UPDATE historique_echange 
                SET id_status = :id_status, date_fin = NOW()
                WHERE id = :id_echange";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            ':id_status' => $statusCompleted['id'],
            ':id_echange' => $id_echange
        ]);

        return $result;
    }
}
