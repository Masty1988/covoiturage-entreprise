<?php
namespace App\Controllers;

use App\Database;
use PDO;

/**
 * Gestion de la partie admin
 */
class AdminController extends Controller
{
    private $db;

    public function __construct()
    {
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

        // Stats rapides
        $stats = [];

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM utilisateurs");
        $stats['users'] = $stmt->fetch()['total'];

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM agences");
        $stats['agences'] = $stmt->fetch()['total'];

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM trajets");
        $stats['trajets'] = $stmt->fetch()['total'];

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

        $stmt = $this->db->query("SELECT * FROM utilisateurs ORDER BY nom, prenom");
        $users = $stmt->fetchAll();

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

        $stmt = $this->db->query("SELECT * FROM agences ORDER BY nom_ville");
        $agences = $stmt->fetchAll();

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

        $stmt = $this->db->prepare("INSERT INTO agences (nom_ville) VALUES (?)");
        $stmt->execute([$nomVille]);

        $this->setFlash('success', 'Agence ajoutee avec succes');
        $this->redirect('/admin/agences');
    }

    /**
     * Formulaire modification agence
     */
    public function editAgence($id)
    {
        $this->checkAdmin();

        $stmt = $this->db->prepare("SELECT * FROM agences WHERE id = ?");
        $stmt->execute([$id]);
        $agence = $stmt->fetch();

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

        $stmt = $this->db->prepare("UPDATE agences SET nom_ville = ? WHERE id = ?");
        $stmt->execute([$nomVille, $id]);

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

        $stmt = $this->db->prepare("DELETE FROM agences WHERE id = ?");
        $stmt->execute([$id]);

        $this->setFlash('success', 'Agence supprimee');
        $this->redirect('/admin/agences');
    }

    /**
     * Liste des trajets (admin)
     */
    public function trajets()
    {
        $this->checkAdmin();

        $sql = "SELECT t.*,
                    dep.nom_ville as ville_depart,
                    arr.nom_ville as ville_arrivee,
                    u.nom, u.prenom
                FROM trajets t
                JOIN agences dep ON t.agence_depart_id = dep.id
                JOIN agences arr ON t.agence_arrivee_id = arr.id
                JOIN utilisateurs u ON t.conducteur_id = u.id
                ORDER BY t.date_heure_depart DESC";

        $stmt = $this->db->query($sql);
        $trajets = $stmt->fetchAll();

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

        $stmt = $this->db->prepare("DELETE FROM trajets WHERE id = ?");
        $stmt->execute([$id]);

        $this->setFlash('success', 'Trajet supprime');
        $this->redirect('/admin/trajets');
    }
}
