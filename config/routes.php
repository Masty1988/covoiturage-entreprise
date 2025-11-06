<?php

use App\Core\Router;

/**
 * Configuration des routes de l'application
 */

$router = new Router();

// Page d'accueil (accessible à tous)
$router->get('/', 'HomeController@index');

// Authentification
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@doLogin');
$router->get('/logout', 'AuthController@logout');

// Trajets (utilisateurs connectés)
$router->get('/trajets', 'TrajetController@index', ['Auth']);
$router->get('/trajets/create', 'TrajetController@create', ['Auth']);
$router->post('/trajets/store', 'TrajetController@store', ['Auth']);
$router->get('/trajets/edit', 'TrajetController@edit', ['Auth']);
$router->post('/trajets/update', 'TrajetController@update', ['Auth']);
$router->post('/trajets/delete', 'TrajetController@delete', ['Auth']);

// Admin (administrateurs uniquement)
$router->get('/admin/users', 'AdminController@users', ['Auth', 'Admin']);
$router->get('/admin/agences', 'AdminController@agences', ['Auth', 'Admin']);
$router->get('/admin/trajets', 'AdminController@trajets', ['Auth', 'Admin']);

return $router;