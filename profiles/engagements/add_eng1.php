<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}

include '../../includes/fonctions.php';

$annee_connexion = $_SESSION['an'] ?? date("Y");
$min_date = $annee_connexion . "-01-01";
$max_date = date("Y-m-d");
$nums = getComptesDotationsByEng();

include '../../includes/header.php';
?>

<main class="container py-1">

    <!-- Titre -->
    <div class="text-center mb-1" style="color:#4655a4;">
        <h3 class="fw-bold">RENSEIGNER LE COMPTE</h3>
        <p class="text-success small"><i>Veuillez sélectionner le compte concerné</i></p>
    </div>

    <!-- Card centrale -->
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

            <form action="add_eng2.php" method="GET" class="needs-validation" novalidate>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        NUMÉRO DU COMPTE <span class="text-danger">*</span>
                    </label>
                    <select name="numc" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($nums as $num): ?>
                        <option value="<?= htmlspecialchars($num["numCompte"]); ?>">
                            <?= htmlspecialchars($num["numCompte"]); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Veuillez sélectionner un compte.</div>
                </div>

                <!-- Actions -->
                <!-- Boutons -->
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
              L'engagement a été enregistré avec succès !
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

// Bootstrap validation
(() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', e => {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
})();
</script>
<?php endif; ?>

<?php include '../../includes/footer.php'; ?>