<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: /BUDGET/index.php"); // Redirige vers la page de connexion
    exit();
}
?>

<?php include '../includes/header.php';?>
<main>
    <div class='container'>
        <?php include 'menu.php';?>
    </div>

    <div class="text-center container" style="margin-bottom: 50px; margin-top: 70px;">
        <a href="http://localhost/BUDGET/shared/accueil.php">
            <img src="/BUDGET/assets/images/logo-du-coud.jpg" width="600" height="250" alt="Logo-du-coud">
        </a>
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
                🎉 votre mot de passe a été modifiè avec succès !
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

<?php include '../includes/footer.php';?>