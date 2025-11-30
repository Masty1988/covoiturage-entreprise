<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\TrajetRepository;

/**
 * Contrôleur Home - Page d'accueil
 * 
 * @package App\Controllers
 */
class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil avec la liste des trajets disponibles
     * 
     * @return void
     */
    public function index(): void
    {
        // Récupère les trajets disponibles
        $trajetRepo = new TrajetRepository();
        $trajets = $trajetRepo->findAllAvailable();

        // Affiche la vue
        $this->render('home/index', [
            'trajets' => $trajets
        ]);
    }
}