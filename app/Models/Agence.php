<?php
namespace App\Models;

use App\Database;

/**
 * Modele pour les agences
 * Represente les differents sites de l'entreprise
 */
class Agence
{
    /**
     * Connexion PDO
     * @var \PDO
     */
    private $db;

    /**
     * Init connexion
     */
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Liste toutes les agences
     *
     * @return array Agences triees par ID
     */
    public function all()
    {
        $stmt = $this->db->query('SELECT * FROM agences ORDER BY id');
        return $stmt->fetchAll();
    }

    /**
     * Recherche par ID
     *
     * @param int $id ID agence
     * @return array|false Donnees ou false
     */
    public function find($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM agences WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Compte les agences
     *
     * @return int Total
     */
    public function count()
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM agences');
        return $stmt->fetchColumn();
    }

    /**
     * Cree une agence
     *
     * @param array $data nom_ville
     * @return bool Success
     */
    public function create($data)
    {
        $stmt = $this->db->prepare('INSERT INTO agences (nom_ville) VALUES (?)');
        return $stmt->execute([$data['nom_ville']]);
    }

    /**
     * Modifie une agence
     *
     * @param int $id ID agence
     * @param array $data nom_ville
     * @return bool Success
     */
    public function update($id, $data)
    {
        $stmt = $this->db->prepare('UPDATE agences SET nom_ville = ? WHERE id = ?');
        return $stmt->execute([$data['nom_ville'], $id]);
    }

    /**
     * Supprime une agence
     * ATTENTION: supprime aussi les trajets associes (CASCADE)
     *
     * @param int $id ID agence
     * @return bool Success
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM agences WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
