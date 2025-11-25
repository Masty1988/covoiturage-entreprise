<?php
namespace App\Models;

use App\Database;

/**
 * Model pour les trajets
 *
 * Gere les operations CRUD sur la table trajets de la base de donnees.
 * Les trajets representent les deplacements proposes par les employes entre agences.
 */
class Trajet
{
    /**
     * Instance de connexion PDO a la base de donnees
     * @var \PDO
     */
    private $db;

    /**
     * Constructeur du modele Trajet
     *
     * Initialise la connexion a la base de donnees via le singleton Database
     */
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Recupere tous les trajets avec les informations completes
     *
     * Cette methode effectue des jointures pour recuperer les informations
     * du conducteur (utilisateur) et des agences de depart et d'arrivee.
     * Les trajets sont tries par date de depart croissante.
     *
     * @return array Tableau de tous les trajets avec informations enrichies
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
     * Trouve un trajet par son identifiant
     *
     * @param int $id L'identifiant du trajet a rechercher
     * @return array|false Les donnees du trajet ou false si non trouve
     */
    public function find($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM trajets WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Compte le nombre total de trajets dans la base
     *
     * @return int Le nombre de trajets
     */
    public function count()
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM trajets');
        return $stmt->fetchColumn();
    }

    /**
     * Cree un nouveau trajet dans la base de donnees
     *
     * Le nombre de places disponibles est automatiquement initialise
     * avec la meme valeur que le nombre de places totales.
     *
     * @param array $data Tableau associatif contenant les donnees du trajet
     *                    Cles requises: conducteur_id, agence_depart_id, agence_arrivee_id,
     *                                   date_heure_depart, date_heure_arrivee, places_totales
     * @return bool True si la creation reussit, false sinon
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
     * Met a jour les informations d'un trajet existant
     *
     * @param int $id L'identifiant du trajet a modifier
     * @param array $data Tableau associatif contenant les nouvelles donnees
     *                    Cles: agence_depart_id, agence_arrivee_id, date_heure_depart,
     *                          date_heure_arrivee, places_totales, places_disponibles
     * @return bool True si la mise a jour reussit, false sinon
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
     * Supprime un trajet de la base de donnees
     *
     * @param int $id L'identifiant du trajet a supprimer
     * @return bool True si la suppression reussit, false sinon
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM trajets WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
