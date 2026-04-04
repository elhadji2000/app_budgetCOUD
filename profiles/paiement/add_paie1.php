<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}
?>
<?php include '../../includes/fonctions.php'; ?>
<?php include '../../includes/header.php'; ?>

<?php 
$nums = getNumCompteForOp();
?>

<main class="container py-2">

    <!-- Titre -->
    <div class="text-center mb-1" style="color:#4655a4;">
        <p class="mb-0 fw-bold">ENREGISTREMENT DES ORDRES DE PAIEMENT</p>
        <p class="mb-2 text-success small">
            <i>Veuillez choisir le compte concerné afin d'obtenir la liste de ses engagements</i>
        </p>
    </div>

    <!-- Card -->
    <div class="card shadow-sm border-primary mx-auto" style="max-width:600px;">
        
        <div class="card-header text-center">
            <strong>Choix du Compte</strong>
        </div>

        <div class="card-body">

            <!-- Message erreur -->
            <?php if (!empty($_GET['error'])): ?>
                <div class="alert alert-danger text-center">
                    <?= htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <form action="add_paie2.php" method="GET">

                <div class="row">

                    <!-- Numéro compte -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">
                            Numéro du Compte <span class="text-danger">*</span>
                        </label>
                        <select name="numc" class="form-select" required>
                            <option value="">Sélectionner un compte</option>
                            <?php foreach ($nums as $num): ?>
                                <option value="<?= htmlspecialchars($num["numCompte"]) ?>">
                                    <?= htmlspecialchars($num["numCompte"]) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">
                        <strong>Valider</strong>
                    </button>

                    <a href="javascript:history.back()" class="btn btn-secondary">
                        <strong>Annuler</strong>
                    </a>
                </div>

            </form>
        </div>
    </div>

</main>

<!-- Modal succès -->
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-success">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Succès</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                 L'opération a été enregistrée avec succès !
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-success" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    new bootstrap.Modal(document.getElementById('successModal')).show();
});
</script>
<?php endif; ?>

<?php include '../../includes/footer.php'; ?>