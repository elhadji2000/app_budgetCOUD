<?php
session_start();
if ( !isset( $_SESSION[ 'user' ] ) ) {
    header( 'Location: ../../index.php' );
    // Redirige vers la page de connexion
    exit();
}
?>
<?php include '../../includes/fonctions.php';?>
<?php include '../../includes/header.php';?>
<main>
    <div class='container'>

        <div class='text-center' style='margin-bottom:20px;color:#4655a4;'>
            <h3>AJOUTER UN UTILISATEUR</h3>
        </div>

        <!-- Formulaire -->
        <form action='traitement_dba.php' method='POST'>

            <div class="mx-auto px-3 py-4"
                style="max-width: 700px; border-top: 2px solid #4655a4; border-bottom: 2px solid #4655a4;">

                <?php if (!empty($_GET['error'])): ?>
                <div class="text-center mb-3">
                    <i style='color:red;'><?= htmlspecialchars($_GET['error']); ?></i>
                </div>
                <?php endif; ?>

                <div class="row">

                    <!-- Nom -->
                    <div class="col-md-6 mb-3">
                        <label><strong>NOM COMPLET :</strong></label>
                        <input type='text' name='nom' placeholder="Prenom NOM" style="border: 1px solid black;" class="form-control" required>
                    </div>

                    <!-- Login -->
                    <div class="col-md-6 mb-3">
                        <label><strong>LOGIN :</strong></label>
                        <input type='text' name='log' placeholder="Matricule de l'utilisateur..." style="border: 1px solid black;" class="form-control"
                            required>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6 mb-3">
                        <label><strong>E-MAIL :</strong></label>
                        <input type='email' name='mail' placeholder="Ex: exemple@coud.com" style="border: 1px solid black;" class="form-control"
                            required>
                    </div>

                    <!-- Telephone -->
                    <div class="col-md-6 mb-3">
                        <label><strong>TELEPHONE :</strong></label>
                        <input type='text' name='telephone' placeholder="+221..." style="border: 1px solid black;" class="form-control"
                            required>
                    </div>

                    <!-- Privilège -->
                    <div class="col-md-6 mb-3">
                        <label><strong>SEXE :</strong></label>
                        <select name="sexe" class="form-control" style="border: 1px solid black;" required>
                            <option value="">--Sélectionnez--</option>
                            <option value="M">M</option>
                            <option value="F">F</option>
                        </select>
                    </div>

                    <!-- Privilège -->
                    <div class="col-md-6 mb-3">
                        <label><strong>PRIVILEGE :</strong></label>
                        <select name="priv" class="form-control" style="border: 1px solid black;" required>
                            <option value="">--Sélectionnez--</option>
                            <option value="admin">ADMIN</option>
                            <option value="Cf_D">Chef_Departement</option>
                            <option value="or">Ordre_Recettes</option>
                            <option value="op">Ordre_Paiements</option>
                        </select>
                    </div>

                </div>
            </div>

            <!-- Boutons -->
            <div class="d-flex justify-content-between align-items-center mt-3 mx-auto" style="max-width: 700px;">
                <button type="submit" class="btn btn-success">
                    <strong>Enregistrer</strong>
                </button>

                <a href="javascript:history.back()" class="btn btn-danger">
                    <strong>Annuler</strong>
                </a>
            </div>

        </form>
    </div>
</main>

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
                 L'utilisateur a été enregistrée avec succès !
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

<?php include '../../includes/footer.php';
?>