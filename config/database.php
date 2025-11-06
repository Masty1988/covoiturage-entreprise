<?php

/**
 * Configuration de la base de données
 * 
 * @package App\Config
 */

return [
    'driver' => 'mysql',
    'host' => 'localhost',
    'port' => '3306',
    'database' => 'covoiturage',
    'username' => 'covoiturage_user',
    'password' => 'covoiturage_pass',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];