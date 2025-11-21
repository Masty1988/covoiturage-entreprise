<?php
namespace App\Controllers;

use App\Models\User;

/**
 * Gestion de l'authentification
 */
class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
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

        // Recherche l'utilisateur via le model
        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['mot_de_passe'])) {
            $this->setFlash('error', 'Email ou mot de passe incorrect');
            $this->redirect('/login');
        }

        // Connexion reussie - stocke en session
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
     * Deconnexion
     */
    public function logout()
    {
        session_destroy();
        $this->redirect('/');
    }
}
