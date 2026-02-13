<?php

namespace app\models;

use PDO;
use PDOException;

class StatusModel
{
    protected $db;
    private static $cache = []; // Cache en mémoire pour éviter les requêtes répétées

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Récupérer un statut par son code
     * @param string $code Le code du statut (ex: 'PENDING', 'ACCEPTED', 'REFUSED', 'REJECTED', 'COMPLETED')
     * @return array|false Les données du statut ou false
     */
    public function getStatusByCode(string $code)
    {
        // Vérifier le cache d'abord
        if (isset(self::$cache[$code])) {
            return self::$cache[$code];
        }

        try {
            $sql = "SELECT id, code, libelle FROM status WHERE code = :code";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':code' => $code]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                self::$cache[$code] = $result;
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération du statut: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer un statut par son ID
     * @param int $id L'ID du statut
     * @return array|false Les données du statut ou false
     */
    public function getStatusById(int $id)
    {
        try {
            $sql = "SELECT id, code, libelle FROM status WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération du statut: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer tous les statuts
     * @return array Liste de tous les statuts
     */
    public function getAllStatus()
    {
        try {
            $sql = "SELECT id, code, libelle FROM status ORDER BY id";
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des statuts: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtenir l'ID d'un statut par son code
     * @param string $code Le code du statut
     * @return int|false L'ID ou false
     */
    public function getStatusIdByCode(string $code)
    {
        $status = $this->getStatusByCode($code);
        return $status ? $status['id'] : false;
    }
}
?>
