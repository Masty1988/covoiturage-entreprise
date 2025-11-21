<?php ob_start(); ?>

<h1>Gestion des agences</h1>

<a href="<?= BASE_URL ?>/admin/agences/create" class="btn btn-primary mb-3">
    Ajouter une agence
</a>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ville</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($agences as $agence): ?>
                <tr>
                    <td><?= $agence['id'] ?></td>
                    <td><?= htmlspecialchars($agence['nom_ville']) ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>/admin/agences/<?= $agence['id'] ?>/edit" class="btn btn-warning btn-sm">
                            Modifier
                        </a>
                        <form action="<?= BASE_URL ?>/admin/agences/<?= $agence['id'] ?>/delete" method="POST" style="display:inline;">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette agence ?')">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<a href="<?= BASE_URL ?>/admin" class="btn btn-secondary">Retour</a>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>
