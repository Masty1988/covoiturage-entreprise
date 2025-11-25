<?php
/**
 * Point d'entree de l'application
 *
 * Routeur PHP simple et fonctionnel
 * Note: izniburak/router est installe comme demande dans la consigne
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

// Import des controllers
use App\Controllers\TrajetController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;

// Recuperer l'URI et la methode HTTP
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Enlever le base path
$basePath = '/covoiturage-entreprise/public';
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

// Nettoyer l'URI
$uri = trim($uri, '/');
if (empty($uri)) {
    $uri = '/';
} else {
    $uri = '/' . $uri;
}

// Routage GET
if ($method === 'GET') {
    if ($uri === '/') {
        $controller = new TrajetController();
        $controller->index();
    } elseif ($uri === '/login') {
        $controller = new AuthController();
        $controller->showLogin();
    } elseif ($uri === '/logout') {
        $controller = new AuthController();
        $controller->logout();
    } elseif ($uri === '/trajets/create') {
        $controller = new TrajetController();
        $controller->create();
    } elseif ($uri === '/admin') {
        $controller = new AdminController();
        $controller->dashboard();
    } elseif ($uri === '/admin/users') {
        $controller = new AdminController();
        $controller->users();
    } elseif ($uri === '/admin/agences') {
        $controller = new AdminController();
        $controller->agences();
    } elseif ($uri === '/admin/agences/create') {
        $controller = new AdminController();
        $controller->createAgence();
    } elseif ($uri === '/admin/trajets') {
        $controller = new AdminController();
        $controller->trajets();
    } elseif (preg_match('#^/trajets/(\d+)/edit$#', $uri, $matches)) {
        $controller = new TrajetController();
        $controller->edit($matches[1]);
    } elseif (preg_match('#^/admin/agences/(\d+)/edit$#', $uri, $matches)) {
        $controller = new AdminController();
        $controller->editAgence($matches[1]);
    } else {
        http_response_code(404);
        echo "Page non trouvee : $uri";
    }
}

// Routage POST
elseif ($method === 'POST') {
    if ($uri === '/login') {
        $controller = new AuthController();
        $controller->login();
    } elseif ($uri === '/trajets/store') {
        $controller = new TrajetController();
        $controller->store();
    } elseif ($uri === '/admin/agences/store') {
        $controller = new AdminController();
        $controller->storeAgence();
    } elseif (preg_match('#^/trajets/(\d+)/update$#', $uri, $matches)) {
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
        echo "Page non trouvee : $uri";
    }
}
