<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Connexion à la base de données
include '../../../includes/fonctions.php';

// Ajouter les headers pour Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="exportation.xls"');
header('Cache-Control: max-age=0');

// Récupérer les données
$sommeDotations = sommeDot();
$sommeEngs = sommeEngs();
$tauxGlobal = ($sommeDotations != 0) ? ($sommeEngs * 100) / $sommeDotations : 0;

$execs1 = getExecution_1();
$showRemanier = false;

// Vérifier s'il y a au moins une dotation remaniée
foreach ($execs1 as $exec) {
    if ($exec['totalDotRemanier'] != 0) {
        $showRemanier = true;
        break;
    }
}

// Construire le contenu dans une variable
$content = '';

// Début du tableau
$content .= "<table border='1'>";

// Ligne des entêtes
$content .= "<tr>";
$content .= "<th style='background-color: #4655a4;'>Compte_principal</th>";
$content .= "<th style='background-color: #4655a4;'>Libelle</th>";
$content .= "<th style='background-color: #4655a4;'>Dotation_Initiale</th>";
if ($showRemanier) {
    $content .= "<th style='background-color: #4655a4;'>Variation</th>";
    $content .= "<th style='background-color: #4655a4;'>Dotation_Remaniee</th>";
}
$content .= "<th style='background-color: #4655a4;'>Realisation</th>";
$content .= "<th style='background-color: #4655a4;'>Taux_Realisation</th>";
$content .= "<th style='background-color: #4655a4;'>Disponible</th>";
$content .= "</tr>";

// Corps du tableau
if (!empty($execs1)) {
    foreach ($execs1 as $exec) {
        $content .= "<tr>";
        $content .= "<td>" . htmlspecialchars($exec['numCp']) . "</td>";
        $content .= "<td style='text-align: left; padding: 15px;'>" . htmlspecialchars($exec['libelle']) . "</td>";
        $content .= "<td style='text-align: right; padding: 15px;'>" . number_format($exec['totalDotInitial'], 0, ',', ' ') . " FCFA</td>";

        if ($showRemanier) {
            $content .= "<td style='text-align: right; padding: 15px;'>" . number_format($exec['totalDotRemanier'], 0, ',', ' ') . " FCFA</td>";
            $content .= "<td style='text-align: right; padding: 15px;'>" . number_format($exec['totalDotations'], 0, ',', ' ') . " FCFA</td>";
        }

        $content .= "<td style='text-align: right; padding: 15px;'>" . number_format($exec['totalEngs'], 0, ',', ' ') . " FCFA</td>";
        $content .= "<td style='text-align: right; padding: 15px;'>" . number_format($exec['taux'], 2) . " %</td>";
        $content .= "<td style='text-align: right; padding: 15px;'>" . number_format(($exec['totalDotations'] - $exec['totalEngs']), 0, ',', ' ') . " FCFA</td>";
        $content .= "</tr>";
    }
} else {
    // Aucune donnée trouvée
    $colspan = $showRemanier ? 8 : 6;
    $content .= "<tr><td colspan='{$colspan}' style='text-align: center; color: red;'>Aucun résultat trouvé</td></tr>";
}

// Fin du tableau
$content .= "</table>";

// Afficher le contenu
echo $content;
exit;
?>
