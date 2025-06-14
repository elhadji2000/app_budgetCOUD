<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php"); // Redirige vers la page de connexion
    exit();
}
?>
<?php include '../../includes/fonctions.php';
$numEng = $_GET['id'];
$engagement = getEngById($numEng);
$details = getDetailsCompte($engagement['numCompte']);
?>
<?php
// Sécurité : Initialisation si valeurs manquantes
$details['dotationInitiale'] = $details['dotationInitiale'] ?? 0;
$details['dotationRemaniee'] = $details['dotationRemaniee'] ?? 0;
$totalEngagement = $details['totalEngagement'] ?? 0;
$ecart = $details['ecart'] ?? 0;
$tresoreri = ($details['dotationInitiale'] + $details['dotationRemaniee'])
?>
<?php include '../../includes/header.php';?>
<main>
    <div class='container'>
        <?php include '../../shared/menu.php';?>
    </div>

    <div class='container' style="margin-bottom: 20px;">
        <?php
            $date = date('d/m/Y'); // ou une autre date d'engagement récupérée en BDD
        ?>
        <div
            style='width: 100%; margin: 0 auto; border-top: 4px solid #4655a4; border-bottom: 4px solid #4655a4; padding: 20px;'>

            <table
                style="width: 100%; border-collapse: separate; border-spacing: 0 10px; font-family: Arial, sans-serif;">
                <!-- Ligne 1 : Logo, Titre, Date -->
                <tr>
                    <td style="text-align: center; width: 30%; border: 1px solid black; padding: 8px;">
                        <img src="/BUDGET/assets/images/logo-du-coud.jpg" width="70%" height="100" alt="Logo" />
                    </td>

                    <td style="text-align: center; width: 40%; border: 1px solid black; padding: 8px;">
                        <p><strong style="font-size: 18px;">BON ENGAGEMENT</strong></p>
                        <p> <strong style="font-size: 16px;">
                                N°
                                <?= formatNumEng($engagement['idEng']); ?>
                            </strong>
                        </p>
                    </td>

                    <td style="text-align: right; width: 30%; border: 1px solid black; padding: 8px;">
                        <p><strong style="margin: 0;">GESTION <?= $_SESSION['an']; ?></strong></p>
                        <p><strong>Dakar, le </strong> <?= date('d/m/Y', strtotime($engagement['dateEng'])); ?></p>
                    </td>
                </tr>

                <!-- Ligne 2 : Dépenses, Imputation, Visa -->
                <tr>
                    <td style="border: 1px solid black; padding: 10px; vertical-align: top;">
                        <p><b>Dépenses</b></p>
                        <p>Type : <i><?= $engagement['service']; ?></i></p>
                        <p>Nature : <?= $engagement['libelleCp']; ?></i></p>
                        <p>Objet : <i><?= $engagement['libelle']; ?></i></p>
                        <p>Montant : <i style="float: right;"><?= number_format($engagement['montant'], 0, ',', ','); ?>
                                FCFA</i></p>
                    </td>
                    <td style="border: 1px solid black; padding: 10px; vertical-align: top;">
                        <p><b>Imputation</b></p>
                        <p>Budget : <i><?= $engagement['libelleCp']; ?></i></p>
                        <p>Numéro compte principal : <i><?= $engagement['numCp']; ?></i></p>
                        <p>Numéro compte d'imputation : <i><?= $engagement['numCompte']; ?></i></p>
                        <p>Libellé : <i><?= $engagement['libelleC']; ?></i></p>
                    </td>
                    <td
                        style="text-align: center; border: 1px solid black; padding: 10px; text-decoration: underline; vertical-align: top;">
                        <p><b>Visa du Chef de Département du Budget</b></p>
                    </td>
                </tr>


                <!-- Ligne 3 : Beneficiaire, Situation de Engagements, Siganture -->
                <tr>
                    <td style="border: 1px solid black; padding: 10px; vertical-align: top;">
                        <p><b>BENEFICIAIRE</b></p>
                        <p style="margin-left: 40px; text-transform: uppercase;"><i><?= $engagement['nom']; ?></i></p>
                        <p><b>SERVIVE</b></p>
                        <p style="margin-left: 40px; text-transform: uppercase;"><i>COUD</i></p>
                    </td>
                    <td style="border: 1px solid black; padding: 10px; vertical-align: top;">
                        <p><b>SITUATION DES ENGAGEMENTS</b></p>
                        <p>Crédits ouverts : <i style="float: right;"><?= number_format($tresoreri, 0, ',', ','); ?>
                                FCFA</i></p>
                        <p>Modification de crédits :</p>
                        <p>Engagements antérieurs :</p>
                        <p>Annulation d"engagement :</p>
                        <p>Disponible avant le bon : <i
                                style="float: right;"><?= number_format($tresoreri, 0, ',', ','); ?>
                                FCFA</i></p>
                        <p>Nouveau disponible : <i style="float: right;"><?= number_format($ecart, 0, ',', ','); ?>
                                FCFA</i>
                        </p>
                    </td>
                    <td
                        style="text-align: center; border: 1px solid black; padding: 10px; text-decoration: underline; vertical-align: top;">
                        <p><b>Signature de l'Ordonnateur</b></p>
                    </td>
                </tr>
            </table>
        </div>

        <div style='width: 90%;' class="d-flex container justify-content-between align-items-center py-2 px-2"
            style="color:rgb(69, 47, 196); font-size: 18px; font-weight: 400;">
            <a  href="../dg/pdf/bonEng_pdf.php?id=<?php echo $numEng; ?>" target="_blank" class='btn btn-success'><strong>Imprimer en PDF</strong></a>
            <a href='javascript:history.back()' class='btn btn-danger mb-0 text-right'><strong>Annuler</strong></a>
        </div>

    </div>

</main>
<?php include '../../includes/footer.php';?>