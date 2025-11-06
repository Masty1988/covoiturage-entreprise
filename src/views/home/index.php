<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<h1 class="mb-4">Trajets proposés</h1>

<?php if (empty($trajets)): ?>
    <div class="alert alert-info">
        <p class="mb-0">Aucun trajet disponible pour le moment.</p>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Départ</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Destination</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Places</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trajets as $trajet): ?>
                <tr>
                    <td><?= htmlspecialchars($trajet['agence_depart']) ?></td>
                    <td><?= date('d/m/Y', strtotime($trajet['date_depart'])) ?></td>
                    <td><?= date('H:i', strtotime($trajet['date_depart'])) ?></td>
                    <td><?= htmlspecialchars($trajet['agence_arrivee']) ?></td>
                    <td><?= date('d/m/Y', strtotime($trajet['date_arrivee'])) ?></td>
                    <td><?= date('H:i', strtotime($trajet['date_arrivee'])) ?></td>
                    <td><?= $trajet['places_disponibles'] ?></td>
                    <td>
                        <button class="btn btn-sm btn-info" 
                                onclick="showDetails(<?= $trajet['id'] ?>)">
                            👁️ Détails
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>