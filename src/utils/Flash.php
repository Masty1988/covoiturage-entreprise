<?php

namespace App\Utils;

/**
 * Classe Flash - Gestion des messages flash
 * 
 * @package App\Utils
 */
class Flash
{
    /**
     * Définit un message flash
     * 
     * @param string $type Type de message (success, error, warning, info)
     * @param string $message Message
     * @return void
     */
    public static function set(string $type, string $message): void
    {
        Session::start();
        $_SESSION['flash'][$type] = $message;
    }

    /**
     * Récupère un message flash (et le supprime)
     * 
     * @param string $type Type de message
     * @return string|null
     */
    public static function get(string $type): ?string
    {
        Session::start();
        
        if (isset($_SESSION['flash'][$type])) {
            $message = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $message;
        }

        return null;
    }

    /**
     * Vérifie si un message flash existe
     * 
     * @param string $type Type de message
     * @return bool
     */
    public static function has(string $type): bool
    {
        Session::start();
        return isset($_SESSION['flash'][$type]);
    }

    /**
     * Affiche un message flash en HTML (Bootstrap)
     * 
     * @param string $type Type de message
     * @return void
     */
    public static function display(string $type): void
    {
        $message = self::get($type);
        
        if ($message !== null) {
            $alertClass = match($type) {
                'success' => 'alert-success',
                'error' => 'alert-danger',
                'warning' => 'alert-warning',
                'info' => 'alert-info',
                default => 'alert-secondary'
            };

            echo "<div class='alert {$alertClass} alert-dismissible fade show' role='alert'>";
            echo htmlspecialchars($message);
            echo "<button type='button' class='btn-close' data-bs-dismiss='alert'></button>";
            echo "</div>";
        }
    }
}