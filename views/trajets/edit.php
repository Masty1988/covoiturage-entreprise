<?php ob_start(); ?>

<h1>Modifier le trajet</h1>

<div class="card">
    <div class="card-body">
        <form action="/trajets/<?= $trajet['id'] ?>/update" method="POST">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="agence_depart_id" class="form-label">Ville de départ</label>
                    <select class="form-select" id="agence_depart_id" name="agence_depart_id" required>
                        <?php foreach ($agences as $agence): ?>
                            <option value="<?= $agence['id'] ?>" <?= $agence['id'] == $trajet['agence_depart_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($agence['nom_ville']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="agence_arrivee_id" class="form-label">Ville d'arrivée</label>
                    <select class="form-select" id="agence_arrivee_id" name="agence_arrivee_id" required>
                        <?php foreach ($agences as $agence): ?>
                            <option value="<?= $agence['id'] ?>" <?= $agence['id'] == $trajet['agence_arrivee_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($agence['nom_ville']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="date_heure_depart" class="form-label">Date et heure de départ</label>
                    <input type="datetime-local" class="form-control" id="date_heure_depart" name="date_heure_depart"
                        value="<?= date('Y-m-d\TH:i', strtotime($trajet['date_heure_depart'])) ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="date_heure_arrivee" class="form-label">Date et heure d'arrivée</label>
                    <input type="datetime-local" class="form-control" id="date_heure_arrivee" name="date_heure_arrivee"
                        value="<?= date('Y-m-d\TH:i', strtotime($trajet['date_heure_arrivee'])) ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="places_totales" class="form-label">Places totales</label>
                    <input type="number" class="form-control" id="places_totales" name="places_totales"
                        value="<?= $trajet['places_totales'] ?>" min="1" max="8" required>
                </div>
                <div class="col-md-6">
                    <label for="places_disponibles" class="form-label">Places disponibles</label>
                    <input type="number" class="form-control" id="places_disponibles" name="places_disponibles"
                        value="<?= $trajet['places_disponibles'] ?>" min="0" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="/" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>
