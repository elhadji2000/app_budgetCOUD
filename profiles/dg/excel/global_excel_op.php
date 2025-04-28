<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../../index.php");
    exit();
}

include '../../../includes/fonctions.php';

$execs1 = getExecutionOp_1();
$showRemanier = false;

foreach ($execs1 as $exec) {
    if ($exec['totalDotRemanier'] != 0) {
        $showRemanier = true;
        break;
    }
}

// On envoie les bons headers pour forcer le téléchargement en format Excel
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=export_realisation.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Début du fichier Excel (simple tableau HTML)
echo "<table border='1'>";
echo "<thead>";
echo "<tr>
<th>Compte_principal</th>
<th>Libelle</th>
<th>Dotation_Initiale</th>";

if ($showRemanier) {
    echo "<th>Variation</th>
          <th>Dotation_Remaniee</th>";
}

echo "<th>Realisation</th>
<th>Taux</th>
<th>Disponible</th>
<th>O.P</th>
<th>Diff Eng/Op</th>
</tr>";
echo "</thead>";
echo "<tbody>";

if (!empty($execs1)) {
    foreach ($execs1 as $exec) {
        echo "<tr>";
        echo "<td>".$exec['numCp']."</td>";
        echo "<td>".$exec['libelle']."</td>";
        echo "<td>".number_format($exec['totalDotInitial'], 0, ',', ',')." FCFA</td>";
        if ($showRemanier) {
            echo "<td>".number_format($exec['totalDotRemanier'], 0, ',', ',')." FCFA</td>";
            echo "<td>".number_format($exec['totalDotations'], 0, ',', ',')." FCFA</td>";
        }
        echo "<td>".number_format($exec['totalEngs'], 0, ',', ',')." FCFA</td>";
        echo "<td>".number_format($exec['taux'], 2)." %</td>";
        echo "<td>".number_format(($exec['totalDotations'] - $exec['totalEngs']), 0, ',', ',')." FCFA</td>";
        echo "<td>".number_format($exec['totalOp'], 0, ',', ',')." FCFA</td>";
        echo "<td>".number_format(($exec['totalEngs'] - $exec['totalOp']), 0, ',', ',')." FCFA</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='10'>Aucun résultat trouvé</td></tr>";
}

echo "</tbody>";
echo "</table>";

exit();
?>
