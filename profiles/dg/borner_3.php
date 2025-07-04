<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php"); // Redirige vers la page de connexion
    exit();
}
?>
<?php include '../../includes/fonctions.php';
$date1 = $_GET['date1'];
$date2 = $_GET['date2'];
$numCompte = $_GET['numCompte'];
$data = getCompteByNum($numCompte);
$engs = getEngsByCompteAndDate2($numCompte, $date1, $date2);
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
        <h3>EXECUTION DU  : <?= $date1; ?> AU <?= $date2; ?></h3>
        <strong>Compte  <?= $data['numCompte']; ?> : <?= $data['libelle']; ?></strong>
    </div>

    <!-- Tableau -->
    <div class='container-fluid' style="margin-bottom: 20px;">
        <div
            style='width: 100%; margin: 0 auto; border-top: 3px solid #4655a4; border-bottom: 3px solid #4655a4; padding: 20px;'>
            <table class="table table-bordered text-center" style="width: 100%;margin: 0 auto;font-size:15px;">
                <thead style="color: white !important;">
                    <tr>
                        <th style="background-color: #4655a4;">Numero</th>
                        <th style="background-color: #4655a4;">Compte</th>
                        <th style="background-color: #4655a4;">Date_Realisation</th>
                        <th style="background-color: #4655a4;">Ref</th>
                        <th style="background-color: #4655a4;">Objet</th>
                        <th style="background-color: #4655a4;">Service</th>
                        <th style="background-color: #4655a4;">FR/Bènèf</th>
                        <th style="background-color: #4655a4;">Montant</th>
                        <th style="background-color: #4655a4;">Bon_Eng</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php
                $n=1;
                if (!empty($engs)) :
                    foreach ($engs as $eng) : ?>
                    <tr>
                        <td><?= formatNumEng($eng['idEng']); ?></td>
                        <td style="padding: 15px;">
                            <?= $eng['numCompte']; ?>
                        </td>

                        <td style='padding: 15px;'>
                            <?= $eng['dateEng']; ?>
                        </td>
                        <td style='padding: 15px;'>
                            facture<br> N°<?= $eng['numFact']; ?>
                        </td>
                        <td style='padding: 15px;max-width: 250px;'>
                            <?= $eng['libelleC']; ?>
                        </td>
                        <td style='padding: 15px;'>
                            <?= $eng['service']; ?>
                        </td>
                        <td style='padding: 15px;'>
                            <?= $eng['nom']; ?>
                        </td>
                        <td style='text-align: right;padding: 15px;'><?= number_format($eng['montant'], 0, ',', ','); ?> FCFA</td>
                        <td>
                        <a href="../engagements/eng_details.php?id=<?= $eng['idEng'] ?>">vue_pdf</a>
                    </td>
                    </tr>
                    <?php 
                    $TEngs += $eng['montant'];
                    endforeach;?>
                    <?php else : ?>
                    <tr>
                        <td colspan="9" class="text-danger">Aucune recette trouvée</td>
                    </tr>
                    <?php endif; ?>

                </tbody>
                <tfooter>
                    <tr>
                        <th colspan="7" style="background-color: #4655a4;texte-align:center;color: white;">TOTAL DES REALISATIONS DU COMPTE</th>
                        <th colspan="2" style="background-color: #4655a4;text-align: center;color: white;">
                            <?= number_format($TEngs, 0, ',', ','); ?> FCFA</th>
                    </tr>
                </tfooter>
            </table>
        </div>
    </div>

    <div class="container text-center" style="font-size: 15px; font-weight: 400;margin-bottom:20px;">
        <a href="javascript:history.back()" class="btn btn-info text-center"><strong>retour</strong></a>
    </div>
</main>
<?php include '../../includes/footer.php';?>