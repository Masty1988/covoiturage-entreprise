<?php
namespace App\Models;

use App\Database;

/**
 * Model pour les trajets
 */
class Trajet
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Recupere tous les trajets avec infos conducteur et villes
     */
    public function all()
    {
        $stmt = $this->db->query('
            SELECT t.*,
                   u.nom, u.prenom, u.email, u.telephone,
                   a1.nom_ville as ville_depart,
                   a2.nom_ville as ville_arrivee
            FROM trajets t
            JOIN utilisateurs u ON t.conducteur_id = u.id
            JOIN agences a1 ON t.agence_depart_id = a1.id
            JOIN agences a2 ON t.agence_arrivee_id = a2.id
            ORDER BY t.date_heure_depart ASC
        ');
        return $stmt->fetchAll();
    }

    /**
     * Trouve un trajet par son ID
     */
    public function find($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM trajets WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Compte le nombre de trajets
     */
    public function count()
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM trajets');
        return $stmt->fetchColumn();
    }

    /**
     * Cree un nouveau trajet
     */
    public function create($data)
    {
        $stmt = $this->db->prepare('
            INSERT INTO trajets (conducteur_id, agence_depart_id, agence_arrivee_id,
                                date_heure_depart, date_heure_arrivee, places_totales, places_disponibles)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');
        return $stmt->execute([
            $data['conducteur_id'],
            $data['agence_depart_id'],
            $data['agence_arrivee_id'],
            $data['date_heure_depart'],
            $data['date_heure_arrivee'],
            $data['places_totales'],
            $data['places_totales'] // places dispo = places totales au debut
        ]);
    }

    /**
     * Met a jour un trajet
     */
    public function update($id, $data)
    {
        $stmt = $this->db->prepare('
            UPDATE trajets
            SET agence_depart_id = ?, agence_arrivee_id = ?,
                date_heure_depart = ?, date_heure_arrivee = ?,
                places_totales = ?, places_disponibles = ?
            WHERE id = ?
        ');
        return $stmt->execute([
            $data['agence_depart_id'],
            $data['agence_arrivee_id'],
            $data['date_heure_depart'],
            $data['date_heure_arrivee'],
            $data['places_totales'],
            $data['places_disponibles'],
            $id
        ]);
    }

    /**
     * Supprime un trajet
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM trajets WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
