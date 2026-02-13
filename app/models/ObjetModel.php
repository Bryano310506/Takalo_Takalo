<?php

namespace app\models;

use PDO;
use PDOException;

class ObjetModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Récupérer tous les objets actuellement possédés par un utilisateur
     * @param int $id_user L'utilisateur
     * @return array Liste des objets
     */
    public function getObjetsActuels(int $id_user): array
    {
        try {
            $sql = "SELECT 
                        o.id_objet,
                        o.titre,
                        o.description,
                        o.prix,
                        c.libelle as categorie,
                        o.date_creation
                    FROM objets o
                    JOIN categorie c ON o.id_categorie = c.id
                    JOIN v_current_objet_user v ON o.id_objet = v.id_objet
                    WHERE v.id_user = :id_user
                    ORDER BY o.titre";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id_user' => $id_user]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des objets actuels: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer tous les objets disponibles (pour afficher la liste)
     * @return array Liste de tous les objets avec propriétaire
     */
    public function getAllObjets(): array
    {
        try {
            $sql = "SELECT 
                        o.id_objet,
                        o.titre,
                        o.description,
                        o.prix,
                        c.libelle as categorie,
                        u.id_user,
                        u.nom as proprietaire_nom,
                        o.date_creation
                    FROM objets o
                    JOIN categorie c ON o.id_categorie = c.id
                    JOIN v_current_objet_user vcou ON o.id_objet = vcou.id_objet
                    JOIN user u ON vcou.id_user = u.id_user
                    ORDER BY o.titre";

            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de tous les objets: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer les objets NON possédés par un utilisateur (objets d'autres utilisateurs)
     * @param int $id_user L'utilisateur
     * @return array Liste des objets disponibles pour l'échange
     */
    public function getObjetsNonPossedes(int $id_user): array
    {
        try {
            $sql = "SELECT 
                        o.id_objet,
                        o.titre,
                        o.description,
                        o.prix,
                        c.libelle as categorie,
                        u.id_user,
                        u.nom as proprietaire_nom,
                        o.date_creation
                    FROM objets o
                    JOIN categorie c ON o.id_categorie = c.id
                    JOIN v_current_objet_user vcou ON o.id_objet = vcou.id_objet
                    JOIN user u ON vcou.id_user = u.id_user
                    WHERE o.id_objet NOT IN (
                        SELECT id_objet
                        FROM v_current_objet_user
                        WHERE id_user = :id_user
                    )
                    ORDER BY o.titre";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id_user' => $id_user]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des objets non possédés: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer un objet spécifique par son ID
     * @param int $id_objet L'ID de l'objet
     * @return array|false Les détails de l'objet ou false
     */
    public function getObjetById(int $id_objet)
    {
        try {
            $sql = "SELECT 
                        o.id_objet,
                        o.titre,
                        o.description,
                        o.prix,
                        c.libelle as categorie,
                        u.id_user,
                        u.nom as proprietaire_nom
                    FROM objets o
                    JOIN categorie c ON o.id_categorie = c.id
                    JOIN v_current_objet_user vcou ON o.id_objet = vcou.id_objet
                    JOIN user u ON vcou.id_user = u.id_user
                    WHERE o.id_objet = :id_objet";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id_objet' => $id_objet]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'objet: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifier si un objet existe
     * @param int $id_objet L'ID de l'objet
     * @return bool true si existe, false sinon
     */
    public function exists(int $id_objet): bool
    {
        try {
            $sql = "SELECT id_objet FROM objets WHERE id_objet = :id_objet";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id_objet' => $id_objet]);
            return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification de l'objet: " . $e->getMessage());
            return false;
        }
    }

    public function getObjetFiltred($mot_cle) {
        $sql = "SELECT * FROM objets WHERE title LIKE %?% AND cat"
    }
}
?>
