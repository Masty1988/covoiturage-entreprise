<?php
/**
 * Point d'entrée de l'application
 * Toutes les requêtes passent par ici
 */

// Autoload Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Démarrage session
session_start();

// Chargement du routeur
use Buki\Router\Router;

$router = new Router([
    'paths' => [
        'controllers' => '../app/Controllers',
    ],
    'namespaces' => [
        'controllers' => 'App\\Controllers',
    ],
]);

// Chargement des routes
require_once __DIR__ . '/../routes/web.php';

// Exécution
$router->run();
