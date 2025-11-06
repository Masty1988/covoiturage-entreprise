<?php

namespace App\Core;

/**
 * Classe Controller - Contrôleur de base
 * Tous les contrôleurs héritent de cette classe
 * 
 * @package App\Core
 */
abstract class Controller
{
    /**
     * Rend une vue avec des données
     * 
     * @param string $view Nom de la vue (ex: 'home/index')
     * @param array $data Données à passer à la vue
     * @return void
     */
    protected function render(string $view, array $data = []): void
    {
        // Extrait les données pour les rendre disponibles dans la vue
        extract($data);

        // Construit le chemin vers la vue
        $viewPath = __DIR__ . "/../Views/{$view}.php";

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            throw new \Exception("La vue {$view} n'existe pas");
        }
    }

    /**
     * Redirige vers une URL
     * 
     * @param string $url URL de redirection
     * @return void
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Retourne une réponse JSON
     * 
     * @param mixed $data Données à encoder en JSON
     * @param int $statusCode Code de statut HTTP
     * @return void
     */
    protected function json($data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}