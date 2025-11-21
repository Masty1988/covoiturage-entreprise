<?php ob_start(); ?>

<h1>Proposer un trajet</h1>

<div class="card">
    <div class="card-body">
        <form action="<?= BASE_URL ?>/trajets/store" method="POST">
            <!-- Infos conducteur (lecture seule) -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Prénom</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['prenom']) ?>" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Téléphone</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['telephone']) ?>" readonly>
                </div>
            </div>

            <hr>

            <!-- Infos trajet -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="agence_depart_id" class="form-label">Ville de départ</label>
                    <select class="form-select" id="agence_depart_id" name="agence_depart_id" required>
                        <option value="">Choisir...</option>
                        <?php foreach ($agences as $agence): ?>
                            <option value="<?= $agence['id'] ?>"><?= htmlspecialchars($agence['nom_ville']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="agence_arrivee_id" class="form-label">Ville d'arrivée</label>
                    <select class="form-select" id="agence_arrivee_id" name="agence_arrivee_id" required>
                        <option value="">Choisir...</option>
                        <?php foreach ($agences as $agence): ?>
                            <option value="<?= $agence['id'] ?>"><?= htmlspecialchars($agence['nom_ville']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="date_heure_depart" class="form-label">Date et heure de départ</label>
                    <input type="datetime-local" class="form-control" id="date_heure_depart" name="date_heure_depart" required>
                </div>
                <div class="col-md-6">
                    <label for="date_heure_arrivee" class="form-label">Date et heure d'arrivée</label>
                    <input type="datetime-local" class="form-control" id="date_heure_arrivee" name="date_heure_arrivee" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="places_totales" class="form-label">Nombre de places</label>
                <input type="number" class="form-control" id="places_totales" name="places_totales" min="1" max="8" required>
            </div>

            <button type="submit" class="btn btn-primary">Créer le trajet</button>
            <a href="<?= BASE_URL ?>/" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>
