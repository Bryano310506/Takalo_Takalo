<?php

namespace app\model;
use PDO;

class UserModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function authAdmin($nom, $password) {
        $stmt = $this->db->prepare("SELECT u.*, r.libelle as role_libelle FROM user u JOIN role r ON u.id_role = r.id WHERE u.nom = :nom AND u.id_role = 1");
        $stmt->bindParam(':nom', $nom);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['mdp'])) {
            unset($user['mdp']);
            return $user;
        }
        
        return null;
    }

    /**
     * Authenticate user with identifier (email or nom) and password
     */
    public function authenticate($identifier, $password) {
        // Determine if identifier is email or nom
        $field = strpos($identifier, '@') !== false ? 'email' : 'nom';
        
        $stmt = $this->db->prepare("SELECT u.*, r.libelle as role_libelle FROM user u JOIN role r ON u.id_role = r.id WHERE u.$field = :identifier AND u.id_role != 1");
        $stmt->bindParam(':identifier', $identifier);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['mdp'])) {
            unset($user['mdp']);
            return $user;
        }
        
        return null;
    }

    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT u.*, r.libelle as role_libelle FROM user u JOIN role r ON u.id_role = r.id WHERE u.id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT u.*, r.libelle as role_libelle FROM user u JOIN role r ON u.id_role = r.id WHERE u.email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT u.id_user, u.nom, u.prenom, u.email, u.telephone, u.id_role, r.libelle as role_libelle FROM user u JOIN role r ON u.id_role = r.id ORDER BY u.nom ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createUser($nom, $prenom, $email, $password, $telephone, $role) {
        $stmt = $this->db->prepare("INSERT INTO user (nom, prenom, email, mdp, telephone, id_role) VALUES (:nom, :prenom, :email, :password, :telephone, :role)");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', password_hash($password, PASSWORD_BCRYPT));
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':role', $role, PDO::PARAM_INT);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function updateUser($id, $nom, $prenom, $email, $telephone, $role, $password = null) {
        if ($password) {
            $stmt = $this->db->prepare("UPDATE user SET nom = :nom, prenom = :prenom, email = :email, telephone = :telephone, mdp = :password, id_role = :role WHERE id = :id");
            $stmt->bindParam(':password', password_hash($password, PASSWORD_BCRYPT));
        } else {
            $stmt = $this->db->prepare("UPDATE user SET nom = :nom, prenom = :prenom, email = :email, telephone = :telephone, id_role = :role WHERE id = :id");
        }
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':role', $role, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteUser($id) {
        $stmt = $this->db->prepare("DELETE FROM user WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM user WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function existsByName($nom) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM user WHERE nom = :nom");
        $stmt->bindParam(':nom', $nom);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function getAllRoles() {
        $stmt = $this->db->query("SELECT id, libelle FROM role ORDER BY libelle ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStatistics() {
        $stats = [];
        
        $stmt = $this->db->query("SELECT COUNT(*) as total_users FROM user");
        $stats['total_users'] = $stmt->fetchColumn();
        
        $stmt = $this->db->query("SELECT r.libelle, COUNT(u.id_user) as count FROM role r LEFT JOIN user u ON r.id = u.id_role GROUP BY r.id, r.libelle ORDER BY count DESC");
        $stats['users_by_role'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt = $this->db->query("SELECT u.nom, r.libelle as role FROM user u JOIN role r ON u.id_role = r.id ORDER BY u.id_user DESC LIMIT 5");
        $stats['recent_users'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $stats;
    }

    public function countObjetsByUser($userId) {
        // Assuming proprietaire_objet table
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM proprietaire_objet WHERE id_user = :id_user AND date_fin IS NULL");
        $stmt->bindParam(':id_user', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}

?>