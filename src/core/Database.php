<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Classe Database - Gestion de la connexion à la base de données
 * Pattern Singleton pour une connexion unique
 * 
 * @package App\Core
 */
class Database
{
    /**
     * Instance unique de PDO
     * 
     * @var PDO|null
     */
    private static ?PDO $instance = null;

    /**
     * Constructeur privé pour empêcher l'instanciation directe
     */
    private function __construct()
    {
        // Empêche l'instanciation
    }

    /**
     * Récupère l'instance unique de connexion PDO
     * 
     * @return PDO Instance de connexion
     * @throws PDOException Si la connexion échoue
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            try {
                // Charge la configuration
                $config = require __DIR__ . '/../../config/database.php';

                // Création du DSN (Data Source Name)
                $dsn = sprintf(
                    "%s:host=%s;port=%s;dbname=%s;charset=%s",
                    $config['driver'],
                    $config['host'],
                    $config['port'],
                    $config['database'],
                    $config['charset']
                );

                // Création de l'instance PDO
                self::$instance = new PDO(
                    $dsn,
                    $config['username'],
                    $config['password'],
                    $config['options']
                );
            } catch (PDOException $e) {
                // Log l'erreur et relance l'exception
                error_log("Erreur de connexion à la base de données : " . $e->getMessage());
                throw new PDOException("Impossible de se connecter à la base de données");
            }
        }

        return self::$instance;
    }

    /**
     * Empêche le clonage de l'instance
     * 
     * @return void
     */
    private function __clone()
    {
        // Empêche le clonage
    }

    /**
     * Empêche la désérialisation de l'instance
     * 
     * @return void
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}