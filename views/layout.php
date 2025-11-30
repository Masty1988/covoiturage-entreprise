<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KLAXON</title>
    <link rel="stylesheet" href="/covoiturage-entreprise/assets/css/app.css">
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_URL ?>/">KLAXON</a>

            <div class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user'])): ?>
                    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                        <!-- Menu admin -->
                        <a class="nav-link" href="<?= BASE_URL ?>/admin">Tableau de bord</a>
                        <a class="nav-link" href="<?= BASE_URL ?>/admin/users">Utilisateurs</a>
                        <a class="nav-link" href="<?= BASE_URL ?>/admin/agences">Agences</a>
                        <a class="nav-link" href="<?= BASE_URL ?>/admin/trajets">Trajets</a>
                    <?php else: ?>
                        <!-- Menu utilisateur -->
                        <a class="btn btn-light btn-sm me-2" href="<?= BASE_URL ?>/trajets/create">Proposer un trajet</a>
                        <span class="nav-link"><?= htmlspecialchars($_SESSION['user']['prenom'] . ' ' . $_SESSION['user']['nom']) ?></span>
                    <?php endif; ?>
                    <a class="btn btn-outline-light btn-sm" href="<?= BASE_URL ?>/logout">Deconnexion</a>
                <?php else: ?>
                    <a class="btn btn-light btn-sm" href="<?= BASE_URL ?>/login">Connexion</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Messages flash -->
    <div class="container mt-3">
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert alert-<?= $_SESSION['flash']['type'] === 'error' ? 'danger' : 'success' ?> alert-dismissible fade show">
                <?= htmlspecialchars($_SESSION['flash']['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>
    </div>

    <!-- Contenu principal -->
    <main class="container my-4">
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-3 mt-5">
        <div class="container text-center">
            <p class="mb-0">KLAXON &copy; <?= date('Y') ?></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
