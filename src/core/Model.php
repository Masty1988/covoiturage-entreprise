<?php

namespace App\Core;

use PDO;

/**
 * Classe Model - Modèle de base
 * Tous les repositories héritent de cette classe
 * 
 * @package App\Core
 */
abstract class Model
{
    /**
     * Instance PDO
     * 
     * @var PDO
     */
    protected PDO $pdo;

    /**
     * Constructeur - Initialise la connexion PDO
     */
    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Exécute une requête préparée
     * 
     * @param string $sql Requête SQL
     * @param array $params Paramètres de la requête
     * @return \PDOStatement
     */
    protected function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Récupère tous les résultats
     * 
     * @param string $sql Requête SQL
     * @param array $params Paramètres
     * @return array
     */
    protected function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Récupère un seul résultat
     * 
     * @param string $sql Requête SQL
     * @param array $params Paramètres
     * @return array|false
     */
    protected function fetchOne(string $sql, array $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
}