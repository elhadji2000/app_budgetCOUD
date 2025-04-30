<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../../index.php");
    exit();
}

require_once __DIR__ . '/../../../vendor/autoload.php';

include '../../../includes/fonctions.php';
$numEng = $_GET['id'];
$engagement = getEngById($numEng);
$details = getDetailsCompte($engagement['numCompte']);

// Sécurité : Initialisation si valeurs manquantes
$details['dotationInitiale'] = $details['dotationInitiale'] ?? 0;
$details['dotationRemaniee'] = $details['dotationRemaniee'] ?? 0;
$totalEngagement = $details['totalEngagement'] ?? 0;
$ecart = $details['ecart'] ?? 0;
$tresoreri = ($details['dotationInitiale'] + $details['dotationRemaniee']);
// Capture HTML
ob_start();
?>

<main>
    <div class='container-fluid' style="margin-bottom: 20px;font-size: 16px;">

        <table style="width: 100%; border: 1px solid black; font-family: Arial, sans-serif;margin-bottom:5px;">
            <!-- Ligne 1 : Logo, Titre, Date -->
            <tr>
                <td style="text-align: center; width: 30%; padding: 8px;">
                    <img src="/BUDGET/assets/images/logo-du-coud.jpg" width="20%" height="100" alt="Logo" />
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
        <table
            style="width: 100%;border: 1px solid black; border-collapse: collapse;font-family: Arial, sans-serif;margin-bottom:5px;">
            <!-- Ligne 1 : Logo, Titre, Date -->
            <tr style="width: 100%;border: 1px solid black; border-collapse: collapse; font-family: Arial, sans-serif;">
                <td style="text-align: center; width: 50%; border: 1px solid black; padding: 8px;vertical-align: top;">
                    <p>
                        <strong>OBJET DE LA DEPENSE</strong>
                    </p>
                    <br>
                    <br>
                    <p>
                        <?= strtoupper($engagement['libelle']); ?>
                    </p>
                </td>
                <td style="text-align: center; width: 50%; border: 1px solid black; padding: 8px;vertical-align: top;">
                    <p>Montant ( en chiffres ) : <?= number_format($engagement['montant'], 0, ',', ','); ?> FCFA</p>
                    <br>
                    <p style="float: right;">En ( lettres ) : <?= nombreEnLettres($engagement['montant']); ?> francs
                        CFA</p>
                </td>
            </tr>
        </table>

        <table
            style="width: 100%;border: 1px solid black; border-collapse: collapse; font-family: Arial, sans-serif; margin-bottom: 5px;">
            <!-- Ligne 1 : Pièces justificatives & Règlement -->
            <tr style="width: 100%;border: 1px solid black; border-collapse: collapse; font-family: Arial, sans-serif;">
                <td style="text-align: center; width: 50%; border: 1px solid black; padding: 8px; vertical-align: top;">
                    <p><strong>PIÈCES JUSTIFICATIVES</strong></p><br><br>
                    <p>FACTURE N°<?= strtoupper($engagement['numFact']); ?> DU
                        <?= date('d/m/Y', strtotime($engagement['dateOp'])); ?></p>
                </td>

                <td rowspan="2"
                    style="text-align: left; width: 50%; border: 1px solid black; padding: 8px; vertical-align: top;">
                    <p><strong>RÈGLEMENT</strong></p><br>
                    <div style="text-align: left; font-size: 16px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <span style="min-width: 200px;">Mode :</span>
                            <span style="flex: 1; height: 25px;"></span>
                        </div>
                        <br>
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <span style="min-width: 200px;">Etablissement :</span>
                            <span style="flex: 1; height: 25px;"></span>
                        </div>
                        <br>
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <span style="min-width: 200px;">Code établissement :</span>
                            <span style="flex: 1; height: 25px;"></span>
                        </div>
                        <br>
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <span style="min-width: 200px;">Numéro Compte :</span>
                            <span style="flex: 1; height: 25px;"></span>
                        </div>
                        <br>
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <span style="min-width: 200px;">Ville :</span>
                            <span style="flex: 1;height: 25px;"></span>
                        </div>
                        <br>
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <span style="min-width: 200px;">Code Guichet :</span>
                            <span style="flex: 1;height: 25px;"></span>
                        </div>
                        <br>
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <span style="min-width: 200px;">RIB :</span>
                            <span style="flex: 1;height: 25px;"></span>
                        </div>
                        <br>
                    </div>
                    <br>
                </td>
            </tr>

            <!-- Ligne 2 : Bénéficiaire -->
            <tr style="width: 100%;border: 1px solid black; border-collapse: collapse; font-family: Arial, sans-serif;">
                <td style="text-align: center; border: 1px solid black; padding: 8px; vertical-align: top;">
                    <p><strong>BÉNÉFICIAIRE</strong></p><br>
                    <p><strong><?= strtoupper($engagement['nom']); ?></strong></p>
                </td>
            </tr>

            <!-- Ligne 3 : Engagement -->
            <tr style="width: 100%;height:300px;border: 1px solid black; border-collapse: collapse; font-family: Arial, sans-serif;">
                <td style="text-align: left; width: 50%; border: 1px solid black; padding: 8px; vertical-align: top;">
                    <p><strong>ENGAGEMENT</strong></p><br>
                    <div style="text-align: left; font-size: 16px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <span style="min-width: 150px;">NUMERO :</span>
                            <span
                                style="flex: 1; border-bottom: 1px solid black; height: 25px;"><?= formatNumEng($engagement['idEng']); ?></span>
                        </div>
                        <br>
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <span style="min-width: 150px;">DATE :</span>
                            <span
                                style="flex: 1; border-bottom: 1px solid black; height: 25px;"><?= date('d/m/Y', strtotime($engagement['dateEng'])); ?>
                                </p></span>
                        </div>
                        <br>
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <span style="min-width: 150px;">MONTANT :</span>
                            <span
                                style="flex: 1; border-bottom: 1px solid black; height: 25px;width:50%;"><?= number_format($engagement['montant'], 0, ',', ','); ?>
                                FCFA</span>
                        </div>
                        <br>
                    </div>
                    <br>
                    <p><strong><b>SERVICE</b></strong></p>
                    <p><strong>COUD</strong></p>
                    <br>
                </td>

                <td style="text-align: center; width: 50%; border: 1px solid black; padding: 8px; vertical-align: top;">
                    <p><strong>Cadre réservé à l'Ordonnateur</strong></p>
                </td>
            </tr>

            <!-- Ligne 5 : imputation -->
            <tr style="width: 100%;height:300px;border: 1px solid black; border-collapse: collapse; font-family: Arial, sans-serif;">
                <td style="text-align: left; width: 50%; border: 1px solid black; padding: 8px; vertical-align: top;">
                    <p style="text-align: center;"><strong>ENGAGEMENT</strong></p><br>
                    <div style="text-align: left; font-size: 16px;">
                        <div style="display: flex;text-align: left; align-items: left; margin-bottom: 8px;">
                            <span style="min-width: 200px;text-align: left;">COMPTE PRINCIPAL :</span>
                            <span
                                style="flex: 1; border-bottom: 1px solid black; height: 25px;"><?= $engagement['numCp']; ?></span>
                        </div>
                        <br>
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <span style="min-width: 200px;">COMPTE D'IMPUTATION :</span>
                            <span
                                style="flex: 1; border-bottom: 1px solid black; height: 25px;"><?= $engagement['numCompte']; ?>
                                </p></span>
                        </div>
                        <br>
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <span style="min-width: 200px;">LIBELLE :</span>
                            <span
                                style="flex: 1; border-bottom: 1px solid black; height: 25px;width:50%;"><?= $engagement['libelleCp']; ?></span>
                        </div>
                        <br>
                    </div>
                    <br><br>
                </td>

                <td style="text-align: center; width: 50%; border: 1px solid black; padding: 8px; vertical-align: top;">
                    <p><b>Cadre réservé au Comptable</b></p>
                </td>
            </tr>
        </table>


    </div>

    <div class="text-center" style="font-size: 12px; font-weight: 400;">
        <p><strong>Généré automatiquement par le système de gestion budgétaire</strong></p>
    </div>
</main>


<?php
$html = ob_get_clean();

// Lecture du CSS Bootstrap local
$bootstrapCSS = file_get_contents(__DIR__ . '/../../../assets/bootstrap/dist/css/bootstrap.min.css');

$mpdf = new \Mpdf\Mpdf([
    'orientation' => 'P' // 'L' pour Landscape (paysage), 'P' pour Portrait
]);
$mpdf->WriteHTML('<style>' . $bootstrapCSS . '</style>', \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
$mpdf->WriteHTML('<style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
    th, td {
        padding: 8px;
        text-align: center;
    }
</style>', \Mpdf\HTMLParserMode::HEADER_CSS);


$mpdf->Output('etat-actuel.pdf', 'I');