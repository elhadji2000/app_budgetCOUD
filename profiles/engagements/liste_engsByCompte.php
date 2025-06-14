<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php"); // Redirige vers la page de connexion
    exit();
}
?>
<?php include '../../includes/fonctions.php';
$numCompte = $_GET['numc'];
$data = getCompteByNum($numCompte);
$engs = getEngsByCompte($numCompte);
?>
<?php include '../../includes/header.php';?>
<main>
    <div class='container'>
        <?php include '../../shared/menu.php';?>
    </div>
    <div class='text-center' style='margin-bottom:20px;color:#4655a4;'>
        <h4>
            <i style='color:rgba(78, 120, 93, 0.91);'><?= $data['numCompte']; ?>:<?= $data['libelle']; ?></i>
        </h4>
    </div>
    <!-- Barre de recherche -->
    <div class="d-flex container justify-content-between align-items-center py-2 px-3"
        style="color: #4655a4; font-size: 13px; font-weight: 400;">
        <input type="text" id="searchInput" class="form-control w-25" placeholder="Rechercher..."
            style="max-width: 250px;" onkeyup="filterTable()">
        <h3 class="mb-0 text-center">LES ENGAGEMENTS</h3>
        <strong></strong>
    </div>

    <!-- Tableau -->
    <div class='container-fluid' style="margin-bottom: 20px;">
        <table class="table table-bordered table-striped text-center" style="margin-top: 10px;">
            <thead style="color: white !important;">
                <tr class="table-primary">
                    <th style="background-color: #4655a4;">N°</th>
                    <th style="background-color: #4655a4;">NumEngs</th>
                    <th style="background-color: #4655a4;">date</th>
                    <th style="background-color: #4655a4;">service</th>
                    <th style="background-color: #4655a4;">libelle</th>
                    <th style="background-color: #4655a4;">bc</th>
                    <th style="background-color: #4655a4;">Montant</th>
                    <th style="background-color: #4655a4;">Fourniseur</th>
                    <th style="background-color: #4655a4;">details</th>
                    <th style="background-color: #4655a4;">suppr</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php
                $n = 1;
                if (!empty($engs)) :
                    foreach ($engs as $eng) : ?>
                <tr>
                    <td><?= $n; ?></td>
                    <td>
                        <?= formatNumEng($eng['idEng']); ?>
                    </td>
                    <td><?= $eng['dateEng']; ?></td>
                    <td><?= $eng['service']; ?></td>
                    <td><?= $eng['libelle']; ?></td>
                    <td><?= $eng['bc']; ?></td>
                    <td><?= number_format($eng['montant'], 0, ',', ','); ?> FCFA</td>
                    <td><?= $eng['numFourn']; ?></td>
                    <td>
                        <a href="eng_details.php?id=<?= $eng['idEng'] ?>">details</a>
                    </td>
                    <td>
                        <?php if (!isEngagementUsed($eng['idEng'])): ?>
                        <a href="supprimer_engagement.php?id=<?= $eng['idEng'] ?>"
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet engagement ?')">Supprimer</a>
                        <?php else: ?>
                        <span style="color: grey; cursor: not-allowed;"
                            title="Engagement utilisé, suppression désactivée">Supprimer</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php
                    $n++;
                    endforeach;
                ?>
                <?php else : ?>
                <tr>
                    <td colspan="10" class="text-danger">Aucun résultat trouvé</td>
                </tr>
                <?php endif; ?>

            </tbody>
            <tbody id="noResultRow" style="display: none;">
                <tr>
                    <td colspan="10" class="text-danger">Aucun résultat trouvé</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Script pour la recherche et l'affichage du message -->
    <script>
    function filterTable() {
        let input = document.getElementById("searchInput");
        let filter = input.value.toLowerCase();
        let tableBody = document.getElementById("tableBody");
        let rows = tableBody.getElementsByTagName("tr");
        let noResultRow = document.getElementById("noResultRow");

        let found = false;

        for (let i = 0; i < rows.length; i++) {
            let cells = rows[i].getElementsByTagName("td");
            let rowContainsFilter = false;

            for (let j = 0; j < cells.length; j++) {
                if (cells[j].textContent.toLowerCase().includes(filter)) {
                    rowContainsFilter = true;
                    break;
                }
            }

            rows[i].style.display = rowContainsFilter ? "" : "none";
            if (rowContainsFilter) found = true;
        }

        // Afficher ou cacher la ligne "Aucun résultat trouvé"
        noResultRow.style.display = found ? "none" : "";
    }
    </script>




    <div class="container text-center" style="font-size: 15px; font-weight: 400;margin-bottom:20px;">
        <a href="javascript:history.back()" class="btn btn-info text-center"><strong>retour</strong></a>
    </div>
</main>
<?php include '../../includes/footer.php';?>