<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php"); // Redirige vers la page de connexion
    exit();
}
?>
<?php include '../../includes/fonctions.php';
$idCp = $_GET['idCp'];
$comptep = getComptePById($idCp);
$TDotations = 0;
$TEngs = 0;
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
    <div class='container' style="margin-bottom: 20px;">
        <div
            style='width: 100%; margin: 0 auto; border-top: 4px solid #4655a4; border-bottom: 4px solid #4655a4; padding: 20px;'>
            <table class="table table-bordered text-center" style="width: 100%;margin: 0 auto;font-size:18px;">
                <thead style="color: white !important;">
                    <tr>
                        <th style="background-color: #4655a4;">Compte_principal</th>
                        <th style="background-color: #4655a4;">Libelle</th>
                        <th style="background-color: #4655a4;">Dotations</th>
                        <th style="background-color: #4655a4;">Realisation</th>
                        <th style="background-color: #4655a4;">Taux_Realisation</th>
                        <th style="background-color: #4655a4;">Disponible</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php
                $execs1 = getExecution_2($idCp);
                $n=1;
                if (!empty($execs1)) :
                    foreach ($execs1 as $exec) : ?>
                    <tr>
                        <td><?= $exec['numCompte']; ?></td>
                        <td style="text-align: left; padding: 15px; max-width: 400px;">
                            <?= $exec['libelleC']; ?>
                        </td>

                        <td style='text-align: right;padding: 15px;'>
                            <?= number_format($exec['totalDotations'], 0, ',', ','); ?> FCFA
                        </td>
                        <td style='text-align: right;padding: 15px;'>
                            <a href="actuel_3.php?numCompte=<?php echo $exec['numCompte']; ?>"><?= number_format($exec['totalEngs'], 0, ',', ','); ?>
                                FCFA</a>
                        </td>
                        <td style='text-align: right;padding: 15px;'><?= number_format($exec['taux'], 2); ?>%</td>
                        <td style='text-align: right;padding: 15px;'>
                            <?= number_format(($exec['totalDotations']-$exec['totalEngs']), 0, ',', ','); ?> FCFA</td>
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
                        <th colspan="2" style="background-color: #4655a4;texte-align:center;">Total principal</th>
                        <th style="background-color: #4655a4;text-align: right;">
                            <?= number_format($TDotations, 0, ',', ','); ?> FCFA</th>
                        <th style="background-color: #4655a4;text-align: right;">
                            <?= number_format($TEngs, 0, ',', ','); ?> FCFA</th>
                        <th style="background-color: #4655a4;texte-align:center;">-</th>
                        <th style="background-color: #4655a4;texte-align:center;text-align: right;">
                            <?=number_format(($TDotations - $TEngs) , 0, ',', ','); ?> FCFA</th>
                    </tr>
                </tfooter>
            </table>
        </div>
        <div style='width: 90%;' class="d-flex container justify-content-between align-items-center py-2 px-2"
            style="color:rgb(69, 47, 196); font-size: 18px; font-weight: 400;">
            <button type='submit' class='btn btn-success'><strong>Imprimer</strong></button>
            <a href='javascript:history.back()' class='btn btn-danger mb-0 text-right'><strong>Annuler</strong></a>
        </div>
    </div>



    <div class="container text-center" style="font-size: 15px; font-weight: 400;margin-bottom:20px;">
        <a href="javascript:history.back()" class="btn btn-info text-center"><strong>retour</strong></a>
    </div>
</main>
<?php include '../../includes/footer.php';?>