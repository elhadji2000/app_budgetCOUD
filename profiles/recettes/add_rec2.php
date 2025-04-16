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
    $numCompte = $_GET['numc'];
    $compte = getCompteByNum($numCompte);
    $nums = getEngsByNumCompteNonOperation($numCompte);
?>
<?php
$annee_connexion = $_SESSION['an']; // ex: 2023
$min_date = $annee_connexion . "-01-01";
$max_date = date("Y-m-d"); // aujourd'hui
?>
<div class='container'>
    <?php include '../../shared/menu.php';?>
</div>
<main>
    <div class='container'>
    <div class='text-center' style='margin-bottom:20px;color:#4655a4;'>
            <h3>ENREGISTREMENT DES ORDRES DE RECETTES</h3>
            <strong style='color:rgba(78, 120, 93, 0.91);'><i><?= htmlspecialchars($compte["numCompte"]) ?> :
                    <?= htmlspecialchars($compte["libelle"]) ?></i></strong>
        </div>

        <!-- Formulaire centré avec design -->
        <form action='../paiement/traitement_paie.php' method='POST'>
            <div
                style='width: 50%; margin: 0 auto; border-top: 4px solid #4655a4; border-bottom: 4px solid #4655a4; padding: 20px;'>
                <table style='width: 70%; margin: 0 auto; text-align: left;'>
                    <?php if ( !empty( $_GET[ 'error' ] ) ): ?>
                    <center><i class='text-center' style='color: red;'><?php echo $_GET[ 'error' ];?></i></center>
                    <?php endif;?>
                    <tr>
                        <td style='padding: 10px 0;'><strong>Numéro de l'Engagement :</strong></td>
                        <td style='padding: 10px 0;'>
                            <select name="idEng" style="width: 100%; padding: 7px;" required>
                                <option value="">Sélectionner un compte</option>
                                <?php foreach ($nums as $num) : ?>
                                <option value="<?= htmlspecialchars($num["idEng"]) ?>">
                                    <?= htmlspecialchars($num["idEng"]) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 10px 0;'><strong>Date O.R :</strong></td>
                        <td style='padding: 10px 0;'>
                            <input type='date' name='dateOr' style='width: 100%; padding: 5px;' required min="<?= $min_date ?>" max="<?= $max_date ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 10px 0;'><strong>Numéro de la Facture :</strong></td>
                        <td style='padding: 10px 0;'>
                            <input type='text' name='numFact' style='width: 100%; padding: 5px;' required />
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
<?php include '../../includes/footer.php';
?>