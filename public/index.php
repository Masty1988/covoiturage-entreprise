<?php
/**
 * Point d'entree de l'application
 *
 * Utilise le routeur izniburak/router comme recommande dans la consigne
 */

// Afficher les erreurs en dev
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoload Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Demarrage session
session_start();

// Base path pour les URLs
define('BASE_URL', '/covoiturage-entreprise/public');

// Initialisation du routeur izniburak/router
use \buki\Router;

$router = new Router([
    'base_folder' => '/covoiturage-entreprise/public',
    'main_method' => 'index',
    'paths' => [
        'controllers' => __DIR__ . '/../app/Controllers'
    ],
    'namespaces' => [
        'controllers' => 'App\Controllers'
    ]
]);

// Chargement des routes
require_once __DIR__ . '/../routes/web.php';

// Lancement du routeur
$router->run();
