<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php"); // Redirige vers la page de connexion
    exit();
}
?>
<?php include '../../includes/fonctions.php';
$idCp = $_GET['idCp'];
$op = isset($_GET['op']) ? $_GET['op'] : 0;
$comptep = getComptePById($idCp);
$TDotations = 0;
$TEngs = 0;
$TOp = 0;
$TDotationInitiale = 0;
$TDotationRemanier = 0;
?>

<?php
$execs1 = getExecution_2($idCp);
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
        <h2>Etat du compte principal <?= $comptep['numCp']; ?> : <?= $comptep['libelle']; ?></h2>
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
                        <th style="background-color: #4655a4;">Dot_Initiale</th>
                        <?php if ($showRemanier): ?>
                        <th style="background-color: #4655a4;">Variation</th>
                        <th style="background-color: #4655a4;">Dot_Remaniee</th>
                        <?php endif; ?>
                        <th style="background-color: #4655a4;">Realisation</th>
                        <th style="background-color: #4655a4;">Taux</th>
                        <th style="background-color: #4655a4;">Disponible</th>
                        <?php if ($op == 1): ?>
                        <th style="background-color: #4655a4;">O.P</th>
                        <th style="background-color: #4655a4;">Diff Eng/Op</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php
                $n=1;
                if (!empty($execs1)) :
                    foreach ($execs1 as $exec) : ?>
                    <tr>
                        <td><?= $exec['numCompte']; ?></td>
                        <td style="text-align: left; padding: 15px; max-width: 350px;"><?= $exec['libelleC']; ?></td>
                        <td style='text-align: right;padding: 15px;'>
                            <?= number_format($exec['totalDotInitial'], 0, ',', ','); ?> f</td>
                        <?php if ($showRemanier): ?>
                        <td style='text-align: right;padding: 15px;'>
                            <?= number_format($exec['totalDotRemanier'], 0, ',', ','); ?> f</td>
                        <td style='text-align: right;padding: 15px;'>
                            <?= number_format($exec['totalDotations'], 0, ',', ','); ?> f</td>
                        <?php endif; ?>
                        <?php if ($op == 1): ?>
                        <td style='text-align: right;padding: 15px;'> <a
                                href="actuel_3.php?numCompte=<?php echo $exec['numCompte']; ?>&op=1"><?= number_format($exec['totalEngs'], 0, ',', ','); ?>
                                f</a></td>
                        <?php else : ?>
                        <td style='text-align: right;padding: 15px;'> <a
                                href="actuel_3.php?numCompte=<?php echo $exec['numCompte']; ?>"><?= number_format($exec['totalEngs'], 0, ',', ','); ?>
                                f</a></td>
                        <?php endif; ?>
                        <td style='text-align: right;padding: 15px;'><?= number_format($exec['taux'], 2); ?>%</td>
                        <td style='text-align: right;padding: 15px;'>
                            <?= number_format(($exec['totalDotations']-$exec['totalEngs']), 0, ',', ','); ?> f</td>
                        <?php if ($op == 1): ?>
                        <td style='text-align: right;padding: 15px;'>
                            <?= number_format($exec['totalOp'], 0, ',', ','); ?> f</td>
                        <td style='text-align: right;padding: 15px;'>
                            <?= number_format(($exec['totalEngs'] - $exec['totalOp']), 0, ',', ','); ?> f</td>
                        <?php endif; ?>

                    </tr>
                    <?php 
                    $TDotations += $exec['totalDotations'];
                    $TEngs += $exec['totalEngs'];
                    $TOp += $exec['totalOp'];
                    $TDotationInitiale += $exec['totalDotInitial'];
                    $TDotationRemanier += $exec['totalDotRemanier'];
                    endforeach;?>
                    <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-danger">Aucune recette trouvée</td>
                    </tr>
                    <?php endif; ?>

                </tbody>
                <tfooter>
                    <tr>
                        <th colspan="2" style="background-color: #4655a4;texte-align:center;color: white;">Total
                            principal</th>
                        <th style="background-color: #4655a4;text-align: right;color: white;">
                            <?= number_format($TDotationInitiale, 0, ',', ','); ?> f</th>
                        <?php if ($showRemanier): ?>
                        <th style="background-color: #4655a4;text-align: right;color: white;">
                            <?= number_format($TDotationRemanier, 0, ',', ','); ?> f</th>
                        <th style="background-color: #4655a4;text-align: right;color: white;">
                            <?= number_format($TDotations, 0, ',', ','); ?> f</th>
                        <?php endif; ?>
                        <th style="background-color: #4655a4;text-align: right;color: white;">
                            <?= number_format($TEngs, 0, ',', ','); ?> f</th>
                        <th style="background-color: #4655a4;texte-align:center;color: white;">-</th>
                        <th style="background-color: #4655a4;text-align: right;color: white;">
                            <?=number_format(($TDotations - $TEngs) , 0, ',', ','); ?> f</th>
                        <?php if ($op == 1): ?>
                        <th style="background-color: #4655a4;texte-align:right;color: white;">
                            <?= number_format($TOp, 0, ',', ','); ?> f</th>
                        <th style="background-color: #4655a4;text-align: right;color: white;">
                            <?=number_format(($TEngs - $TOp) , 0, ',', ','); ?> f</th>
                        <?php endif; ?>
                    </tr>
                </tfooter>
            </table>
        </div>
        <div style='width: 90%;' class="d-flex container justify-content-between align-items-center py-2 px-2"
            style="color:rgb(69, 47, 196); font-size: 18px; font-weight: 400;">
            <a href="pdf/actuel_2_pdf.php?idCp=<?php echo $exec['idCp']; ?>&op=1" target="_blank"
                class='btn btn-success'><strong>Imprimer en PDF</strong></a>
            <a href='javascript:history.back()' class='btn btn-danger mb-0 text-right'><strong>Annuler</strong></a>
        </div>
    </div>



    <div class="container text-center" style="font-size: 15px; font-weight: 400;margin-bottom:20px;">
        <a href="javascript:history.back()" class="btn btn-info text-center"><strong>retour</strong></a>
    </div>
</main>
<?php include '../../includes/footer.php';?>