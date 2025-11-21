<?php
/**
 * Definition des routes de l'application
 */

use App\Controllers\TrajetController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;

// Page d'accueil - liste des trajets
$router->get('/', function() {
    $controller = new TrajetController();
    $controller->index();
});

// Authentification
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// Trajets (utilisateur connecté)
$router->get('/trajets/create', 'TrajetController@create');
$router->post('/trajets/store', 'TrajetController@store');
$router->get('/trajets/{id}/edit', 'TrajetController@edit');
$router->post('/trajets/{id}/update', 'TrajetController@update');
$router->post('/trajets/{id}/delete', 'TrajetController@delete');

// Admin
$router->get('/admin', 'AdminController@dashboard');
$router->get('/admin/users', 'AdminController@users');
$router->get('/admin/agences', 'AdminController@agences');
$router->get('/admin/agences/create', 'AdminController@createAgence');
$router->post('/admin/agences/store', 'AdminController@storeAgence');
$router->get('/admin/agences/{id}/edit', 'AdminController@editAgence');
$router->post('/admin/agences/{id}/update', 'AdminController@updateAgence');
$router->post('/admin/agences/{id}/delete', 'AdminController@deleteAgence');
$router->get('/admin/trajets', 'AdminController@trajets');
$router->post('/admin/trajets/{id}/delete', 'AdminController@deleteTrajet');
