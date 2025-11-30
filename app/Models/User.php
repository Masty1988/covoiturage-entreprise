<?php
namespace App\Models;

use App\Database;

/**
 * Modele pour gerer les utilisateurs
 * Operations CRUD sur la table utilisateurs
 */
class User
{
    /**
     * Connexion PDO
     * @var \PDO
     */
    private $db;

    /**
     * Initialise la connexion DB
     */
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Recherche par email
     *
     * @param string $email Email de l'utilisateur
     * @return array|false Donnees utilisateur ou false
     */
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare('SELECT * FROM utilisateurs WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Recherche par ID
     *
     * @param int $id ID utilisateur
     * @return array|false Donnees ou false
     */
    public function find($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM utilisateurs WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Liste tous les utilisateurs
     *
     * @return array Utilisateurs tries par ID
     */
    public function all()
    {
        $stmt = $this->db->query('SELECT * FROM utilisateurs ORDER BY id');
        return $stmt->fetchAll();
    }

    /**
     * Compte les utilisateurs
     *
     * @return int Nombre total
     */
    public function count()
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM utilisateurs');
        return $stmt->fetchColumn();
    }

    /**
     * Cree un utilisateur
     * Le mdp est hashe automatiquement avec bcrypt
     * Role par defaut: employe
     *
     * @param array $data nom, prenom, email, mot_de_passe, telephone, role (optionnel)
     * @return bool Success
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
     * Modifie un utilisateur
     * Note: le mdp n'est pas modifiable ici
     *
     * @param int $id ID utilisateur
     * @param array $data nom, prenom, email, telephone
     * @return bool Success
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
     * ATTENTION: supprime aussi ses trajets (CASCADE)
     *
     * @param int $id ID utilisateur
     * @return bool Success
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM utilisateurs WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
