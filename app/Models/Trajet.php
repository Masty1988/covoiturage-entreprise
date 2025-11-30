<?php
namespace App\Models;

use App\Database;

/**
 * Modele pour les trajets de covoiturage
 * Gere les deplacements proposes entre agences
 */
class Trajet
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
     * Recupere tous les trajets avec les infos completes
     * Fait des jointures pour avoir conducteur + agences
     *
     * @return array Trajets avec infos enrichies
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
     * Recherche par ID
     *
     * @param int $id ID du trajet
     * @return array|false Donnees ou false
     */
    public function find($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM trajets WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Compte les trajets
     *
     * @return int Total
     */
    public function count()
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM trajets');
        return $stmt->fetchColumn();
    }

    /**
     * Cree un trajet
     * Places dispo initialisees automatiquement avec places totales
     *
     * @param array $data conducteur_id, agence_depart_id, agence_arrivee_id, date_heure_depart, date_heure_arrivee, places_totales
     * @return bool Success
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
     * Modifie un trajet
     *
     * @param int $id ID trajet
     * @param array $data agence_depart_id, agence_arrivee_id, date_heure_depart, date_heure_arrivee, places_totales, places_disponibles
     * @return bool Success
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
     *
     * @param int $id ID trajet
     * @return bool Success
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM trajets WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
