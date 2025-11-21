<?php
namespace App\Controllers;

/**
 * Controller de base
 * Les autres controllers héritent de celui-ci
 */
class Controller
{
    /**
     * Affiche une vue avec des données
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
     * Redirige vers une URL
     */
    protected function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Stocke un message flash en session
     */
    protected function setFlash($type, $message)
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /**
     * Vérifie si l'utilisateur est connecté
     */
    protected function isLogged()
    {
        return isset($_SESSION['user']);
    }

    /**
     * Vérifie si l'utilisateur est admin
     */
    protected function isAdmin()
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }

    /**
     * Récupère l'utilisateur connecté
     */
    protected function getUser()
    {
        return $_SESSION['user'] ?? null;
    }
}
