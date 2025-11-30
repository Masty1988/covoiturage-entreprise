<?php
namespace App\Controllers;

use App\Models\User;

/**
 * Gestion de l'authentification
 */
class AuthController extends Controller
{
    private $modelUser;

    public function __construct()
    {
        $this->modelUser = new User();
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
        $emailUser = $_POST['email'] ?? '';
        $mdp = $_POST['password'] ?? '';

        if (empty($emailUser) || empty($mdp)) {
            $this->setFlash('error', 'Veuillez remplir tous les champs');
            $this->redirect('/login');
        }

        // Cherche l'utilisateur
        $userData = $this->modelUser->findByEmail($emailUser);

        if (!$userData || !password_verify($mdp, $userData['mot_de_passe'])) {
            $this->setFlash('error', 'Email ou mot de passe incorrect');
            $this->redirect('/login');
        }

        // OK - stocke en session
        $_SESSION['user'] = [
            'id' => $userData['id'],
            'nom' => $userData['nom'],
            'prenom' => $userData['prenom'],
            'email' => $userData['email'],
            'telephone' => $userData['telephone'],
            'role' => $userData['role']
        ];

        $this->setFlash('success', 'Bienvenue ' . $userData['prenom'] . ' !');

        // Redirection selon role
        if ($userData['role'] === 'admin') {
            $this->redirect('/admin');
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Deconnexion
     */
    public function logout()
    {
        session_destroy();
        $this->redirect('/');
    }
}
