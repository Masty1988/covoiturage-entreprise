<?php
/**
 * Bootstrap pour les tests PHPUnit
 */

// Autoload Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Demarrage session pour les tests
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
