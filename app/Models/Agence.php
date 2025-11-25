<?php
namespace App\Models;

use App\Database;

/**
 * Model pour les agences
 *
 * Gere les operations CRUD sur la table agences de la base de donnees.
 * Les agences representent les differents sites geographiques de l'entreprise.
 */
class Agence
{
    /**
     * Instance de connexion PDO a la base de donnees
     * @var \PDO
     */
    private $db;

    /**
     * Constructeur du modele Agence
     *
     * Initialise la connexion a la base de donnees via le singleton Database
     */
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Recupere toutes les agences de la base de donnees
     *
     * @return array Tableau contenant toutes les agences triees par ID
     */
    public function all()
    {
        $stmt = $this->db->query('SELECT * FROM agences ORDER BY id');
        return $stmt->fetchAll();
    }

    /**
     * Trouve une agence par son identifiant
     *
     * @param int $id L'identifiant de l'agence a rechercher
     * @return array|false Les donnees de l'agence ou false si non trouvee
     */
    public function find($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM agences WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Compte le nombre total d'agences dans la base
     *
     * @return int Le nombre d'agences
     */
    public function count()
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM agences');
        return $stmt->fetchColumn();
    }

    /**
     * Cree une nouvelle agence dans la base de donnees
     *
     * @param array $data Tableau associatif contenant les donnees de l'agence
     *                    Cle requise: nom_ville
     * @return bool True si la creation reussit, false sinon
     */
    public function create($data)
    {
        $stmt = $this->db->prepare('INSERT INTO agences (nom_ville) VALUES (?)');
        return $stmt->execute([$data['nom_ville']]);
    }

    /**
     * Met a jour les informations d'une agence existante
     *
     * @param int $id L'identifiant de l'agence a modifier
     * @param array $data Tableau associatif contenant les nouvelles donnees
     *                    Cle requise: nom_ville
     * @return bool True si la mise a jour reussit, false sinon
     */
    public function update($id, $data)
    {
        $stmt = $this->db->prepare('UPDATE agences SET nom_ville = ? WHERE id = ?');
        return $stmt->execute([$data['nom_ville'], $id]);
    }

    /**
     * Supprime une agence de la base de donnees
     *
     * Attention: Cette action supprimera egalement tous les trajets ayant
     * cette agence comme depart ou arrivee en raison de la contrainte ON DELETE CASCADE
     *
     * @param int $id L'identifiant de l'agence a supprimer
     * @return bool True si la suppression reussit, false sinon
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM agences WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
