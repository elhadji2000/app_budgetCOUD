<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}

include '../../includes/fonctions.php';

$idFourn = $_GET['idFourn'] ?? null;
$fournisseur = null;

if ($idFourn) {
    $fournisseur = getFournisseurById($idFourn);  // fonction à créer si pas encore
}

include '../../includes/header.php';

?>

<main>
    <div class='container'>

        <!-- Titre -->
        <div class='text-center' style='margin-bottom:20px;color:#4655a4;'>
            <h3>NOUVEAU FOURNISSEUR</h3>
            <p class="text-danger fst-italic mb-0">
                Veuillez remplir correctement toutes les informations
            </p>
        </div>

        <!-- Formulaire -->
        <form action="traitement_fourn.php" method="POST" class="needs-validation" novalidate>

            <input type="hidden" name="idFourn" value="<?= $fournisseur['idFourn'] ?? '' ?>">

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

                    <!-- Num fournisseur -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold">
                            NUM F <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="numFourn" class="form-control" required
                            value="<?= htmlspecialchars($fournisseur['numFourn'] ?? '') ?>">
                        <div class="invalid-feedback">Veuillez entrer le numéro fournisseur.</div>
                    </div>

                    <!-- Nom -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold">
                            NOM <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nom" class="form-control" required
                            value="<?= htmlspecialchars($fournisseur['nom'] ?? '') ?>">
                        <div class="invalid-feedback">Veuillez entrer le nom.</div>
                    </div>

                    <!-- Adresse -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold">
                            ADRESSE <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="adresse" class="form-control" required
                            value="<?= htmlspecialchars($fournisseur['adresse'] ?? '') ?>">
                        <div class="invalid-feedback">Veuillez entrer l'adresse.</div>
                    </div>

                    <!-- Contact -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold">
                            CONTACT <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="contact" class="form-control" required
                            value="<?= htmlspecialchars($fournisseur['contact'] ?? '') ?>">
                        <div class="invalid-feedback">Veuillez entrer le contact.</div>
                    </div>

                    <!-- Nature -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold">
                            NATURE <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nature" class="form-control" required
                            value="<?= htmlspecialchars($fournisseur['nature'] ?? '') ?>">
                        <div class="invalid-feedback">Veuillez préciser la nature.</div>
                    </div>

                </div>
            </div>

            <!-- Boutons -->
            <div class="container mt-3" style="max-width:700px;">
                <div class="d-flex justify-content-between">
                    <button type="submit"
                        class="btn <?= isset($fournisseur['idFourn']) ? 'btn-warning' : 'btn-success' ?>">
                        <strong>
                            <?= isset($fournisseur['idFourn']) ? 'Modifier' : 'Valider' ?>
                        </strong>
                    </button>
                    <a href="javascript:history.back()" class="btn btn-secondary">
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
                Le fournisseur a été enregistré avec succès !
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