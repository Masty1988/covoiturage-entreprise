<?php ob_start(); ?>

<h1>Trajets disponibles</h1>

<?php if (empty($trajets)): ?>
    <div class="alert alert-info">Aucun trajet disponible pour le moment.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Départ</th>
                    <th>Date départ</th>
                    <th>Arrivée</th>
                    <th>Date arrivée</th>
                    <th>Places dispo</th>
                    <?php if (isset($user)): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trajets as $trajet): ?>
                    <tr>
                        <td><?= htmlspecialchars($trajet['ville_depart']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($trajet['date_heure_depart'])) ?></td>
                        <td><?= htmlspecialchars($trajet['ville_arrivee']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($trajet['date_heure_arrivee'])) ?></td>
                        <td><?= $trajet['places_disponibles'] ?></td>
                        <?php if (isset($user)): ?>
                            <td>
                                <!-- Bouton détails -->
                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modal<?= $trajet['id'] ?>">
                                    Détails
                                </button>

                                <?php if ($user['id'] == $trajet['conducteur_id']): ?>
                                    <a href="<?= BASE_URL ?>/trajets/<?= $trajet['id'] ?>/edit" class="btn btn-warning btn-sm">Modifier</a>
                                    <form action="<?= BASE_URL ?>/trajets/<?= $trajet['id'] ?>/delete" method="POST" style="display:inline;">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce trajet ?')">Supprimer</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>

                    <!-- Modal détails -->
                    <?php if (isset($user)): ?>
                        <div class="modal fade" id="modal<?= $trajet['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Détails du trajet</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Conducteur :</strong> <?= htmlspecialchars($trajet['prenom'] . ' ' . $trajet['nom']) ?></p>
                                        <p><strong>Téléphone :</strong> <?= htmlspecialchars($trajet['telephone']) ?></p>
                                        <p><strong>Email :</strong> <?= htmlspecialchars($trajet['email']) ?></p>
                                        <p><strong>Places totales :</strong> <?= $trajet['places_totales'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>
