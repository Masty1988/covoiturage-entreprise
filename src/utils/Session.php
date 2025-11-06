<?php

namespace App\Utils;

/**
 * Classe Session - Gestion des sessions
 * 
 * @package App\Utils
 */
class Session
{
    /**
     * Démarre la session si elle n'est pas déjà démarrée
     * 
     * @return void
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Définit une valeur dans la session
     * 
     * @param string $key Clé
     * @param mixed $value Valeur
     * @return void
     */
    public static function set(string $key, $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Récupère une valeur de la session
     * 
     * @param string $key Clé
     * @param mixed $default Valeur par défaut
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Vérifie si une clé existe dans la session
     * 
     * @param string $key Clé
     * @return bool
     */
    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    /**
     * Supprime une valeur de la session
     * 
     * @param string $key Clé
     * @return void
     */
    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    /**
     * Détruit complètement la session
     * 
     * @return void
     */
    public static function destroy(): void
    {
        self::start();
        session_unset();
        session_destroy();
    }

    /**
     * Vérifie si l'utilisateur est connecté
     * 
     * @return bool
     */
    public static function isAuthenticated(): bool
    {
        return self::has('user_id');
    }

    /**
     * Vérifie si l'utilisateur est administrateur
     * 
     * @return bool
     */
    public static function isAdmin(): bool
    {
        return self::get('is_admin', false) === true;
    }
}