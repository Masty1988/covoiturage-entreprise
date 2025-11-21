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
    private $userModel;
    private $trajetModel;
    private $agenceModel;
    private $db;

    public function __construct()
    {
        $this->userModel = new User();
        $this->trajetModel = new Trajet();
        $this->agenceModel = new Agence();
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Verifie que l'utilisateur est admin
     */
    private function checkAdmin()
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
        $this->checkAdmin();

        $stats = [
            'users' => $this->userModel->count(),
            'agences' => $this->agenceModel->count(),
            'trajets' => $this->trajetModel->count()
        ];

        $this->view('admin/dashboard', [
            'stats' => $stats,
            'user' => $this->getUser()
        ]);
    }

    /**
     * Liste des utilisateurs
     */
    public function users()
    {
        $this->checkAdmin();

        $users = $this->userModel->all();

        $this->view('admin/users', [
            'users' => $users,
            'user' => $this->getUser()
        ]);
    }

    /**
     * Liste des agences
     */
    public function agences()
    {
        $this->checkAdmin();

        $agences = $this->agenceModel->all();

        $this->view('admin/agences', [
            'agences' => $agences,
            'user' => $this->getUser()
        ]);
    }

    /**
     * Formulaire creation agence
     */
    public function createAgence()
    {
        $this->checkAdmin();

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
        $this->checkAdmin();

        $nomVille = trim($_POST['nom_ville'] ?? '');

        if (empty($nomVille)) {
            $this->setFlash('error', 'Le nom de la ville est obligatoire');
            $this->redirect('/admin/agences/create');
        }

        // Verifie si existe deja
        $stmt = $this->db->prepare("SELECT id FROM agences WHERE nom_ville = ?");
        $stmt->execute([$nomVille]);
        if ($stmt->fetch()) {
            $this->setFlash('error', 'Cette ville existe deja');
            $this->redirect('/admin/agences/create');
        }

        $this->agenceModel->create(['nom_ville' => $nomVille]);

        $this->setFlash('success', 'Agence ajoutee');
        $this->redirect('/admin/agences');
    }

    /**
     * Formulaire modification agence
     */
    public function editAgence($id)
    {
        $this->checkAdmin();

        $agence = $this->agenceModel->find($id);

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
    public function updateAgence($id)
    {
        $this->checkAdmin();

        $nomVille = trim($_POST['nom_ville'] ?? '');

        if (empty($nomVille)) {
            $this->setFlash('error', 'Le nom de la ville est obligatoire');
            $this->redirect('/admin/agences/' . $id . '/edit');
        }

        // Verifie si existe deja (autre que celle-ci)
        $stmt = $this->db->prepare("SELECT id FROM agences WHERE nom_ville = ? AND id != ?");
        $stmt->execute([$nomVille, $id]);
        if ($stmt->fetch()) {
            $this->setFlash('error', 'Cette ville existe deja');
            $this->redirect('/admin/agences/' . $id . '/edit');
        }

        $this->agenceModel->update($id, ['nom_ville' => $nomVille]);

        $this->setFlash('success', 'Agence modifiee');
        $this->redirect('/admin/agences');
    }

    /**
     * Supprime une agence
     */
    public function deleteAgence($id)
    {
        $this->checkAdmin();

        // Verifie si des trajets utilisent cette agence
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM trajets WHERE agence_depart_id = ? OR agence_arrivee_id = ?");
        $stmt->execute([$id, $id]);
        $count = $stmt->fetch()['total'];

        if ($count > 0) {
            $this->setFlash('error', 'Impossible de supprimer : ' . $count . ' trajet(s) utilisent cette agence');
            $this->redirect('/admin/agences');
        }

        $this->agenceModel->delete($id);

        $this->setFlash('success', 'Agence supprimee');
        $this->redirect('/admin/agences');
    }

    /**
     * Liste des trajets (admin)
     */
    public function trajets()
    {
        $this->checkAdmin();

        $trajets = $this->trajetModel->all();

        $this->view('admin/trajets', [
            'trajets' => $trajets,
            'user' => $this->getUser()
        ]);
    }

    /**
     * Supprime un trajet (admin)
     */
    public function deleteTrajet($id)
    {
        $this->checkAdmin();

        $this->trajetModel->delete($id);

        $this->setFlash('success', 'Trajet supprime');
        $this->redirect('/admin/trajets');
    }
}
