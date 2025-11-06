<?php
use App\Utils\Session;
use App\Utils\Flash;
Session::start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Touche pas au Klaxon - Covoiturage d'entreprise</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header class="navbar navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Touche pas au Klaxon</a>
            
            <div class="d-flex align-items-center">
                <?php if (Session::isAuthenticated()): ?>
                    <?php if (Session::isAdmin()): ?>
                        <!-- Menu Admin -->
                        <nav class="me-3">
                            <a href="/admin/users" class="btn btn-outline-light btn-sm me-2">Utilisateurs</a>
                            <a href="/admin/agences" class="btn btn-outline-light btn-sm me-2">Agences</a>
                            <a href="/admin/trajets" class="btn btn-outline-light btn-sm me-2">Trajets</a>
                        </nav>
                    <?php else: ?>
                        <!-- Menu Utilisateur -->
                        <a href="/trajets/create" class="btn btn-primary me-3">Créer un trajet</a>
                    <?php endif; ?>
                    
                    <span class="text-white me-3">
                        Bonjour <?= htmlspecialchars(Session::get('user_prenom')) ?> 
                        <?= htmlspecialchars(Session::get('user_nom')) ?>
                    </span>
                    
                    <a href="/logout" class="btn btn-outline-light">Déconnexion</a>
                <?php else: ?>
                    <!-- Bouton Connexion -->
                    <a href="/login" class="btn btn-outline-light">Connexion</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="container">
        <!-- Messages Flash -->
        <?php
        Flash::display('success');
        Flash::display('error');
        Flash::display('warning');
        Flash::display('info');
        ?>