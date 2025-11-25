<?php
namespace App\Controllers;

/**
 * Controller de base
 *
 * Classe abstraite dont heritent tous les autres controllers de l'application.
 * Fournit des methodes utilitaires pour la gestion des vues, redirections,
 * messages flash et verification des permissions utilisateur.
 */
class Controller
{
    /**
     * Affiche une vue avec des donnees
     *
     * Cette methode charge un fichier de vue et lui transmet des donnees.
     * Les donnees sont extraites dans le scope de la vue pour un acces direct.
     *
     * @param string $name Nom de la vue a afficher (chemin relatif depuis views/)
     * @param array $data Tableau associatif de donnees a passer a la vue
     * @return void
     */
    protected function view($name, $data = [])
    {
        // Extrait les variables pour les rendre accessibles dans la vue
        extract($data);

        // Chemin vers la vue
        $viewPath = __DIR__ . '/../../views/' . $name . '.php';

        if (!file_exists($viewPath)) {
            die('Vue non trouvée : ' . $name);
        }

        require $viewPath;
    }

    /**
     * Redirige vers une URL interne de l'application
     *
     * @param string $url URL relative a rediriger (ex: '/login', '/admin')
     * @return void
     */
    protected function redirect($url)
    {
        header('Location: ' . BASE_URL . $url);
        exit;
    }

    /**
     * Stocke un message flash en session
     *
     * Les messages flash sont affiches une seule fois apres une redirection,
     * puis automatiquement supprimes.
     *
     * @param string $type Type de message ('success' ou 'error')
     * @param string $message Contenu du message a afficher
     * @return void
     */
    protected function setFlash($type, $message)
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /**
     * Verifie si un utilisateur est connecte
     *
     * @return bool True si un utilisateur est connecte, false sinon
     */
    protected function isLogged()
    {
        return isset($_SESSION['user']);
    }

    /**
     * Verifie si l'utilisateur connecte a le role administrateur
     *
     * @return bool True si l'utilisateur est admin, false sinon
     */
    protected function isAdmin()
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }

    /**
     * Recupere les informations de l'utilisateur actuellement connecte
     *
     * @return array|null Tableau des donnees utilisateur ou null si non connecte
     */
    protected function getUser()
    {
        return $_SESSION['user'] ?? null;
    }
}
