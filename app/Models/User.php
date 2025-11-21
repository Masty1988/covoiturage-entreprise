<?php
namespace App\Models;

use App\Database;

/**
 * Model pour les utilisateurs
 */
class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Trouve un utilisateur par son email
     */
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare('SELECT * FROM utilisateurs WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Trouve un utilisateur par son ID
     */
    public function find($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM utilisateurs WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Recupere tous les utilisateurs
     */
    public function all()
    {
        $stmt = $this->db->query('SELECT * FROM utilisateurs ORDER BY id');
        return $stmt->fetchAll();
    }

    /**
     * Compte le nombre d'utilisateurs
     */
    public function count()
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM utilisateurs');
        return $stmt->fetchColumn();
    }

    /**
     * Cree un nouvel utilisateur
     */
    public function create($data)
    {
        $stmt = $this->db->prepare('
            INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, role)
            VALUES (?, ?, ?, ?, ?, ?)
        ');
        return $stmt->execute([
            $data['nom'],
            $data['prenom'],
            $data['email'],
            password_hash($data['mot_de_passe'], PASSWORD_DEFAULT),
            $data['telephone'],
            $data['role'] ?? 'employe'
        ]);
    }

    /**
     * Met a jour un utilisateur
     */
    public function update($id, $data)
    {
        $stmt = $this->db->prepare('
            UPDATE utilisateurs
            SET nom = ?, prenom = ?, email = ?, telephone = ?
            WHERE id = ?
        ');
        return $stmt->execute([
            $data['nom'],
            $data['prenom'],
            $data['email'],
            $data['telephone'],
            $id
        ]);
    }

    /**
     * Supprime un utilisateur
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM utilisateurs WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
