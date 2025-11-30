<?php
namespace App;

use PDO;
use PDOException;

/**
 * Classe de connexion à la base de données
 * Utilise le pattern Singleton pour éviter les connexions multiples
 */
class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        // Utilise config test si variable d'env TEST_MODE=true
        $configFile = getenv('TEST_MODE') === 'true'
            ? __DIR__ . '/../config/database.test.php'
            : __DIR__ . '/../config/database.php';

        $config = require $configFile;

        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['dbname'],
            $config['charset']
        );

        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Erreur connexion BDD : ' . $e->getMessage());
        }
    }

    /**
     * Récupère l'instance unique de la connexion
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Retourne l'objet PDO
     */
    public function getConnection()
    {
        return $this->pdo;
    }
}
