<?php include 'includes/header.php'; ?>

<div class="container py-1">

    <!-- TITRES -->
    <div class="text-center mb-2">
        <h6 class="fw-bold mb-0">
            Veuillez vous identifier et indiquer l'année budgétaire
        </h6>

        <p class="text-danger small mb-1">
            <strong>Attention :</strong> Ne jamais saisir son mot de passe devant une tierce personne !
        </p>

        <h6 class="fw-bold text-success mb-0">
            AUTHENTIFICATION
        </h6>
    </div>

    <!-- CARD FORM -->
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-6 col-lg-4">

            <div class="card border shadow-sm">
                <div class="card-body p-3">

                    <!-- MESSAGE ERREUR -->
                    <?php if (!empty($_GET["error"])): ?>
                    <div class="alert alert-danger text-center py-1 mb-2">
                        <?php echo $_GET["error"]; ?>
                    </div>
                    <?php endif; ?>

                    <form action="auth/log.php" method="POST">

                        <!-- UTILISATEUR -->
                        <div class="mb-2">
                            <label class="form-label small fw-semibold mb-1">Utilisateur</label>
                            <input type="text" name="utilisateur" class="form-control form-control-sm"
                                placeholder="Matricule Agent..." required>
                        </div>

                        <!-- MOT DE PASSE -->
                        <div class="mb-2">
                            <label class="form-label small fw-semibold mb-1">Mot de passe</label>
                            <input type="password" name="motdepasse" class="form-control form-control-sm"
                                placeholder="••••••••" required>
                        </div>

                        <!-- ANNEE -->
                        <div class="mb-3">
                            <label class="form-label small fw-semibold mb-1">Année budgétaire</label>
                            <select name="annee_budgetaire" class="form-select form-select-sm" required>
                                <option value="">-- Sélectionnez --</option>
                                <?php
                                $annee_actuelle = date("Y");
                                $annee_debut = 2025;
                                for ($i = $annee_actuelle; $i >= $annee_debut; $i--) {
                                    echo "<option value='$i'>$i</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- BUTTON -->
                        <div class="d-grid mb-2">
                            <button type="submit" class="btn btn-success btn-sm">
                                Se connecter
                            </button>
                        </div>

                        <!-- LINK -->
                        <div class="text-center">
                            <a href="#" class="small text-decoration-none">
                                Mot de passe oublié ?
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
<!-- Modal Bootstrap -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-success">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel">Succès</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                votre mot de passe a été modifiè avec succès !
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
<script>
// Une fois le DOM chargé, on lance la modal
document.addEventListener('DOMContentLoaded', function() {
    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
});
</script>
<?php endif; ?>
<?php include 'includes/footer.php'; ?>