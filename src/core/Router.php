<?php

namespace App\Core;

/**
 * Classe Router - Gestion du routage de l'application
 * 
 * @package App\Core
 */
class Router
{
    /**
     * Liste des routes enregistrées
     * 
     * @var array
     */
    private array $routes = [];

    /**
     * Ajoute une route GET
     * 
     * @param string $path Chemin de la route
     * @param string $action Action (Controller@method)
     * @param array $middlewares Middlewares à appliquer
     * @return void
     */
    public function get(string $path, string $action, array $middlewares = []): void
    {
        $this->addRoute('GET', $path, $action, $middlewares);
    }

    /**
     * Ajoute une route POST
     * 
     * @param string $path Chemin de la route
     * @param string $action Action (Controller@method)
     * @param array $middlewares Middlewares à appliquer
     * @return void
     */
    public function post(string $path, string $action, array $middlewares = []): void
    {
        $this->addRoute('POST', $path, $action, $middlewares);
    }

    /**
     * Ajoute une route dans le registre
     * 
     * @param string $method Méthode HTTP
     * @param string $path Chemin
     * @param string $action Action
     * @param array $middlewares Middlewares
     * @return void
     */
    private function addRoute(string $method, string $path, string $action, array $middlewares): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'action' => $action,
            'middlewares' => $middlewares
        ];
    }

    /**
     * Dispatche la requête vers le bon contrôleur
     * 
     * @return void
     */
    public function dispatch(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Recherche la route correspondante
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $route['path'] === $requestUri) {
                // Exécute les middlewares
                foreach ($route['middlewares'] as $middleware) {
                    $middlewareClass = "App\\Middlewares\\{$middleware}Middleware";
                    if (class_exists($middlewareClass)) {
                        $middlewareClass::handle();
                    }
                }

                // Sépare le contrôleur et la méthode
                [$controller, $method] = explode('@', $route['action']);
                
                // Construit le nom complet de la classe
                $controllerClass = "App\\Controllers\\{$controller}";

                if (class_exists($controllerClass)) {
                    $controllerInstance = new $controllerClass();
                    
                    if (method_exists($controllerInstance, $method)) {
                        $controllerInstance->$method();
                        return;
                    }
                }
            }
        }

        // Aucune route trouvée - 404
        http_response_code(404);
        echo "404 - Page non trouvée";
    }
}