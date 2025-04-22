<?php
session_start();
if ( !isset( $_SESSION[ 'user' ] ) ) {
    header( 'Location: ../index.php' );
    // Redirige vers la page de connexion
    exit();
}
?>
<?php include '../includes/fonctions.php';?>
<?php
$annee_connexion = $_SESSION['an']; // ex: 2023
$min_date = $annee_connexion . "-01-01";
$max_date = date("Y-m-d"); // aujourd'hui
?>
<?php include '../includes/header.php';?>
<?php 
    $nums = getNumCompteSansInitiale()
?>
<main>
    <div class='container'>
        <div class='text-center' style='margin-bottom:45px;color: #4655a4;'>
            <h3>Changement de mot de passe</h3>
            <p style='color:rgb(46, 176, 67);'>Veuillez saisir votre ancien mot de passe, puis entrer et confirmer le
                nouveau.</p>
        </div>
        <!-- Formulaire centré avec design -->
        <form action='traitement_password.php' method='post'>
            <div
                style='width: 70%; margin: 0 auto; border-top: 4px solid #4655a4; border-bottom: 4px solid #4655a4; padding: 20px;'>

                <table style='width: 70%; margin: 0 auto; text-align: left;'>
                    <?php if ( !empty( $_GET[ 'error' ] ) ): ?>
                    <tr>
                        <td colspan="2" style='padding: 10px 0;'>
                            <center><i class="alert alert-danger text-center" role="alert"
                                    style='color: red;'><?php echo $_GET[ 'error' ];?>!!</i></center>
                        </td>
                    </tr>
                    <?php endif;?>
                    <tr>
                        <td style='padding: 10px 0;'><strong>Ancien Mot de Passe :</strong></td>
                        <td style='padding: 10px 0;'>
                            <input type="password" name="old_password" style="width: 100%; padding: 5px;" required />
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 10px 0;'><strong>Nouveau mot de passe :</strong></td>
                        <td style='padding: 10px 0;'>
                            <input type="password" name="new_password" style="width: 100%; padding: 5px;" required />
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 10px 0;'><strong>Confirmer le nouveau mot de passe :</strong></td>
                        <td style='padding: 10px 0;'>
                            <input type="password" name="confirm_password" style="width: 100%; padding: 5px;"
                                required />

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

<?php include '../includes/footer.php';
?>