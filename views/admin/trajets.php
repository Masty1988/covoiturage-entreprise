<?php ob_start(); ?>

<h1>Gestion des trajets</h1>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Depart</th>
                <th>Arrivee</th>
                <th>Date depart</th>
                <th>Places</th>
                <th>Conducteur</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($trajets)): ?>
                <tr>
                    <td colspan="7" class="text-center">Aucun trajet</td>
                </tr>
            <?php else: ?>
                <?php foreach ($trajets as $trajet): ?>
                    <tr>
                        <td><?= $trajet['id'] ?></td>
                        <td><?= htmlspecialchars($trajet['ville_depart']) ?></td>
                        <td><?= htmlspecialchars($trajet['ville_arrivee']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($trajet['date_heure_depart'])) ?></td>
                        <td><?= $trajet['places_disponibles'] ?>/<?= $trajet['places_totales'] ?></td>
                        <td><?= htmlspecialchars($trajet['prenom'] . ' ' . $trajet['nom']) ?></td>
                        <td>
                            <form action="<?= BASE_URL ?>/admin/trajets/<?= $trajet['id'] ?>/delete" method="POST" style="display:inline;">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce trajet ?')">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<a href="<?= BASE_URL ?>/admin" class="btn btn-secondary">Retour</a>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>
