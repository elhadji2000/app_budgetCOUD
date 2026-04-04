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
$nums = getNumCompteSansInitiale();

include '../../includes/header.php';
?>

<main>
    <div class='container'>

        <!-- Titre -->
        <div class='text-center' style='margin-bottom:20px;color:#4655a4;'>
            <h3>DOTATION INITIALE</h3>
            <p class="text-success fst-italic mb-0">
                Veuillez renseigner correctement les informations
            </p>
        </div>

        <!-- Formulaire -->
        <form action="traitement_dot.php" method="POST" class="needs-validation" novalidate>

            <div class="mx-auto px-3 py-4"
                style="max-width: 700px; border-top: 2px solid #4655a4; border-bottom: 2px solid #4655a4;">

                <!-- Erreur -->
                <?php if (!empty($_GET['error'])): ?>
                    <div class="text-center mb-3">
                        <i style="color:red;">
                            <?= htmlspecialchars($_GET['error']); ?>
                        </i>
                    </div>
                <?php endif; ?>

                <div class="row">

                    <!-- Compte -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold">
                            NUMÉRO DU COMPTE <span class="text-danger">*</span>
                        </label>
                        <select name="ini_numc" class="form-select" required>
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($nums as $num): ?>
                                <option value="<?= htmlspecialchars($num["idCompte"]); ?>">
                                    <?= htmlspecialchars($num["numCompte"]); ?> : <?= htmlspecialchars($num["libelle"]); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Veuillez sélectionner un compte.</div>
                    </div>

                    <!-- Date -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold">
                            DATE <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="ini_date"
                            class="form-control"
                            required min="<?= $min_date ?>" max="<?= $max_date ?>">
                        <div class="invalid-feedback">Veuillez choisir une date valide.</div>
                    </div>

                    <!-- Montant -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold">
                            MONTANT <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="ini_volume"
                            class="form-control"
                            required min="0" step="1">
                        <div class="invalid-feedback">Veuillez saisir un montant valide.</div>
                    </div>

                </div>
            </div>

            <!-- Boutons -->
            <div class="container mt-3" style="max-width:700px;">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                    <button type="submit" class="btn btn-success w-100 w-md-auto">
                        <strong>Enregistrer</strong>
                    </button>
                    <a href="javascript:history.back()" class="btn btn-danger w-100 w-md-auto">
                        <strong>Annuler</strong>
                    </a>
                </div>
            </div>

        </form>
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
                La dotation a été enregistrée avec succès !
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

// Validation Bootstrap
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