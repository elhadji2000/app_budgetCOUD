<?php
session_start();
if ( !isset( $_SESSION[ 'user' ] ) ) {
    header( 'Location: ../../index.php' );
    // Redirige vers la page de connexion
    exit();
}

?>
<?php include '../../includes/fonctions.php';
$numCompte = $_GET['numc'];
$idCompte = getIdCompteByNum($numCompte);
$data = getCompteByNum($numCompte);
$details = getDetailsCompte($numCompte);
$fourns = getAllFourniseurs();
?>
<?php
// Sécurité : Initialisation si valeurs manquantes
$details['dotationInitiale'] = $details['dotationInitiale'] ?? 0;
$details['dotationRemaniee'] = $details['dotationRemaniee'] ?? 0;
$details['totalEngagement'] = $details['totalEngagement'] ?? 0;
$details['ecart'] = $details['ecart'] ?? 0;
$tresoreri = ($details['dotationInitiale'] + $details['dotationRemaniee'])
?>
<?php
$annee_connexion = $_SESSION['an']; // ex: 2023
$min_date = $annee_connexion . "-01-01";
$max_date = date("Y-m-d"); // aujourd'hui
?>
<?php include '../../includes/header.php';?>

<div class='container'>
    <?php include '../../shared/menu.php';?>
</div>
<main>
    <!-- ##########  ESPACE POUR DETAILS DES ENGEMENT DU COMPTE DONNER ################ -->
    <div class='container' style='margin-bottom:40px;'>
        <div class='text-center' style='margin-bottom:20px;color:#4655a4;'>
            <strong style='color:rgba(78, 120, 93, 0.91);'><i><?= $data['numCompte']; ?>:
                    <?= $data['libelle']; ?></i></strong>
            <h4>SITUATION ACTUEL DU COMPTE !!</h4>
        </div>

        <!-- Formulaire centré avec design -->
        <div
            style='width: 50%; margin: 0 auto; border-top: 4px solid #4655a4; border-bottom: 4px solid #4655a4; padding: 10px;'>
            <table style='width: 100%; margin: 0 auto;'>
                <tr>
                    <td class="text-left" style='padding: 5px; text-align: left;'><strong>DOTATION INITIALE</strong>
                    </td>
                    <td style='padding: 5px; text-align: right;'>
                        <strong><?= number_format($details['dotationInitiale'], 0, ',', ','); ?> FCFA</strong>
                    </td>
                </tr>

                <tr>
                    <td style='padding: 5px; text-align: left;'><strong>DOTATION REMANIEE</strong></td>
                    <td style='padding: 5px; text-align: right;'>
                        <strong><?= number_format($details['dotationRemaniee'], 0, ',', ','); ?> FCFA</strong>
                    </td>
                </tr>
                <tr>
                    <td style='padding: 5px; text-align: left;'><strong>TOTAL ENGAGEMENT</strong></td>
                    <td style='padding: 5px; text-align: right;'>
                        <a href="#"><strong><?= number_format($details['totalEngagement'], 0, ',', ','); ?>
                                FCFA</strong></a>
                    </td>
                </tr>
                <tr>
                    <td style='padding: 5px; text-align: left;'><strong>ECART</strong></td>
                    <td style='padding: 5px; text-align: right;color: green;'>
                        <strong><?= number_format($details['ecart'], 0, ',', ','); ?> FCFA</strong>
                    </td>
                </tr>
                <tr>
                    <td style='padding: 5px; text-align: left;'><strong>TOTAL DES O.P</strong></td>
                    <td style='padding: 5px; text-align: right;'>
                        <strong><?= number_format($details['O.P'], 0, ',', ','); ?> FCFA</strong>
                    </td>
                </tr>
                <tr>
                    <td style='padding: 5px; text-align: left;'><strong>REGLEMENTS EFFECTIS</strong></td>
                    <td style='padding: 5px; text-align: right;'><strong>0</strong></td>
                </tr>
                <tr>
                    <td style='padding: 5px; text-align: left;'><strong>SOLDE DE TRESORIE ACTUEL</strong></td>
                    <td style='padding: 5px; text-align: right;'>
                        <strong><?= number_format($tresoreri, 0, ',', ','); ?> FCFA</strong>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <!-- ################## 2222 ###################### -->
    <!-- ################## AJOUTER ENGAGEMENT CI_DESSOUS ###################### -->
    <div class='container'>
        <div class='text-center' style='margin-bottom:15px;color:#4655a4;'>
            <h4>NOUVEL ENGAGEMENT </h4>
        </div>

        <!-- Formulaire centré avec design -->
        <form action='traitement_eng.php' method='POST'>
            <div
                style='width: 50%; margin: 0 auto; border-top: 4px solid #4655a4; border-bottom: 4px solid #4655a4; padding: 5px;'>

                <table style='width: 90%; margin: 0 auto; text-align: left;'>
                    <?php if ( !empty( $_GET[ 'error' ] ) ): ?>
                    <center><i class='text-center' style='color: red;'><?php echo $_GET[ 'error' ];?></i></center>
                    <?php endif;?>
                    <tr>
                        <td style='padding: 5px 0;'><strong>NUMERO DU COMPTE :</strong></td>
                        <td style='padding: 5px 0;'>
                            <input type="hidden" name="idCompte" value="<?= htmlspecialchars($idCompte) ?>" />
                            <input type='text' value="<?= $numCompte; ?>" readonly style='width: 100%; padding: 5px;'
                                required />
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 5px 0;'><strong>DATE ENGAGEMENT :</strong></td>
                        <td style='padding: 5px 0;'>
                            <input type="date" name="dateEng" style="width: 100%; padding: 5px;" required
                                min="<?= $min_date ?>" max="<?= $max_date ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 5px 0;'><strong>OBJET :</strong></td>
                        <td style='padding: 5px 0;'>
                            <input type='text' name='libelle' style='width: 100%; padding: 5px;' required />
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 5px 0;'><strong>REFERENCE :</strong></td>
                        <td style='padding: 5px 0;'>
                            <input type='text' name='bc' style='width: 100%; padding: 5px;' required />
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 5px 0;'><strong>SERVICE CONSERNÈ :</strong></td>
                        <td style='padding: 5px 0;'>
                            <input type='text' name='service' style='width: 100%; padding: 5px;' required />
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 5px 0;'><strong>FOURNISSEUR :</strong></td>
                        <td style='padding: 5px 0;'>
                            <select name="idFourn" style="width: 100%; padding: 8px;" required>
                                <option value="">Sélectionner un fournisseur</option>
                                <?php foreach ($fourns as $fourn) : ?>
                                <option value="<?= htmlspecialchars($fourn["idFourn"]) ?>">
                                    <?= htmlspecialchars($fourn["numFourn"]) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 5px 0;'><strong>MONTANT :</strong></td>
                        <td style='padding: 5px 0;'>
                            <input type='text' name='montant' style='width: 100%; padding: 5px;' required />
                        </td>
                    </tr>
                </table>
            </div>
            <div style='width: 50%;' class="d-flex container justify-content-between align-items-center py-2 px-2"
                style="color:rgb(69, 47, 196); font-size: 13px; font-weight: 400;">
                <button type='submit' class='btn btn-success'><strong>Enregistrer</strong></button>
                <a href='javascript:history.back()' class='btn btn-danger mb-0 text-right'><strong>Annuler</strong></a>
            </div>
        </form>
    </div>

    <script>
    document.querySelector("form").addEventListener("submit", function(e) {
        const montantInput = document.querySelector("input[name='montant']");
        const montant = parseFloat(montantInput.value);
        const ecartDisponible = <?= $details['ecart']; ?>; // récupéré du PHP

        if (montant < 0) {
            alert("Le montant ne peut pas être inférieur à zéro.");
            e.preventDefault();
            return;
        }

        if (montant > ecartDisponible) {
            alert("Le montant ne peut pas dépasser l'écart disponible (" + ecartDisponible.toLocaleString() +
                " FCFA).");
            e.preventDefault();
            return;
        }
    });
    </script>

</main>

<?php include '../../includes/footer.php';
?>