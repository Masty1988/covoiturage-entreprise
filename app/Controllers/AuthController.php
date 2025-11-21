<?php
namespace App\Controllers;

use App\Database;
use PDO;

/**
 * Gestion de l'authentification
 */
class AuthController extends Controller
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Affiche le formulaire de connexion
     */
    public function showLogin()
    {
        if ($this->isLogged()) {
            $this->redirect('/');
        }

        $this->view('auth/login');
    }

    /**
     * Traite la connexion
     */
    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->setFlash('error', 'Veuillez remplir tous les champs');
            $this->redirect('/login');
        }

        // Recherche l'utilisateur
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['mot_de_passe'])) {
            $this->setFlash('error', 'Email ou mot de passe incorrect');
            $this->redirect('/login');
        }

        // Connexion réussie - stocke en session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nom' => $user['nom'],
            'prenom' => $user['prenom'],
            'email' => $user['email'],
            'telephone' => $user['telephone'],
            'role' => $user['role']
        ];

        $this->setFlash('success', 'Bienvenue ' . $user['prenom'] . ' !');

        // Redirige vers admin si admin, sinon accueil
        if ($user['role'] === 'admin') {
            $this->redirect('/admin');
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        session_destroy();
        $this->redirect('/');
    }
}
