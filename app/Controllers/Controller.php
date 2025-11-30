<?php
namespace App\Controllers;

/**
 * Controller de base
 * Fournit des methodes utiles pour tous les controllers
 */
class Controller
{
    /**
     * Affiche une vue
     * Les donnees sont extraites pour etre accessibles directement dans la vue
     *
     * @param string $name Nom de la vue (chemin relatif depuis views/)
     * @param array $data Donnees a passer a la vue
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
     * Redirection interne
     *
     * @param string $url URL relative (ex: '/login', '/admin')
     * @return void
     */
    protected function redirect($url)
    {
        header('Location: ' . BASE_URL . $url);
        exit;
    }

    /**
     * Stocke un message flash
     * Affiche une fois apres redirection puis supprime auto
     *
     * @param string $type 'success' ou 'error'
     * @param string $message Contenu du message
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
     * Verifie si un user est connecte
     *
     * @return bool
     */
    protected function isLogged()
    {
        return isset($_SESSION['user']);
    }

    /**
     * Verifie si le user est admin
     *
     * @return bool
     */
    protected function isAdmin()
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }

    /**
     * Recupere l'utilisateur connecte
     *
     * @return array|null Donnees user ou null
     */
    protected function getUser()
    {
        return $_SESSION['user'] ?? null;
    }
}
