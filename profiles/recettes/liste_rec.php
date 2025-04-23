<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php"); // Redirige vers la page de connexion
    exit();
}
?>
<?php include '../../includes/fonctions.php';
$typeOp = "recette";
$an = $_SESSION['an'];
?>
<?php include '../../includes/header.php';?>
<main>
    <div class='container'>
        <?php include '../../shared/menu.php';?>
    </div>

    <div class="d-flex container justify-content-between align-items-center py-2 px-3"
        style="color: #4655a4; font-size: 13px; font-weight: 400;">

        <input type="text" class="form-control w-25" placeholder="Rechercher..." style="max-width: 250px;">
        <h3 class="mb-0 text-center"> ORDRE(S) DES RECETTES</h3>
        <a href="add_rec1.php" class="btn btn-success"><strong>ajouter</strong></a>
    </div>

    <div class='container-fluid' style="margin-bottom: 20px;">
    <table class="table table-bordered table-hover text-center" style="margin-top: 10px;">
            <thead style="color: white !important;">
                <tr class="table-primary">
                    <th>N°</th>
                    <th>Compte</th>
                    <th>Num_Engagement</th>
                    <th>Num O.R</th>
                    <th>Date_Engagement</th>
                    <th>Objet</th>
                    <th>Service</th>
                    <th>Fournisseur</th>
                    <th>Date O.R</th>
                    <th>Num_Fact</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php
                    $n=1;
                    $ops = getOperationsByType($typeOp);
                    if (!empty($ops)):
                        foreach ($ops as $op):
                    ?>
                <tr>
                    <td><?= $n++ ?></td>
                    <td><?= $op['numCompte'] ?></td>
                    <td>BE<?= $an ?>-<?= str_pad($op['idEng'], 4, '0', STR_PAD_LEFT) ?></td>
                    <td><?= $op['idOp'] ?></td>
                    <td><?= $op['dateEng'] ?></td>
                    <td><?= $op['libelle'] ?></td>
                    <td><?= $op['service'] ?></td>
                    <td><?= $op['numFourn'] ?></td>
                    <td><?= $op['dateOp'] ?></td>
                    <td><?= $op['numFact'] ?></td>
                    <td><?= number_format($op['montant'], 0, ',', ',') ?> FCFA</td>
                </tr>
                <?php
                    endforeach;
                    else:
                    ?>
                <tr>
                    <td colspan="11" style="color: red;">Aucun résultat trouvé</td>
                </tr>
                <?php endif; ?>

            </tbody>
            <tbody id="noResultRow" style="display: none;">
                <tr>
                    <td colspan="11" class="text-danger">Aucun résultat trouvé</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="container text-center" style="font-size: 15px; font-weight: 400;margin-bottom:20px;">
        <a href="javascript:history.back()" class="btn btn-info text-center"><strong>retour</strong></a>
    </div>
</main>
<?php include '../../includes/footer.php';?>