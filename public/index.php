<?php

/**
 * Point d'entrée de l'application
 * Toutes les requêtes passent par ce fichier
 */

// Autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Démarrage de la session
session_start();

// Charge le routeur
$router = require_once __DIR__ . '/../config/routes.php';

// Dispatche la requête
$router->dispatch();