<?php
namespace App\Models;

use App\Database;

/**
 * Model pour les agences
 */
class Agence
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Recupere toutes les agences
     */
    public function all()
    {
        $stmt = $this->db->query('SELECT * FROM agences ORDER BY id');
        return $stmt->fetchAll();
    }

    /**
     * Trouve une agence par son ID
     */
    public function find($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM agences WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Compte le nombre d'agences
     */
    public function count()
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM agences');
        return $stmt->fetchColumn();
    }

    /**
     * Cree une nouvelle agence
     */
    public function create($data)
    {
        $stmt = $this->db->prepare('INSERT INTO agences (nom_ville) VALUES (?)');
        return $stmt->execute([$data['nom_ville']]);
    }

    /**
     * Met a jour une agence
     */
    public function update($id, $data)
    {
        $stmt = $this->db->prepare('UPDATE agences SET nom_ville = ? WHERE id = ?');
        return $stmt->execute([$data['nom_ville'], $id]);
    }

    /**
     * Supprime une agence
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM agences WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
