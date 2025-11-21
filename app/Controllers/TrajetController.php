<?php
namespace App\Controllers;

use App\Database;
use PDO;

/**
 * Gestion des trajets
 */
class TrajetController extends Controller
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Liste des trajets disponibles (page d'accueil)
     */
    public function index()
    {
        // Récupère les trajets avec places dispo et date future
        $sql = "SELECT t.*,
                    dep.nom_ville as ville_depart,
                    arr.nom_ville as ville_arrivee,
                    u.nom, u.prenom, u.email, u.telephone
                FROM trajets t
                JOIN agences dep ON t.agence_depart_id = dep.id
                JOIN agences arr ON t.agence_arrivee_id = arr.id
                JOIN utilisateurs u ON t.conducteur_id = u.id
                WHERE t.places_disponibles > 0
                AND t.date_heure_depart > NOW()
                ORDER BY t.date_heure_depart ASC";

        $stmt = $this->db->query($sql);
        $trajets = $stmt->fetchAll();

        $this->view('trajets/index', [
            'trajets' => $trajets,
            'user' => $this->getUser()
        ]);
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        if (!$this->isLogged()) {
            $this->setFlash('error', 'Vous devez être connecté');
            $this->redirect('/login');
        }

        // Liste des agences pour le select
        $stmt = $this->db->query("SELECT * FROM agences ORDER BY nom_ville");
        $agences = $stmt->fetchAll();

        $this->view('trajets/create', [
            'agences' => $agences,
            'user' => $this->getUser()
        ]);
    }

    /**
     * Enregistre un nouveau trajet
     */
    public function store()
    {
        if (!$this->isLogged()) {
            $this->redirect('/login');
        }

        // Récupération des données du formulaire
        $agenceDepart = $_POST['agence_depart_id'] ?? '';
        $agenceArrivee = $_POST['agence_arrivee_id'] ?? '';
        $dateDepart = $_POST['date_heure_depart'] ?? '';
        $dateArrivee = $_POST['date_heure_arrivee'] ?? '';
        $places = $_POST['places_totales'] ?? '';

        // Validations basiques
        if ($agenceDepart == $agenceArrivee) {
            $this->setFlash('error', 'Le départ et l\'arrivée doivent être différents');
            $this->redirect('/trajets/create');
        }

        if (strtotime($dateArrivee) <= strtotime($dateDepart)) {
            $this->setFlash('error', 'La date d\'arrivée doit être après le départ');
            $this->redirect('/trajets/create');
        }

        // Insertion en base
        $sql = "INSERT INTO trajets (agence_depart_id, agence_arrivee_id, date_heure_depart,
                date_heure_arrivee, places_totales, places_disponibles, conducteur_id)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $agenceDepart,
            $agenceArrivee,
            $dateDepart,
            $dateArrivee,
            $places,
            $places, // places dispo = places totales au début
            $this->getUser()['id']
        ]);

        $this->setFlash('success', 'Trajet créé avec succès');
        $this->redirect('/');
    }

    /**
     * Formulaire de modification
     */
    public function edit($id)
    {
        if (!$this->isLogged()) {
            $this->redirect('/login');
        }

        // Récupère le trajet
        $stmt = $this->db->prepare("SELECT * FROM trajets WHERE id = ?");
        $stmt->execute([$id]);
        $trajet = $stmt->fetch();

        if (!$trajet) {
            $this->setFlash('error', 'Trajet non trouvé');
            $this->redirect('/');
        }

        // Vérifie que c'est bien l'auteur (ou admin)
        if ($trajet['conducteur_id'] != $this->getUser()['id'] && !$this->isAdmin()) {
            $this->setFlash('error', 'Vous ne pouvez pas modifier ce trajet');
            $this->redirect('/');
        }

        // Liste des agences
        $stmt = $this->db->query("SELECT * FROM agences ORDER BY nom_ville");
        $agences = $stmt->fetchAll();

        $this->view('trajets/edit', [
            'trajet' => $trajet,
            'agences' => $agences,
            'user' => $this->getUser()
        ]);
    }

    /**
     * Met à jour un trajet
     */
    public function update($id)
    {
        if (!$this->isLogged()) {
            $this->redirect('/login');
        }

        // Vérifie les droits
        $stmt = $this->db->prepare("SELECT * FROM trajets WHERE id = ?");
        $stmt->execute([$id]);
        $trajet = $stmt->fetch();

        if (!$trajet || ($trajet['conducteur_id'] != $this->getUser()['id'] && !$this->isAdmin())) {
            $this->setFlash('error', 'Action non autorisée');
            $this->redirect('/');
        }

        // Récupération des données
        $agenceDepart = $_POST['agence_depart_id'] ?? '';
        $agenceArrivee = $_POST['agence_arrivee_id'] ?? '';
        $dateDepart = $_POST['date_heure_depart'] ?? '';
        $dateArrivee = $_POST['date_heure_arrivee'] ?? '';
        $placesTotales = $_POST['places_totales'] ?? '';
        $placesDispo = $_POST['places_disponibles'] ?? '';

        // Validations
        if ($agenceDepart == $agenceArrivee) {
            $this->setFlash('error', 'Le départ et l\'arrivée doivent être différents');
            $this->redirect('/trajets/' . $id . '/edit');
        }

        // Mise à jour
        $sql = "UPDATE trajets SET
                agence_depart_id = ?,
                agence_arrivee_id = ?,
                date_heure_depart = ?,
                date_heure_arrivee = ?,
                places_totales = ?,
                places_disponibles = ?
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $agenceDepart,
            $agenceArrivee,
            $dateDepart,
            $dateArrivee,
            $placesTotales,
            $placesDispo,
            $id
        ]);

        $this->setFlash('success', 'Trajet modifié');
        $this->redirect('/');
    }

    /**
     * Supprime un trajet
     */
    public function delete($id)
    {
        if (!$this->isLogged()) {
            $this->redirect('/login');
        }

        // Vérifie les droits
        $stmt = $this->db->prepare("SELECT * FROM trajets WHERE id = ?");
        $stmt->execute([$id]);
        $trajet = $stmt->fetch();

        if (!$trajet || ($trajet['conducteur_id'] != $this->getUser()['id'] && !$this->isAdmin())) {
            $this->setFlash('error', 'Action non autorisée');
            $this->redirect('/');
        }

        $stmt = $this->db->prepare("DELETE FROM trajets WHERE id = ?");
        $stmt->execute([$id]);

        $this->setFlash('success', 'Trajet supprimé');
        $this->redirect('/');
    }
}
