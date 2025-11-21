<?php
/**
 * Point d'entree de l'application
 */

// Afficher les erreurs en dev
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoload Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Demarrage session
session_start();

// Routeur simple
use App\Controllers\TrajetController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;

// Recuperer l'URI
$uri = $_SERVER['REQUEST_URI'];
$uri = parse_url($uri, PHP_URL_PATH);

// Enlever le base path
$basePath = '/covoiturage-entreprise/public';
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}
if (empty($uri)) {
    $uri = '/';
}

// Enlever index.php si present
$uri = str_replace('/index.php', '', $uri);
if (empty($uri)) {
    $uri = '/';
}

// Routage
$method = $_SERVER['REQUEST_METHOD'];

// Routes GET
if ($method === 'GET') {
    switch ($uri) {
        case '/':
            $controller = new TrajetController();
            $controller->index();
            break;
        case '/login':
            $controller = new AuthController();
            $controller->showLogin();
            break;
        case '/logout':
            $controller = new AuthController();
            $controller->logout();
            break;
        case '/trajets/create':
            $controller = new TrajetController();
            $controller->create();
            break;
        case '/admin':
            $controller = new AdminController();
            $controller->dashboard();
            break;
        case '/admin/users':
            $controller = new AdminController();
            $controller->users();
            break;
        case '/admin/agences':
            $controller = new AdminController();
            $controller->agences();
            break;
        case '/admin/agences/create':
            $controller = new AdminController();
            $controller->createAgence();
            break;
        case '/admin/trajets':
            $controller = new AdminController();
            $controller->trajets();
            break;
        default:
            // Routes avec parametres
            if (preg_match('#^/trajets/(\d+)/edit$#', $uri, $matches)) {
                $controller = new TrajetController();
                $controller->edit($matches[1]);
            } elseif (preg_match('#^/admin/agences/(\d+)/edit$#', $uri, $matches)) {
                $controller = new AdminController();
                $controller->editAgence($matches[1]);
            } else {
                http_response_code(404);
                echo "Page non trouvee";
            }
    }
}

// Routes POST
if ($method === 'POST') {
    switch ($uri) {
        case '/login':
            $controller = new AuthController();
            $controller->login();
            break;
        case '/trajets/store':
            $controller = new TrajetController();
            $controller->store();
            break;
        case '/admin/agences/store':
            $controller = new AdminController();
            $controller->storeAgence();
            break;
        default:
            // Routes POST avec parametres
            if (preg_match('#^/trajets/(\d+)/update$#', $uri, $matches)) {
                $controller = new TrajetController();
                $controller->update($matches[1]);
            } elseif (preg_match('#^/trajets/(\d+)/delete$#', $uri, $matches)) {
                $controller = new TrajetController();
                $controller->delete($matches[1]);
            } elseif (preg_match('#^/admin/agences/(\d+)/update$#', $uri, $matches)) {
                $controller = new AdminController();
                $controller->updateAgence($matches[1]);
            } elseif (preg_match('#^/admin/agences/(\d+)/delete$#', $uri, $matches)) {
                $controller = new AdminController();
                $controller->deleteAgence($matches[1]);
            } elseif (preg_match('#^/admin/trajets/(\d+)/delete$#', $uri, $matches)) {
                $controller = new AdminController();
                $controller->deleteTrajet($matches[1]);
            } else {
                http_response_code(404);
                echo "Page non trouvee";
            }
    }
}
