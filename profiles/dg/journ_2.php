<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}
?>

<?php 
include '../../includes/fonctions.php';

$date = $_GET['dateEng'] ?? '';
$nums = getCompteEngsByDate($date);
?>

<?php include '../../includes/header.php';?>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<main class="container mt-4">

    <!-- HEADER -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body text-center">

            <h5 class="fw-bold text-primary mb-2">
                <i class="bi bi-journal-text"></i> SÉLECTION DU COMPTE (JOURNALIER)
            </h5>

            <small class="text-muted">
                Date sélectionnée :
                <strong><?= htmlspecialchars($date) ?></strong>
            </small>

        </div>
    </div>

    <!-- FORMULAIRE -->
    <form action="journ_3.php" method="GET">

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <!-- Message erreur -->
                <?php if (!empty($_GET['error'])) : ?>
                    <div class="alert alert-danger text-center">
                        <i class="bi bi-exclamation-triangle"></i>
                        <?= htmlspecialchars($_GET['error']) ?>
                    </div>
                <?php endif; ?>

                <!-- Hidden date -->
                <input type="hidden" name="dateEng" value="<?= htmlspecialchars($date) ?>">

                <div class="row justify-content-center">

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            <i class="bi bi-folder"></i> Numéro du compte
                        </label>

                        <select name="numCompte" class="form-select" required>
                            <option value="">-- Sélectionner un compte --</option>

                            <?php if (!empty($nums)) : ?>
                                <?php foreach ($nums as $num) : ?>
                                    <option value="<?= htmlspecialchars($num['numCompte']) ?>">
                                        <?= htmlspecialchars($num['numCompte']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <option disabled>Aucun compte disponible</option>
                            <?php endif; ?>

                        </select>

                    </div>

                </div>

            </div>

            <!-- ACTIONS -->
            <div class="card-footer d-flex justify-content-between">

                <button type="submit" class="btn btn-success btn-sm">
                    <i class="bi bi-check-circle"></i> Valider
                </button>

                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Annuler
                </a>

            </div>
        </div>

    </form>

</main>

<!-- SUCCESS MODAL -->
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="bi bi-check-circle"></i> Succès
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">
                🎉 L’engagement a été enregistré avec succès !
            </div>

            <div class="modal-footer">
                <button class="btn btn-outline-success" data-bs-dismiss="modal">
                    Fermer
                </button>
            </div>

        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = new bootstrap.Modal(document.getElementById('successModal'));
    modal.show();
});
</script>
<?php endif; ?>

<?php include '../../includes/footer.php';?>