<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php"); // Redirige vers la page de connexion
    exit();
}
?>
<?php include '../../includes/fonctions.php';
$sommeDotations = sommeDot();
$sommeEngs = sommeEngs();
if ($sommeDotations != 0) {
    $taux = ($sommeEngs * 100) / $sommeDotations;
} else {
    $taux = 0; // Ou un autre comportement selon ton besoin
}

?>
<?php
$execs1 = getExecution_1();
$showRemanier = false;

// Première boucle pour vérifier s'il existe au moins une dotation remaniée
foreach ($execs1 as $exec) {
    if ($exec['totalDotRemanier'] != 0) {
        $showRemanier = true;
        break;
    }
}
?>
<?php include '../../includes/header.php';?>
<main>
    <div class='container'>
        <?php include '../../shared/menu.php';?>
    </div>

    <!-- Barre de recherche -->
    <div class='text-center' style='margin-bottom:20px;color:#4655a4;'>
        <h3>REALISATIONS: <?= number_format($sommeEngs, 0, ',', ','); ?>FCFA / <?= number_format($sommeDotations, 0, ',', ','); ?>FCFA soit <?= number_format($taux, 2); ?>%</h3>
    </div>

    <!-- Tableau -->
    <div class='container-fluid' style="margin-bottom: 20px;">
        <div
            style='width: 100%; margin: 0 auto; border-top: 3px solid #4655a4; border-bottom: 3px solid #4655a4; padding: 20px;'>
            <table class="table table-bordered text-center" style="width: 100%;margin: 0 auto;font-size:15px;">
                <thead style="color: white !important;">
                    <tr>
                        <th style="background-color: #4655a4;">Compte_principal</th>
                        <th style="background-color: #4655a4;">Libelle</th>
                        <th style="background-color: #4655a4;">Dotation_Initiale</th>
                        <?php if ($showRemanier): ?>
                            <th style="background-color: #4655a4;">Variation</th>
                            <th style="background-color: #4655a4;">Dotation_Remaniee</th>
                        <?php endif; ?>
                        <th style="background-color: #4655a4;">Realisation</th>
                        <th style="background-color: #4655a4;">Taux_Realisation</th>
                        <th style="background-color: #4655a4;">Disponible</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php
                $n=1;
                if (!empty($execs1)) :
                    foreach ($execs1 as $exec) : ?>
                    <tr>
                        <td><?= $exec['numCp']; ?></td>
                        <td style='text-align: left;padding: 15px;'><?= $exec['libelle']; ?></td>
                        <td style='text-align: right;padding: 15px;'><?= number_format($exec['totalDotInitial'], 0, ',', ','); ?> fcfa</td>
                        <?php if ($showRemanier): ?>
                        <td style='text-align: right;padding: 15px;'><?= number_format($exec['totalDotRemanier'], 0, ',', ','); ?> fcfa</td>
                        <td style='text-align: right;padding: 15px;'><?= number_format($exec['totalDotations'], 0, ',', ','); ?> fcfa</td>
                        <?php endif; ?>
                        <td style='text-align: right;padding: 15px;'>
                            <a href="actuel_2.php?idCp=<?php echo $exec['idCp']; ?>"><?= number_format($exec['totalEngs'], 0, ',', ','); ?> fcfa</a>
                        </td>
                        <td style='text-align: right;padding: 15px;'><?= number_format(($exec['taux']), 2); ?>%</td>
                        <td style='text-align: right;padding: 15px;'><?= number_format(($exec['totalDotations']-$exec['totalEngs']), 0, ',', ','); ?> fcfa</td>
                    </tr>
                    <?php endforeach;?>
                    <?php else : ?>
                    <tr>
                        <td colspan="6" class="text-danger">Aucune resultat trouvée</td>
                    </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
        <div style='width: 90%;' class="d-flex container justify-content-between align-items-center py-2 px-2"
            style="color:rgb(69, 47, 196); font-size: 18px; font-weight: 400;">
            <a href="pdf/actuel_1_pdf.php" target="_blank" class='btn btn-success'><strong>Imprimer en PDF</strong></a>
            <a href="excel/global_excel.php" target="_blank" class='btn btn-info'><strong>Exporter en Excel</strong></a>
        </div>
    </div>


    <div class="container text-center" style="font-size: 15px; font-weight: 400;margin-bottom:20px;">
        <a href="javascript:history.back()" class="btn btn-info text-center"><strong>retour</strong></a>
    </div>
</main>
<?php include '../../includes/footer.php';?>