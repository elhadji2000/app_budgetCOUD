<?php
session_start();
if ( !isset( $_SESSION[ 'user' ] ) ) {
    header( 'Location: ../../index.php' );
    // Redirige vers la page de connexion
    exit();
}

?>
<?php include '../../includes/fonctions.php';?>
<?php
$annee_connexion = $_SESSION['an']; // ex: 2023
$min_date = $annee_connexion . "-01-01";
$max_date = date("Y-m-d"); // aujourd'hui
?>
<?php include '../../includes/header.php';?>
<?php 
    $nums = getNumCompte();
?>

<div class='container'>
    <?php include '../../shared/menu.php';?>
</div>
<main>
    <div class='container'>
        <div class='text-center' style='margin-bottom:40px;color:rgba(70, 86, 164, 0.84);'>
            <h3>MISE À JOUR APRES REMANIEMENT !</h3>
            <strong style='color:rgba(78, 120, 93, 0.91);'><i>NB: Le montant peut etre positif ou negatif selon que la
                    dotation ait augmentè ou diminuè.</i></strong>
        </div>

        <!-- Formulaire centré avec design -->
        <form action='traitement_dot.php' method='POST'>
            <div
                style='width: 50%; margin: 0 auto; border-top: 4px solid #4655a4; border-bottom: 4px solid #4655a4; padding: 20px;'>

                <table style='width: 70%; margin: 0 auto; text-align: left;'>
                    <?php if ( !empty( $_GET[ 'error' ] ) ): ?>
                    <center><i class='text-center' style='color: red;'><?php echo $_GET[ 'error' ];?></i></center>
                    <?php endif;?>
                    <tr>
                        <td style='padding: 10px 0;'><strong>Numéro du Compte :</strong></td>
                        <td style='padding: 10px 0;'>
                            <select name="rem_numc" style="width: 100%; padding: 7px;" required>
                                <option value="">Sélectionner un compte</option>
                                <?php foreach ($nums as $num) : ?>
                                <option value="<?= htmlspecialchars($num["idCompte"]) ?>">
                                    <?= htmlspecialchars($num["numCompte"]) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 10px 0;'><strong>Date de Remaniement :</strong></td>
                        <td style='padding: 10px 0;'>
                            <input type='date' name='rem_date' style='width: 100%; padding: 5px;' required min="<?= $min_date ?>" max="<?= $max_date ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 10px 0;'><strong>Montant Differentiel :</strong></td>
                        <td style='padding: 10px 0;'>
                            <input type='number' name='rem_volume' step='1' style='width: 100%; padding: 5px;' required />
                        </td>
                    </tr>
                </table>
            </div>
            <div style='width: 50%;' class="d-flex container justify-content-between align-items-center py-2 px-2"
                style="color:rgb(69, 47, 196); font-size: 18px; font-weight: 400;">
                <button type='submit' class='btn btn-success'><strong>Enregistrer</strong></button>
                <a href='javascript:history.back()' class='btn btn-danger mb-0 text-right'><strong>Annuler</strong></a>
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
                🎉 La dotation a été enregistrée avec succès !
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