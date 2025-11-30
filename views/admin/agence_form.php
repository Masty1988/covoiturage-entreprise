<?php ob_start(); ?>

<h1><?= $agence ? 'Modifier' : 'Ajouter' ?> une agence</h1>

<div class="card">
    <div class="card-body">
        <form action="<?= $agence ? BASE_URL . '/admin/agences/' . $agence['id'] . '/update' : BASE_URL . '/admin/agences/store' ?>" method="POST">
            <div class="mb-3">
                <label for="nom_ville" class="form-label">Nom de la ville</label>
                <input type="text" class="form-control" id="nom_ville" name="nom_ville"
                    value="<?= $agence ? htmlspecialchars($agence['nom_ville']) : '' ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">
                <?= $agence ? 'Modifier' : 'Ajouter' ?>
            </button>
            <a href="<?= BASE_URL ?>/admin/agences" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>
