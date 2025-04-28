<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../../index.php");
    exit();
}

require_once __DIR__ . '/../../../vendor/autoload.php';

include '../../../includes/fonctions.php';
$sommeDotations = sommeDot();
$sommeEngs = sommeEngs();
if ($sommeDotations != 0) {
    $taux = ($sommeEngs * 100) / $sommeDotations;
} else {
    $taux = 0; // Ou un autre comportement selon ton besoin
}

$execs1 = getExecutionOp_1();
$showRemanier = false;

// Première boucle pour vérifier s'il existe au moins une dotation remaniée
foreach ($execs1 as $exec) {
    if ($exec['totalDotRemanier'] != 0) {
        $showRemanier = true;
        break;
    }
}
// Capture HTML
ob_start();
?>

<main>
    <div class="text-center">
        <img src="/BUDGET/assets/images/logo.jpg" width="1020" height="90" alt="Logo">
    </div>
    <br>
    <div class='text-center' style='margin-bottom:20px;color:#4655a4;'>
    <h3>REALISATIONS: <?= number_format($sommeEngs, 0, ',', ','); ?>FCFA / <?= number_format($sommeDotations, 0, ',', ','); ?>FCFA soit <?= number_format($taux, 2); ?>%</h3>
    </div>

    <div class='container-fluid'>
        <div style='width: 100%; border-top: 3px solid #4655a4; border-bottom: 3px solid #4655a4; padding: 20px;'>
            <table style="width: 100%; font-size: 12px;">
                <thead style="color: white !important;">
                    <tr style="color: white !important;text-align:center;">
                        <th style="background-color: #4655a4; color: white;text-align:center;">C.P</th>
                        <th style="background-color: #4655a4; color: white;text-align:center;">Libelle</th>
                        <th style="background-color: #4655a4; color: white;text-align:center;">Dot_Initiale</th>
                        <?php if ($showRemanier): ?>
                            <th style="background-color: #4655a4; color: white;text-align:center;">Variation</th>
                            <th style="background-color: #4655a4; color: white;text-align:center;">Dot_Remaniee</th>
                        <?php endif; ?>
                        <th style="background-color: #4655a4; color: white;text-align:center;">Realisation</th>
                        <th style="background-color: #4655a4; color: white;text-align:center;">Taux</th>
                        <th style="background-color: #4655a4; color: white;text-align:center;">Disponible</th>
                        <th style="background-color: #4655a4;color: white;text-align:center;">O.P</th>
                        <th style="background-color: #4655a4;color: white;text-align:center;">Diff Eng/Op</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($execs1)) :
                        foreach ($execs1 as $exec) : ?>
                    <tr>
                        <td><?= $exec['numCp']; ?></td>
                        <td style='text-align:left;padding: 15px;max-width: 250px;'><?= $exec['libelle']; ?></td>
                        <td style='text-align:center;padding: 15px;'><?= number_format($exec['totalDotInitial'], 0, ',', ','); ?> f</td>
                        <?php if ($showRemanier): ?>
                        <td style='text-align: right;padding: 15px;'><?= number_format($exec['totalDotRemanier'], 0, ',', ','); ?> f</td>
                        <td style='text-align: right;padding: 15px;'><?= number_format($exec['totalDotations'], 0, ',', ','); ?> f</td>
                        <?php endif; ?>
                        <td style='text-align:center;padding: 15px;'> <?= number_format($exec['totalEngs'], 0, ',', ','); ?> f</td>
                        <td style='text-align:center;padding: 15px;'><?= number_format(($exec['taux']), 2); ?>%</td>
                        <td style='text-align:center;padding: 15px;'><?= number_format(($exec['totalDotations'] - $exec['totalEngs']), 0, ',', ','); ?> f</td>
                        <td style='text-align: right;padding: 15px;'><?= number_format($exec['totalOp'], 0, ',', ','); ?> f</td>
                        <td style='text-align: right;padding: 15px;'><?= number_format(($exec['totalEngs']-$exec['totalOp']), 0, ',', ','); ?> f</td>
                    </tr>
                    <?php endforeach;
                    else : ?>
                    <tr>
                        <td colspan="10" class="text-danger">Aucun résultat trouvé</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center" style="font-size: 13px; font-weight: 400; margin-top: 20px;">
        <p><strong>Généré automatiquement par le système de gestion budgétaire</strong></p>
    </div>
</main>

<?php
$html = ob_get_clean();

// Lecture du CSS Bootstrap local
$bootstrapCSS = file_get_contents(__DIR__ . '/../../../assets/bootstrap/dist/css/bootstrap.min.css');

$mpdf = new \Mpdf\Mpdf([
    'orientation' => 'L' // 'L' pour Landscape (paysage), 'P' pour Portrait
]);
$mpdf->WriteHTML('<style>' . $bootstrapCSS . '</style>', \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
$mpdf->WriteHTML('<style>
    table, th, td {
        border: 1px solid white;
        border-collapse: collapse;
    }
    th, td {
        padding: 8px;
        text-align: center;
    }
</style>', \Mpdf\HTMLParserMode::HEADER_CSS);


$mpdf->Output('etat_global-actuel.pdf', 'I');