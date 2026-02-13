<?php

namespace app\models;

use PDO;
use PDOException;

class HistoriqueEchangeModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Créer une proposition d'échange
     * @param int $id_emetteur L'utilisateur qui propose
     * @param int $id_recepteur L'utilisateur qui reçoit la proposition
     * @param int $id_objet_propose L'objet proposé
     * @param int $id_objet_demande L'objet demandé
     * @param int $id_status Le statut (généralement PENDING)
     * @return int|false L'ID créé ou false
     */
    public function createExchange(
        int $id_emetteur,
        int $id_recepteur,
        int $id_objet_propose,
        int $id_objet_demande,
        int $id_status
    )
    {
        try {
            $sql = "INSERT INTO historique_echange (
                        id_emetteur,
                        id_recepteur,
                        id_objet_propose,
                        id_objet_demande,
                        id_status,
                        date_debut
                    ) VALUES (
                        :id_emetteur,
                        :id_recepteur,
                        :id_objet_propose,
                        :id_objet_demande,
                        :id_status,
                        NOW()
                    )";

            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                ':id_emetteur' => $id_emetteur,
                ':id_recepteur' => $id_recepteur,
                ':id_objet_propose' => $id_objet_propose,
                ':id_objet_demande' => $id_objet_demande,
                ':id_status' => $id_status
            ]);

            return $result ? $this->db->lastInsertId() : false;
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de l'échange: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer un échange par son ID avec tous les détails
     * @param int $id L'ID de l'échange
     * @return array|false Les détails de l'échange ou false
     */
    public function getExchangeById(int $id)
    {
        try {
            $sql = "SELECT
                        h.id AS id_echange,
                        h.id_emetteur,
                        h.id_recepteur,
                        h.id_objet_propose,
                        h.id_objet_demande,
                        h.id_status,
                        h.date_debut,
                        h.date_fin,
                        u_em.nom AS emetteur_nom,
                        u_re.nom AS recepteur_nom,
                        obj_p.titre AS objet_propose_titre,
                        obj_p.description AS objet_propose_description,
                        obj_p.prix AS objet_propose_prix,
                        obj_d.titre AS objet_demande_titre,
                        obj_d.description AS objet_demande_description,
                        obj_d.prix AS objet_demande_prix,
                        s.code AS status_code,
                        s.libelle AS status_libelle
                    FROM historique_echange h
                    JOIN user u_em ON h.id_emetteur = u_em.id_user
                    JOIN user u_re ON h.id_recepteur = u_re.id_user
                    JOIN objets obj_p ON h.id_objet_propose = obj_p.id_objet
                    JOIN objets obj_d ON h.id_objet_demande = obj_d.id_objet
                    JOIN status s ON h.id_status = s.id
                    WHERE h.id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'échange: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer les échanges en attente (PENDING) pour un utilisateur (en tant que récepteur)
     * @param int $id_recepteur L'ID du récepteur
     * @return array Liste des échanges en attente
     */
    public function getPendingExchanges(int $id_recepteur): array
    {
        try {
            $sql = "SELECT
                        h.id AS id_echange,
                        h.id_emetteur,
                        h.id_recepteur,
                        h.id_objet_propose,
                        h.id_objet_demande,
                        h.date_debut,
                        u_em.nom AS emetteur_nom,
                        obj_p.titre AS objet_propose_titre,
                        obj_d.titre AS objet_demande_titre
                    FROM historique_echange h
                    JOIN user u_em ON h.id_emetteur = u_em.id_user
                    JOIN objets obj_p ON h.id_objet_propose = obj_p.id_objet
                    JOIN objets obj_d ON h.id_objet_demande = obj_d.id_objet
                    JOIN status s ON h.id_status = s.id
                    WHERE h.id_recepteur = :id_recepteur AND s.code = 'PENDING'
                    ORDER BY h.date_debut DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id_recepteur' => $id_recepteur]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des échanges en attente: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer les détails complets des échanges en attente (avec vue v_echange_details)
     * @param int $id_recepteur L'ID du récepteur
     * @return array Liste détaillée des échanges en attente
     */
    public function getPendingExchangesDetail(int $id_recepteur): array
    {
        try {
            $sql = "SELECT * FROM v_echange_details
                    WHERE id_recepteur = :id_recepteur AND status_code = 'PENDING'
                    ORDER BY date_proposition DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id_recepteur' => $id_recepteur]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des détails des échanges: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer tous les échanges d'un utilisateur (émetteur ou récepteur) avec un statut optionnel
     * @param int $id_user L'utilisateur
     * @param string|null $status_code Filtre optionnel par code de statut
     * @return array Liste des échanges
     */
    public function getUserExchanges(int $id_user, ?string $status_code = null): array
    {
        try {
            $sql = "SELECT
                        h.id AS id_echange,
                        h.id_emetteur,
                        h.id_recepteur,
                        h.id_objet_propose,
                        h.id_objet_demande,
                        h.date_debut,
                        h.date_fin,
                        u_em.nom AS emetteur_nom,
                        u_re.nom AS recepteur_nom,
                        obj_p.titre AS objet_propose_titre,
                        obj_d.titre AS objet_demande_titre,
                        s.code AS status_code,
                        s.libelle AS status_libelle
                    FROM historique_echange h
                    JOIN user u_em ON h.id_emetteur = u_em.id_user
                    JOIN user u_re ON h.id_recepteur = u_re.id_user
                    JOIN objets obj_p ON h.id_objet_propose = obj_p.id_objet
                    JOIN objets obj_d ON h.id_objet_demande = obj_d.id_objet
                    JOIN status s ON h.id_status = s.id
                    WHERE (h.id_emetteur = :id_user OR h.id_recepteur = :id_user)";

            if ($status_code) {
                $sql .= " AND s.code = :status_code";
            }

            $sql .= " ORDER BY h.date_debut DESC";

            $stmt = $this->db->prepare($sql);
            $params = [':id_user' => $id_user];
            if ($status_code) {
                $params[':status_code'] = $status_code;
            }

            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des échanges: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Mettre à jour le statut d'un échange
     * @param int $id_echange L'ID de l'échange
     * @param int $id_status Le nouveau statut ID
     * @return bool true si succès, false sinon
     */
    public function updateStatus(int $id_echange, int $id_status): bool
    {
        try {
            $sql = "UPDATE historique_echange
                    SET id_status = :id_status, date_fin = NOW()
                    WHERE id = :id_echange";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id_echange' => $id_echange,
                ':id_status' => $id_status
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour du statut: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mettre à jour le statut d'un échange par son code
     * @param int $id_echange L'ID de l'échange
     * @param string $status_code Le code du statut ('PENDING', 'ACCEPTED', 'REFUSED', etc.)
     * @param StatusModel $statusModel Une instance de StatusModel
     * @return bool true si succès, false sinon
     */
    public function updateStatusByCode(int $id_echange, string $status_code, StatusModel $statusModel): bool
    {
        $status_id = $statusModel->getStatusIdByCode($status_code);
        if (!$status_id) {
            return false;
        }
        return $this->updateStatus($id_echange, $status_id);
    }

    /**
     * Supprimer un échange (généralement les propositions PENDING non acceptées)
     * @param int $id L'ID de l'échange
     * @return bool true si succès, false sinon
     */
    public function deleteExchange(int $id): bool
    {
        try {
            $sql = "DELETE FROM historique_echange WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de l'échange: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Compter les échanges en attente pour un utilisateur
     * @param int $id_recepteur L'ID du récepteur
     * @return int Nombre d'échanges en attente
     */
    public function countPendingExchanges(int $id_recepteur): int
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM historique_echange h
                    JOIN status s ON h.id_status = s.id
                    WHERE h.id_recepteur = :id_recepteur AND s.code = 'PENDING'";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id_recepteur' => $id_recepteur]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] ?? 0;
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des échanges: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Valider une proposition d'échange avant création
     * @param int $id_emetteur
     * @param int $id_recepteur
     * @param int $id_objet_propose
     * @param int $id_objet_demande
     * @param ProprietaireObjetModel $proprietaireModel
     * @return array ['valid' => bool, 'message' => string]
     */
    public function validateExchange(
        int $id_emetteur,
        int $id_recepteur,
        int $id_objet_propose,
        int $id_objet_demande,
        ProprietaireObjetModel $proprietaireModel
    ): array
    {
        // Utilisateurs différents
        if ($id_emetteur === $id_recepteur) {
            return ['valid' => false, 'message' => 'Un utilisateur ne peut pas échanger avec lui-même'];
        }

        // Émetteur possède l'objet proposé
        if (!$proprietaireModel->estProprietaire($id_emetteur, $id_objet_propose)) {
            return ['valid' => false, 'message' => 'Vous ne possédez pas cet objet'];
        }

        // Récepteur possède l'objet demandé
        if (!$proprietaireModel->estProprietaire($id_recepteur, $id_objet_demande)) {
            return ['valid' => false, 'message' => 'L\'autre utilisateur ne possède pas cet objet'];
        }

        // Objets différents
        if ($id_objet_propose === $id_objet_demande) {
            return ['valid' => false, 'message' => 'Vous devez échanger des objets différents'];
        }

        return ['valid' => true, 'message' => 'Validation réussie'];
    }
}
?>
