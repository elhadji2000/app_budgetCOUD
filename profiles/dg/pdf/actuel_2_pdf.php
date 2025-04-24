<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../../index.php");
    exit();
}

require_once __DIR__ . '/../../../vendor/autoload.php';

include '../../../includes/fonctions.php';
$idCp = $_GET['idCp'];
$comptep = getComptePById($idCp);
$TDotations = 0;
$TEngs = 0;
// Capture HTML
ob_start();
?>

<main>
    <div class="text-center">
        <img src="/BUDGET/assets/images/logo.jpg" width="1020" height="100" alt="Logo">
    </div>
    <br>
    <div class='text-center' style='margin-bottom:20px;color:#4655a4;'>
        <h2>Etat du compte principal <?= $comptep['numCp']; ?> : <?= $comptep['libelle']; ?></h2>
    </div>

    <!-- Tableau -->
    <div class='container-fluid'>
        <div style='width: 100%; border-top: 4px solid #4655a4; border-bottom: 4px solid #4655a4; padding: 20px;'>
            <table style="width: 100%; font-size: 15px;">
                <thead style="color: white !important;">
                    <tr style="color: white !important;text-align:center;">
                        <th style="background-color: #4655a4; color: white;text-align:center;">C_P</th>
                        <th style="background-color: #4655a4; color: white;text-align:center;">Libelle</th>
                        <th style="background-color: #4655a4; color: white;text-align:center;">Dotations</th>
                        <th style="background-color: #4655a4; color: white;text-align:center;">Realisation</th>
                        <th style="background-color: #4655a4; color: white;text-align:center;">Taux</th>
                        <th style="background-color: #4655a4; color: white;text-align:center;">Disponible</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php
                $execs1 = getExecution_2($idCp);
                $n=1;
                if (!empty($execs1)) :
                    foreach ($execs1 as $exec) : ?>
                    <tr>
                        <td style='text-align:center;padding: 15px;'><?= $exec['numCompte']; ?></td>
                        <td style="text-align:left; padding: 15px; max-width: 200px;">
                            <?= $exec['libelleC']; ?>
                        </td>

                        <td style='text-align:center;padding: 15px;'>
                            <?= number_format($exec['totalDotations'], 0, ',', ','); ?> F
                        </td>
                        <td style='text-align:center;padding: 15px;'>
                            <a href="actuel_3.php?numCompte=<?php echo $exec['numCompte']; ?>"><?= number_format($exec['totalEngs'], 0, ',', ','); ?> F</a>
                        </td>
                        <td style='text-align:center;padding: 15px;'><?= number_format($exec['taux'], 2); ?>%</td>
                        <td style='text-align:center;padding: 15px;'>
                            <?= number_format(($exec['totalDotations']-$exec['totalEngs']), 0, ',', ','); ?> F</td>
                    </tr>
                    <?php 
                    $TDotations += $exec['totalDotations'];
                    $TEngs += $exec['totalEngs'];
                    endforeach;?>
                    <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-danger">Aucune recette trouvée</td>
                    </tr>
                    <?php endif; ?>

                </tbody>
                <tfooter>
                    <tr>
                        <th colspan="2" style="background-color: #4655a4;text-align:center;color: white;">Total principal</th>
                        <th style="background-color: #4655a4;text-align:center;color: white;">
                            <?= number_format($TDotations, 0, ',', ','); ?> F</th>
                        <th style="background-color: #4655a4;text-align:center;color: white;">
                            <?= number_format($TEngs, 0, ',', ','); ?> F</th>
                        <th style="background-color: #4655a4;text-align:center;color: white;">-</th>
                        <th style="background-color: #4655a4;text-align:center;color: white;">
                            <?=number_format(($TDotations - $TEngs) , 0, ',', ','); ?> F</th>
                    </tr>
                </tfooter>
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


$mpdf->Output('etat-actuel.pdf', 'I');