<?php
namespace App\Models;

use App\Database;

/**
 * Model pour les utilisateurs
 *
 * Gere les operations CRUD sur la table utilisateurs de la base de donnees.
 * Permet de recuperer, creer, modifier et supprimer des utilisateurs.
 */
class User
{
    /**
     * Instance de connexion PDO a la base de donnees
     * @var \PDO
     */
    private $db;

    /**
     * Constructeur du modele User
     *
     * Initialise la connexion a la base de donnees via le singleton Database
     */
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Trouve un utilisateur par son adresse email
     *
     * @param string $email L'adresse email de l'utilisateur a rechercher
     * @return array|false Les donnees de l'utilisateur ou false si non trouve
     */
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare('SELECT * FROM utilisateurs WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Trouve un utilisateur par son identifiant
     *
     * @param int $id L'identifiant de l'utilisateur
     * @return array|false Les donnees de l'utilisateur ou false si non trouve
     */
    public function find($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM utilisateurs WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Recupere tous les utilisateurs de la base de donnees
     *
     * @return array Tableau contenant tous les utilisateurs tries par ID
     */
    public function all()
    {
        $stmt = $this->db->query('SELECT * FROM utilisateurs ORDER BY id');
        return $stmt->fetchAll();
    }

    /**
     * Compte le nombre total d'utilisateurs dans la base
     *
     * @return int Le nombre d'utilisateurs
     */
    public function count()
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM utilisateurs');
        return $stmt->fetchColumn();
    }

    /**
     * Cree un nouvel utilisateur dans la base de donnees
     *
     * Le mot de passe fourni est automatiquement hashe avec bcrypt.
     * Si le role n'est pas specifie, l'utilisateur est cree avec le role 'employe'.
     *
     * @param array $data Tableau associatif contenant les donnees de l'utilisateur
     *                    Cles requises: nom, prenom, email, mot_de_passe, telephone
     *                    Cle optionnelle: role (par defaut 'employe')
     * @return bool True si la creation reussit, false sinon
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
     * Met a jour les informations d'un utilisateur existant
     *
     * Note: Le mot de passe n'est pas modifiable via cette methode
     *
     * @param int $id L'identifiant de l'utilisateur a modifier
     * @param array $data Tableau associatif contenant les nouvelles donnees
     *                    Cles: nom, prenom, email, telephone
     * @return bool True si la mise a jour reussit, false sinon
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
     * Supprime un utilisateur de la base de donnees
     *
     * Attention: Cette action supprimera egalement tous les trajets associes
     * a cet utilisateur en raison de la contrainte ON DELETE CASCADE
     *
     * @param int $id L'identifiant de l'utilisateur a supprimer
     * @return bool True si la suppression reussit, false sinon
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM utilisateurs WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
