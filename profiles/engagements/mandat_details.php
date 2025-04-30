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
        <div
            style='width: 100%; margin: 0 auto; border-top: 3px solid #4655a4; border-bottom: 3px solid #4655a4; padding: 20px;font-size:18px;'>

            <table style="width: 100%; border: 1px solid black; font-family: Arial, sans-serif;margin-bottom:5px;">
                <!-- Ligne 1 : Logo, Titre, Date -->
                <tr>
                    <td style="text-align: center; width: 30%; padding: 8px;">
                        <img src="/BUDGET/assets/images/logo-du-coud.jpg" width="70%" height="100" alt="Logo" />
                    </td>

                    <td style="text-align: center; width: 40%; padding: 8px;">
                        <p><strong style="font-size: 18px;">MANDAT DE PAIEMENT</strong></p>
                        <p> <strong style="font-size: 16px;">
                                N°
                                <?= formatNumOP($engagement['idOp']); ?>
                            </strong>
                        </p>
                    </td>

                    <td style="text-align: right; width: 30%; padding: 8px;">
                        <p><strong style="margin: 0;">GESTION <?= $_SESSION['an']; ?></strong></p>
                        <p><strong>Dakar, le </strong> <?= date('d/m/Y', strtotime($engagement['dateOp'])); ?></p>
                    </td>
                </tr>
            </table>
            <table style="width: 100%; border-collapse: collapse;font-family: Arial, sans-serif;margin-bottom:5px;">
                <!-- Ligne 1 : Logo, Titre, Date -->
                <tr>
                    <td
                        style="text-align: center; width: 50%; border: 1px solid black; padding: 8px;vertical-align: top;">
                        <p>
                            <strong>OBJET DE LA DEPENSE</strong>
                        </p>
                        <br>
                        <br>
                        <p>
                            <?= strtoupper($engagement['libelle']); ?>
                        </p>
                    </td>
                    <td
                        style="text-align: center; width: 50%; border: 1px solid black; padding: 8px;vertical-align: top;">
                        <p>Montant ( en chiffres ) : <?= number_format($engagement['montant'], 0, ',', ','); ?> FCFA</p>
                        <br>
                        <p style="float: right;">En ( lettres ) : <?= nombreEnLettres($engagement['montant']); ?> francs
                            CFA</p>
                    </td>
                </tr>
            </table>

            <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; margin-bottom: 5px;">
                <!-- Ligne 1 : Pièces justificatives & Règlement -->
                <tr>
                    <td
                        style="text-align: center; width: 50%; border: 1px solid black; padding: 8px; vertical-align: top;">
                        <p><strong>PIÈCES JUSTIFICATIVES</strong></p><br><br>
                        <p>FACTURE N°<?= strtoupper($engagement['numFact']); ?> DU
                            <?= date('d/m/Y', strtotime($engagement['dateOp'])); ?></p>
                    </td>

                    <td rowspan="2"
                        style="text-align: center; width: 50%; border: 1px solid black; padding: 8px; vertical-align: top;">
                        <p><strong>RÈGLEMENT</strong></p><br>
                        <div style="text-align: left; font-size: 16px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <span style="min-width: 200px;">Mode :</span>
                                <span style="flex: 1; border-bottom: 1px solid black; height: 25px;"></span>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <span style="min-width: 200px;">Etablissement :</span>
                                <span style="flex: 1; border-bottom: 1px solid black; height: 25px;"></span>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <span style="min-width: 200px;">Code établissement :</span>
                                <span style="flex: 1; border-bottom: 1px solid black; height: 25px;"></span>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <span style="min-width: 200px;">Numéro Compte :</span>
                                <span style="flex: 1; border-bottom: 1px solid black; height: 25px;"></span>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <span style="min-width: 200px;">Ville :</span>
                                <span style="flex: 1; border-bottom: 1px solid black; height: 25px;"></span>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <span style="min-width: 200px;">Code Guichet :</span>
                                <span style="flex: 1; border-bottom: 1px solid black; height: 25px;"></span>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <span style="min-width: 200px;">RIB :</span>
                                <span style="flex: 1; border-bottom: 1px solid black; height: 25px;"></span>
                            </div>
                        </div>

                    </td>
                </tr>

                <!-- Ligne 2 : Bénéficiaire -->
                <tr>
                    <td style="text-align: center; border: 1px solid black; padding: 8px; vertical-align: top;">
                        <p><strong>BÉNÉFICIAIRE</strong></p><br>
                        <p><strong><?= strtoupper($engagement['nom']); ?></strong></p>
                    </td>
                </tr>

                <!-- Ligne 3 : Engagement -->
                <tr>
                    <td
                        style="text-align: center; width: 50%; border: 1px solid black; padding: 8px; vertical-align: top;">
                        <p><strong>ENGAGEMENT</strong></p><br>
                        <div style="text-align: left; font-size: 16px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <span style="min-width: 150px;">NUMERO :</span>
                                <span style="flex: 1; border-bottom: 1px solid black; height: 25px;"><?= formatNumEng($engagement['idEng']); ?></span>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <span style="min-width: 150px;">DATE :</span>
                                <span style="flex: 1; border-bottom: 1px solid black; height: 25px;"><?= date('d/m/Y', strtotime($engagement['dateEng'])); ?></p></span>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <span style="min-width: 150px;">MONTANT :</span>
                                <span style="flex: 1; border-bottom: 1px solid black; height: 25px;"><?= number_format($engagement['montant'], 0, ',', ','); ?> FCFA</span>
                            </div>
                        </div>
                        <br>
                        <p><strong>SERVICE</strong></p>
                        <p><strong>COUD</strong></p>
                    </td>

                    <td
                        style="text-align: center; width: 50%; border: 1px solid black; padding: 8px; vertical-align: top;">
                        <p><strong>Cadre réservé à l'Ordonnateur</strong></p>
                    </td>
                </tr>

                <!-- Ligne 5 : imputation -->
                <tr>
                    <td
                        style="text-align: center; width: 50%; border: 1px solid black; padding: 8px; vertical-align: top;">
                        <p><strong>ENGAGEMENT</strong></p><br>
                        <div style="text-align: left; font-size: 16px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <span style="min-width: 200px;">COMPTE PRINCIPAL :</span>
                                <span style="flex: 1; border-bottom: 1px solid black; height: 25px;"><?= $engagement['numCp']; ?></span>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <span style="min-width: 200px;">COMPTE D'IMPUTATION :</span>
                                <span style="flex: 1; border-bottom: 1px solid black; height: 25px;"><?= $engagement['numCompte']; ?></p></span>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <span style="min-width: 200px;">LIBELLE :</span>
                                <span style="flex: 1; border-bottom: 1px solid black; height: 25px;width:50%;"><?= $engagement['libelleCp']; ?></span>
                            </div>
                        </div>
                    </td>

                    <td
                        style="text-align: center; width: 50%; border: 1px solid black; padding: 8px; vertical-align: top;">
                        <p><strong>Cadre réservé au Comptable</strong></p>
                    </td>
                </tr>
            </table>


        </div>

        <div style='width: 90%;' class="d-flex container justify-content-between align-items-center py-2 px-2"
            style="color:rgb(69, 47, 196); font-size: 18px; font-weight: 400;">
            <a href="../dg/pdf/mandat_pdf.php?id=<?php echo $numEng; ?>" target="_blank" class='btn btn-success'><strong>Imprimer en PDF</strong></a>
            <a href="javascript:history.back()" class="btn btn-danger text-center"><strong>Retour</strong></a>
        </div>
    </div>
</main>
<?php include '../../includes/footer.php';?>