<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php"); // Redirige vers la page de connexion
    exit();
}
?>
<?php include '../../includes/fonctions.php';?>
<?php include '../../includes/header.php';?>
<main>
    <div class='container'>
        <?php include '../../shared/menu.php';?>
    </div>

    <div class="d-flex container justify-content-between align-items-center py-2 px-3"
        style="color: #4655a4; font-size: 13px; font-weight: 400;">

        <input type="text" class="form-control w-25" placeholder="Rechercher..." style="max-width: 250px;">
        <h3 class="mb-0 text-center"> ORDRE(S) DES RECETTES</h3>
        <a href="new_recette.php" class="btn btn-success"><strong>ajouter recette</strong></a>
    </div>

    <div class='container-fluid' style="margin-bottom: 20px;">
        <table class="table table-bordered table-hover text-center" style="margin-top: 10px;">
            <thead style="color: white !important;">
                <tr class="table-primary">
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
            <tbody>
                <?php
        $recettes = getAllRecettes();
        if (!empty($recettes)) {
            foreach ($recettes as $recette) {
                echo "<tr>
                        <td>{$recette['numc']}</td>
                        <td>{$recette['date']}</td>
                        <td>{$recette['numfact']}</td>
                        <td>{$recette['datedesaisie']}</td>
                        <td>{$recette['numfact']}</td>
                        <td>{$recette['numfact']}</td>
                        <td>{$recette['numfact']}</td>
                        <td>{$recette['numfact']}</td>
                        <td>{$recette['numfact']}</td>
                        <td>{$recette['numfact']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='10' style='color: red;'>Aucune recette trouvée</td></tr>";
        }
        ?>
            </tbody>
        </table>
    </div>

    <div class="container text-center" style="font-size: 15px; font-weight: 400;margin-bottom:20px;">
        <a href="javascript:history.back()" class="btn btn-info text-center"><strong>retour</strong></a>
    </div>
</main>
<?php include '../../includes/footer.php';?>