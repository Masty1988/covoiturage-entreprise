<?php
namespace App\Controllers;

use App\Models\Trajet;
use App\Models\Agence;

/**
 * Gestion des trajets
 */
class TrajetController extends Controller
{
    private $modelTrajet;
    private $modelAgence;

    public function __construct()
    {
        $this->modelTrajet = new Trajet();
        $this->modelAgence = new Agence();
    }

    /**
     * Liste des trajets disponibles (page d'accueil)
     */
    public function index()
    {
        $trajets = $this->modelTrajet->all();

        // Garde que les trajets avec places dispo et pas encore passes
        $trajets = array_filter($trajets, function($item) {
            return $item['places_disponibles'] > 0 && strtotime($item['date_heure_depart']) > time();
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

        $listeAgences = $this->modelAgence->all();

        $this->view('trajets/create', [
            'agences' => $listeAgences,
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

        $depart = $_POST['agence_depart_id'] ?? '';
        $arrivee = $_POST['agence_arrivee_id'] ?? '';
        $dateDepart = $_POST['date_heure_depart'] ?? '';
        $dateArrivee = $_POST['date_heure_arrivee'] ?? '';
        $nbPlaces = $_POST['places_totales'] ?? '';

        // Validations
        if ($depart == $arrivee) {
            $this->setFlash('error', 'Le depart et l\'arrivee doivent etre differents');
            $this->redirect('/trajets/create');
        }

        if (strtotime($dateArrivee) <= strtotime($dateDepart)) {
            $this->setFlash('error', 'La date d\'arrivee doit etre apres le depart');
            $this->redirect('/trajets/create');
        }

        // Enregistrement
        $this->modelTrajet->create([
            'conducteur_id' => $this->getUser()['id'],
            'agence_depart_id' => $depart,
            'agence_arrivee_id' => $arrivee,
            'date_heure_depart' => $dateDepart,
            'date_heure_arrivee' => $dateArrivee,
            'places_totales' => $nbPlaces
        ]);

        $this->setFlash('success', 'Trajet cree avec succes');
        $this->redirect('/');
    }

    /**
     * Formulaire de modification
     */
    public function edit($trajetId)
    {
        if (!$this->isLogged()) {
            $this->redirect('/login');
        }

        $trajet = $this->modelTrajet->find($trajetId);

        if (!$trajet) {
            $this->setFlash('error', 'Trajet non trouve');
            $this->redirect('/');
        }

        // Check si c'est bien le conducteur
        $currentUser = $this->getUser();
        if ($trajet['conducteur_id'] != $currentUser['id'] && !$this->isAdmin()) {
            $this->setFlash('error', 'Vous ne pouvez pas modifier ce trajet');
            $this->redirect('/');
        }

        $listeAgences = $this->modelAgence->all();

        $this->view('trajets/edit', [
            'trajet' => $trajet,
            'agences' => $listeAgences,
            'user' => $currentUser
        ]);
    }

    /**
     * Met a jour un trajet
     */
    public function update($trajetId)
    {
        if (!$this->isLogged()) {
            $this->redirect('/login');
        }

        $trajet = $this->modelTrajet->find($trajetId);
        $user = $this->getUser();

        if (!$trajet || ($trajet['conducteur_id'] != $user['id'] && !$this->isAdmin())) {
            $this->setFlash('error', 'Action non autorisee');
            $this->redirect('/');
        }

        $depart = $_POST['agence_depart_id'] ?? '';
        $arrivee = $_POST['agence_arrivee_id'] ?? '';
        $dateDepart = $_POST['date_heure_depart'] ?? '';
        $dateArrivee = $_POST['date_heure_arrivee'] ?? '';
        $placesTotal = $_POST['places_totales'] ?? '';
        $placesDispo = $_POST['places_disponibles'] ?? '';

        if ($depart == $arrivee) {
            $this->setFlash('error', 'Le depart et l\'arrivee doivent etre differents');
            $this->redirect('/trajets/' . $trajetId . '/edit');
        }

        $this->modelTrajet->update($trajetId, [
            'agence_depart_id' => $depart,
            'agence_arrivee_id' => $arrivee,
            'date_heure_depart' => $dateDepart,
            'date_heure_arrivee' => $dateArrivee,
            'places_totales' => $placesTotal,
            'places_disponibles' => $placesDispo
        ]);

        $this->setFlash('success', 'Trajet modifie');
        $this->redirect('/');
    }

    /**
     * Supprime un trajet
     */
    public function delete($trajetId)
    {
        if (!$this->isLogged()) {
            $this->redirect('/login');
        }

        $trajet = $this->modelTrajet->find($trajetId);
        $user = $this->getUser();

        if (!$trajet || ($trajet['conducteur_id'] != $user['id'] && !$this->isAdmin())) {
            $this->setFlash('error', 'Action non autorisee');
            $this->redirect('/');
        }

        $this->modelTrajet->delete($trajetId);

        $this->setFlash('success', 'Trajet supprime');
        $this->redirect('/');
    }
}
