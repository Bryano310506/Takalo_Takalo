<?php

namespace app\models;

use PDO;
use PDOException;

class ProprietaireObjetModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Insérer un nouveau propriétaire
     * @param int $id_user L'utilisateur propriétaire
     * @param int $id_objet L'objet
     * @param int|null $id_echange L'échange optionnel
     * @return int|false L'ID inséré ou false
     */
    public function insert(int $id_user, int $id_objet, ?int $id_echange = null)
    {
        try {
            $sql = "INSERT INTO proprietaire_objet 
                    (id_user, id_objet, id_echange, date_debut)
                    VALUES (:id_user, :id_objet, :id_echange, NOW())";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id_user' => $id_user,
                ':id_objet' => $id_objet,
                ':id_echange' => $id_echange
            ]);

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'insertion du propriétaire: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Transférer la propriété d'un objet
     * Clôture l'ancienne propriété et crée une nouvelle avec transaction
     * @param int $id_user_nouveau Le nouvel propriétaire
     * @param int $id_objet L'objet
     * @param int|null $id_echange L'échange optionnel
     * @return bool true si succès, false sinon
     */
    public function transferer(int $id_user_nouveau, int $id_objet, ?int $id_echange = null): bool
    {
        try {
            $this->db->beginTransaction();

            // Clôturer la propriété actuelle
            $sql_update = "UPDATE proprietaire_objet 
                          SET date_fin = NOW() 
                          WHERE id_objet = :id_objet AND date_fin IS NULL";
            
            $stmt = $this->db->prepare($sql_update);
            $stmt->execute([':id_objet' => $id_objet]);

            // Créer la nouvelle propriété
            $sql_insert = "INSERT INTO proprietaire_objet 
                          (id_user, id_objet, id_echange, date_debut)
                          VALUES (:id_user, :id_objet, :id_echange, NOW())";
            
            $stmt = $this->db->prepare($sql_insert);
            $stmt->execute([
                ':id_user' => $id_user_nouveau,
                ':id_objet' => $id_objet,
                ':id_echange' => $id_echange
            ]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erreur lors du transfert de propriété: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer le propriétaire actuel d'un objet
     * @param int $id_objet L'ID de l'objet
     * @return array|false Les données du propriétaire ou false
     */
    public function getProprietaireActuel(int $id_objet)
    {
        try {
            $sql = "SELECT * FROM proprietaire_objet 
                    WHERE id_objet = :id_objet AND date_fin IS NULL";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id_objet' => $id_objet]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération du propriétaire: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifier si un utilisateur possède actuellement un objet
     * @param int $id_user L'utilisateur
     * @param int $id_objet L'objet
     * @return bool true si propriétaire actuel, false sinon
     */
    public function estProprietaire(int $id_user, int $id_objet): bool
    {
        try {
            $sql = "SELECT id FROM proprietaire_objet 
                    WHERE id_user = :id_user 
                    AND id_objet = :id_objet 
                    AND date_fin IS NULL";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id_user' => $id_user, ':id_objet' => $id_objet]);
            return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification de propriété: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer tous les objets d'un utilisateur
     * @param int $id_user L'utilisateur
     * @return array Liste des objets actuels
     */
    public function getObjetsByUser(int $id_user): array
    {
        try {
            $sql = "SELECT 
                        po.id,
                        po.id_user,
                        po.id_objet,
                        po.id_echange,
                        po.date_debut,
                        o.titre,
                        o.description,
                        o.prix,
                        c.libelle as categorie
                    FROM proprietaire_objet po
                    JOIN objets o ON po.id_objet = o.id_objet
                    JOIN categorie c ON o.id_categorie = c.id
                    WHERE po.id_user = :id_user AND po.date_fin IS NULL
                    ORDER BY po.date_debut DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id_user' => $id_user]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des objets: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer l'historique complet de propriété d'un objet
     * @param int $id_objet L'objet
     * @return array Historique de propriété
     */
    public function getHistorique(int $id_objet): array
    {
        try {
            $sql = "SELECT 
                        po.id,
                        po.id_user,
                        po.id_objet,
                        po.id_echange,
                        po.date_debut,
                        po.date_fin,
                        u.nom as user_nom
                    FROM proprietaire_objet po
                    LEFT JOIN user u ON po.id_user = u.id_user
                    WHERE po.id_objet = :id_objet 
                    ORDER BY po.date_debut DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id_objet' => $id_objet]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'historique: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Clôturer la propriété d'un objet
     * @param int $id_objet L'objet
     * @return bool true si succès, false sinon
     */
    public function cloturer(int $id_objet): bool
    {
        try {
            $sql = "UPDATE proprietaire_objet 
                    SET date_fin = NOW() 
                    WHERE id_objet = :id_objet AND date_fin IS NULL";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id_objet' => $id_objet]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la clôture de la propriété: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer les détails complets d'une propriété
     * @param int $id L'ID de l'enregistrement
     * @return array|false Les détails ou false
     */
    public function getById(int $id)
    {
        try {
            $sql = "SELECT 
                        po.*,
                        u.nom as user_nom,
                        o.titre as objet_titre,
                        o.description as objet_description
                    FROM proprietaire_objet po
                    LEFT JOIN user u ON po.id_user = u.id_user
                    LEFT JOIN objets o ON po.id_objet = o.id_objet
                    WHERE po.id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de la propriété: " . $e->getMessage());
            return false;
        }
    }
}
?>
