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
<?php 
    $nums = getNumCompte();
?>

<div class='container'>
    <?php include '../../shared/menu.php';?>
</div>
<main>
    <div class='container'>
        <div class='text-center' style='margin-bottom:45px;color: #4655a4;'>
            <h3>RENSEIGNEMENT DE LA DOTATION INITIALE !!</h3>
        </div>

        <!-- Formulaire centré avec design -->
        <form action='add_eng2.php' method='POST'>
            <div
                style='width: 50%; margin: 0 auto; border-top: 4px solid #4655a4; border-bottom: 4px solid #4655a4; padding: 20px;'>

                <table style='width: 70%; margin: 0 auto; text-align: left;'>
                    <?php if ( !empty( $_GET[ 'error' ] ) ): ?>
                    <center><i class='text-center' style='color: red;'><?php echo $_GET[ 'error' ];?></i></center>
                    <?php endif;?>
                    <tr>
                        <td style='padding: 10px 0;'><strong>Numéro du Compte :</strong></td>
                        <td style='padding: 10px 0;'>
                            <select name="numc" style="width: 100%; padding: 7px;" required>
                                <option value="">Sélectionner un compte</option>
                                <?php foreach ($nums as $num) : ?>
                                <option value="<?= htmlspecialchars($num["numc"]) ?>">
                                    <?= htmlspecialchars($num["numc"]) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
            <div style='width: 50%;' class="d-flex container justify-content-between align-items-center py-2 px-2"
                style="color:rgb(69, 47, 196); font-size: 18px; font-weight: 400;">
                <button type='submit' class='btn btn-success'><strong>Valider</strong></button>
                <a href='javascript:history.back()' class='btn btn-danger mb-0 text-right'><strong>Annuler</strong></a>
            </div>
        </form>
    </div>
</main>
<?php include '../../includes/footer.php';
?>