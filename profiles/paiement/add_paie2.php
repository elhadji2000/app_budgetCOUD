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
    <div class='text-center' style='margin-bottom:20px;color:#4655a4;'>
            <h3>ENREGISTREMENT DES ORDRES DE PAIEMENTS</h3>
            <strong style='color:rgba(78, 120, 93, 0.91);'><i>6123 sous traitance restauration</i></strong>
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
                        <td style='padding: 10px 0;'><strong>Numero OP :</strong></td>
                        <td style='padding: 10px 0;'>
                            <input type='number' name='montant' step='1' style='width: 100%; padding: 5px;' required />
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 10px 0;'><strong>Numéro de l'Engagement :</strong></td>
                        <td style='padding: 10px 0;'>
                            <select name="numc" style="width: 100%; padding: 7px;" required>
                                <option value="">Sélectionner un compte</option>
                                <?php foreach ($nums as $num) : ?>
                                <option value="<?= htmlspecialchars($num["numCompte"]) ?>">
                                    <?= htmlspecialchars($num["numCompte"]) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 10px 0;'><strong>Date OP :</strong></td>
                        <td style='padding: 10px 0;'>
                            <input type='date' name='dateRemanie' style='width: 100%; padding: 5px;' required />
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 10px 0;'><strong>Numéro de la Facture :</strong></td>
                        <td style='padding: 10px 0;'>
                            <input type='text' name='montant' style='width: 100%; padding: 5px;' required />
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