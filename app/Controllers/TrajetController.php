<?php
namespace App\Controllers;

use App\Models\Trajet;
use App\Models\Agence;

/**
 * Gestion des trajets
 */
class TrajetController extends Controller
{
    private $trajetModel;
    private $agenceModel;

    public function __construct()
    {
        $this->trajetModel = new Trajet();
        $this->agenceModel = new Agence();
    }

    /**
     * Liste des trajets disponibles (page d'accueil)
     */
    public function index()
    {
        $trajets = $this->trajetModel->all();

        // Filtre les trajets avec places dispo et date future
        $trajets = array_filter($trajets, function($t) {
            return $t['places_disponibles'] > 0 && strtotime($t['date_heure_depart']) > time();
        });

        $this->view('trajets/index', [
            'trajets' => $trajets,
            'user' => $this->getUser()
        ]);
    }

    /**
     * Formulaire de creation
     */
    public function create()
    {
        if (!$this->isLogged()) {
            $this->setFlash('error', 'Vous devez etre connecte');
            $this->redirect('/login');
        }

        $agences = $this->agenceModel->all();

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

        $agenceDepart = $_POST['agence_depart_id'] ?? '';
        $agenceArrivee = $_POST['agence_arrivee_id'] ?? '';
        $dateDepart = $_POST['date_heure_depart'] ?? '';
        $dateArrivee = $_POST['date_heure_arrivee'] ?? '';
        $places = $_POST['places_totales'] ?? '';

        // Validations
        if ($agenceDepart == $agenceArrivee) {
            $this->setFlash('error', 'Le depart et l\'arrivee doivent etre differents');
            $this->redirect('/trajets/create');
        }

        if (strtotime($dateArrivee) <= strtotime($dateDepart)) {
            $this->setFlash('error', 'La date d\'arrivee doit etre apres le depart');
            $this->redirect('/trajets/create');
        }

        // Creation via le model
        $this->trajetModel->create([
            'conducteur_id' => $this->getUser()['id'],
            'agence_depart_id' => $agenceDepart,
            'agence_arrivee_id' => $agenceArrivee,
            'date_heure_depart' => $dateDepart,
            'date_heure_arrivee' => $dateArrivee,
            'places_totales' => $places
        ]);

        $this->setFlash('success', 'Trajet cree avec succes');
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

        $trajet = $this->trajetModel->find($id);

        if (!$trajet) {
            $this->setFlash('error', 'Trajet non trouve');
            $this->redirect('/');
        }

        // Verifie que c'est bien l'auteur
        if ($trajet['conducteur_id'] != $this->getUser()['id'] && !$this->isAdmin()) {
            $this->setFlash('error', 'Vous ne pouvez pas modifier ce trajet');
            $this->redirect('/');
        }

        $agences = $this->agenceModel->all();

        $this->view('trajets/edit', [
            'trajet' => $trajet,
            'agences' => $agences,
            'user' => $this->getUser()
        ]);
    }

    /**
     * Met a jour un trajet
     */
    public function update($id)
    {
        if (!$this->isLogged()) {
            $this->redirect('/login');
        }

        $trajet = $this->trajetModel->find($id);

        if (!$trajet || ($trajet['conducteur_id'] != $this->getUser()['id'] && !$this->isAdmin())) {
            $this->setFlash('error', 'Action non autorisee');
            $this->redirect('/');
        }

        $agenceDepart = $_POST['agence_depart_id'] ?? '';
        $agenceArrivee = $_POST['agence_arrivee_id'] ?? '';
        $dateDepart = $_POST['date_heure_depart'] ?? '';
        $dateArrivee = $_POST['date_heure_arrivee'] ?? '';
        $placesTotales = $_POST['places_totales'] ?? '';
        $placesDispo = $_POST['places_disponibles'] ?? '';

        if ($agenceDepart == $agenceArrivee) {
            $this->setFlash('error', 'Le depart et l\'arrivee doivent etre differents');
            $this->redirect('/trajets/' . $id . '/edit');
        }

        $this->trajetModel->update($id, [
            'agence_depart_id' => $agenceDepart,
            'agence_arrivee_id' => $agenceArrivee,
            'date_heure_depart' => $dateDepart,
            'date_heure_arrivee' => $dateArrivee,
            'places_totales' => $placesTotales,
            'places_disponibles' => $placesDispo
        ]);

        $this->setFlash('success', 'Trajet modifie');
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

        $trajet = $this->trajetModel->find($id);

        if (!$trajet || ($trajet['conducteur_id'] != $this->getUser()['id'] && !$this->isAdmin())) {
            $this->setFlash('error', 'Action non autorisee');
            $this->redirect('/');
        }

        $this->trajetModel->delete($id);

        $this->setFlash('success', 'Trajet supprime');
        $this->redirect('/');
    }
}
