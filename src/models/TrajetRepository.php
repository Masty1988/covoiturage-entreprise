<?php

namespace App\Models;

use App\Core\Model;

/**
 * Repository Trajet - Gestion des trajets en base de données
 * 
 * @package App\Models
 */
class TrajetRepository extends Model
{
    /**
     * Récupère tous les trajets disponibles (futurs avec places dispo)
     * 
     * @return array
     */
    public function findAllAvailable(): array
    {
        $sql = "SELECT 
                    t.*,
                    ad.nom as agence_depart,
                    aa.nom as agence_arrivee,
                    CONCAT(u.prenom, ' ', u.nom) as auteur_nom,
                    u.telephone as auteur_telephone,
                    u.email as auteur_email
                FROM trajets t
                INNER JOIN agences ad ON t.agence_depart_id = ad.id
                INNER JOIN agences aa ON t.agence_arrivee_id = aa.id
                INNER JOIN users u ON t.user_id = u.id
                WHERE t.places_disponibles > 0 
                AND t.date_depart > NOW()
                ORDER BY t.date_depart ASC";

        return $this->fetchAll($sql);
    }

    /**
     * Récupère un trajet par son ID
     * 
     * @param int $id ID du trajet
     * @return array|false
     */
    public function findById(int $id)
    {
        $sql = "SELECT 
                    t.*,
                    ad.nom as agence_depart,
                    aa.nom as agence_arrivee,
                    CONCAT(u.prenom, ' ', u.nom) as auteur_nom,
                    u.telephone as auteur_telephone,
                    u.email as auteur_email
                FROM trajets t
                INNER JOIN agences ad ON t.agence_depart_id = ad.id
                INNER JOIN agences aa ON t.agence_arrivee_id = aa.id
                INNER JOIN users u ON t.user_id = u.id
                WHERE t.id = :id";

        return $this->fetchOne($sql, ['id' => $id]);
    }

    /**
     * Récupère les trajets d'un utilisateur
     * 
     * @param int $userId ID de l'utilisateur
     * @return array
     */
    public function findByUser(int $userId): array
    {
        $sql = "SELECT 
                    t.*,
                    ad.nom as agence_depart,
                    aa.nom as agence_arrivee
                FROM trajets t
                INNER JOIN agences ad ON t.agence_depart_id = ad.id
                INNER JOIN agences aa ON t.agence_arrivee_id = aa.id
                WHERE t.user_id = :user_id
                ORDER BY t.date_depart DESC";

        return $this->fetchAll($sql, ['user_id' => $userId]);
    }

    /**
     * Crée un nouveau trajet
     * 
     * @param array $data Données du trajet
     * @return int ID du trajet créé
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO trajets 
                (agence_depart_id, agence_arrivee_id, date_depart, date_arrivee, 
                 places_total, places_disponibles, user_id)
                VALUES 
                (:agence_depart_id, :agence_arrivee_id, :date_depart, :date_arrivee,
                 :places_total, :places_disponibles, :user_id)";

        $this->query($sql, $data);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Met à jour un trajet
     * 
     * @param int $id ID du trajet
     * @param array $data Données à mettre à jour
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE trajets SET
                agence_depart_id = :agence_depart_id,
                agence_arrivee_id = :agence_arrivee_id,
                date_depart = :date_depart,
                date_arrivee = :date_arrivee,
                places_total = :places_total,
                places_disponibles = :places_disponibles
                WHERE id = :id";

        $data['id'] = $id;
        $this->query($sql, $data);
        return true;
    }

    /**
     * Supprime un trajet
     * 
     * @param int $id ID du trajet
     * @return bool
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM trajets WHERE id = :id";
        $this->query($sql, ['id' => $id]);
        return true;
    }
}