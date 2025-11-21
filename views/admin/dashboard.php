<?php ob_start(); ?>

<h1>Tableau de bord</h1>

<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Utilisateurs</h5>
                <p class="card-text display-4"><?= $stats['users'] ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Agences</h5>
                <p class="card-text display-4"><?= $stats['agences'] ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Trajets</h5>
                <p class="card-text display-4"><?= $stats['trajets'] ?></p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <a href="<?= BASE_URL ?>/admin/users" class="btn btn-outline-primary btn-lg w-100">
            Gerer les utilisateurs
        </a>
    </div>
    <div class="col-md-4">
        <a href="<?= BASE_URL ?>/admin/agences" class="btn btn-outline-success btn-lg w-100">
            Gerer les agences
        </a>
    </div>
    <div class="col-md-4">
        <a href="<?= BASE_URL ?>/admin/trajets" class="btn btn-outline-info btn-lg w-100">
            Gerer les trajets
        </a>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>
