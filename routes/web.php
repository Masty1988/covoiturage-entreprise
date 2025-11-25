<?php
/**
 * Definition des routes de l'application
 *
 * Utilise le routeur izniburak/router
 * Documentation: https://github.com/izniburak/php-router
 */

// Page d'accueil - liste des trajets
$router->get('/', 'TrajetController@index');

// Authentification
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// Trajets (utilisateur connecte)
$router->get('/trajets/create', 'TrajetController@create');
$router->post('/trajets/store', 'TrajetController@store');
$router->get('/trajets/(:num)/edit', 'TrajetController@edit');
$router->post('/trajets/(:num)/update', 'TrajetController@update');
$router->post('/trajets/(:num)/delete', 'TrajetController@delete');

// Administration - Dashboard et utilisateurs
$router->get('/admin', 'AdminController@dashboard');
$router->get('/admin/users', 'AdminController@users');

// Administration - Gestion des agences
$router->get('/admin/agences', 'AdminController@agences');
$router->get('/admin/agences/create', 'AdminController@createAgence');
$router->post('/admin/agences/store', 'AdminController@storeAgence');
$router->get('/admin/agences/(:num)/edit', 'AdminController@editAgence');
$router->post('/admin/agences/(:num)/update', 'AdminController@updateAgence');
$router->post('/admin/agences/(:num)/delete', 'AdminController@deleteAgence');

// Administration - Gestion des trajets
$router->get('/admin/trajets', 'AdminController@trajets');
$router->post('/admin/trajets/(:num)/delete', 'AdminController@deleteTrajet');
