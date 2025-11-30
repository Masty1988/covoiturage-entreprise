<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\Trajet;
use App\Models\Agence;
use App\Database;

/**
 * Gestion de la partie admin
 */
class AdminController extends Controller
{
    private $modelUsers;
    private $modelTrajets;
    private $modelAgences;
    private $connexion;

    public function __construct()
    {
        $this->modelUsers = new User();
        $this->modelTrajets = new Trajet();
        $this->modelAgences = new Agence();
        $this->connexion = Database::getInstance()->getConnection();
    }

    /**
     * Verifie que l'utilisateur est admin
     */
    private function verifierAdmin()
    {
        if (!$this->isAdmin()) {
            $this->setFlash('error', 'Acces reserve aux administrateurs');
            $this->redirect('/');
        }
    }

    /**
     * Tableau de bord admin
     */
    public function dashboard()
    {
        $this->verifierAdmin();

        $statistiques = [
            'users' => $this->modelUsers->count(),
            'agences' => $this->modelAgences->count(),
            'trajets' => $this->modelTrajets->count()
        ];

        $this->view('admin/dashboard', [
            'stats' => $statistiques,
            'user' => $this->getUser()
        ]);
    }

    /**
     * Liste des utilisateurs
     */
    public function users()
    {
        $this->verifierAdmin();

        $listeUsers = $this->modelUsers->all();

        $this->view('admin/users', [
            'users' => $listeUsers,
            'user' => $this->getUser()
        ]);
    }

    /**
     * Liste des agences
     */
    public function agences()
    {
        $this->verifierAdmin();

        $listeAgences = $this->modelAgences->all();

        $this->view('admin/agences', [
            'agences' => $listeAgences,
            'user' => $this->getUser()
        ]);
    }

    /**
     * Formulaire creation agence
     */
    public function createAgence()
    {
        $this->verifierAdmin();

        $this->view('admin/agence_form', [
            'agence' => null,
            'user' => $this->getUser()
        ]);
    }

    /**
     * Enregistre une nouvelle agence
     */
    public function storeAgence()
    {
        $this->verifierAdmin();

        $ville = trim($_POST['nom_ville'] ?? '');

        if (empty($ville)) {
            $this->setFlash('error', 'Le nom de la ville est obligatoire');
            $this->redirect('/admin/agences/create');
        }

        // Check si existe deja
        $query = $this->connexion->prepare("SELECT id FROM agences WHERE nom_ville = ?");
        $query->execute([$ville]);
        if ($query->fetch()) {
            $this->setFlash('error', 'Cette ville existe deja');
            $this->redirect('/admin/agences/create');
        }

        $this->modelAgences->create(['nom_ville' => $ville]);

        $this->setFlash('success', 'Agence ajoutee');
        $this->redirect('/admin/agences');
    }

    /**
     * Formulaire modification agence
     */
    public function editAgence($agenceId)
    {
        $this->verifierAdmin();

        $agence = $this->modelAgences->find($agenceId);

        if (!$agence) {
            $this->setFlash('error', 'Agence non trouvee');
            $this->redirect('/admin/agences');
        }

        $this->view('admin/agence_form', [
            'agence' => $agence,
            'user' => $this->getUser()
        ]);
    }

    /**
     * Met a jour une agence
     */
    public function updateAgence($agenceId)
    {
        $this->verifierAdmin();

        $ville = trim($_POST['nom_ville'] ?? '');

        if (empty($ville)) {
            $this->setFlash('error', 'Le nom de la ville est obligatoire');
            $this->redirect('/admin/agences/' . $agenceId . '/edit');
        }

        // Verifie doublon (sauf elle-meme)
        $query = $this->connexion->prepare("SELECT id FROM agences WHERE nom_ville = ? AND id != ?");
        $query->execute([$ville, $agenceId]);
        if ($query->fetch()) {
            $this->setFlash('error', 'Cette ville existe deja');
            $this->redirect('/admin/agences/' . $agenceId . '/edit');
        }

        $this->modelAgences->update($agenceId, ['nom_ville' => $ville]);

        $this->setFlash('success', 'Agence modifiee');
        $this->redirect('/admin/agences');
    }

    /**
     * Supprime une agence
     */
    public function deleteAgence($agenceId)
    {
        $this->verifierAdmin();

        // Compte trajets associes
        $query = $this->connexion->prepare("SELECT COUNT(*) as nb FROM trajets WHERE agence_depart_id = ? OR agence_arrivee_id = ?");
        $query->execute([$agenceId, $agenceId]);
        $nbTrajets = $query->fetch()['nb'];

        if ($nbTrajets > 0) {
            $this->setFlash('error', 'Impossible de supprimer : ' . $nbTrajets . ' trajet(s) utilisent cette agence');
            $this->redirect('/admin/agences');
        }

        $this->modelAgences->delete($agenceId);

        $this->setFlash('success', 'Agence supprimee');
        $this->redirect('/admin/agences');
    }

    /**
     * Liste des trajets (admin)
     */
    public function trajets()
    {
        $this->verifierAdmin();

        $listeTrajets = $this->modelTrajets->all();

        $this->view('admin/trajets', [
            'trajets' => $listeTrajets,
            'user' => $this->getUser()
        ]);
    }

    /**
     * Supprime un trajet (admin)
     */
    public function deleteTrajet($trajetId)
    {
        $this->verifierAdmin();

        $this->modelTrajets->delete($trajetId);

        $this->setFlash('success', 'Trajet supprime');
        $this->redirect('/admin/trajets');
    }
}
