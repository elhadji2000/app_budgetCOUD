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

    <!-- Barre de recherche -->
    <div class="d-flex container justify-content-between align-items-center py-2 px-3"
        style="color: #4655a4; font-size: 13px; font-weight: 400;">
        <input type="text" id="searchInput" class="form-control w-25" placeholder="Rechercher..."
            style="max-width: 250px;" onkeyup="filterTable()">
        <h3 class="mb-0 text-center">LES COMPTES ENREGISTRÉS</h3>
        <a href="#" class="btn btn-success"><strong>nouveau</strong></a>
    </div>

    <!-- Tableau -->
    <div class='container-fluid' style="margin-bottom: 20px;">
        <table class="table table-bordered table-striped text-center" style="margin-top: 10px;">
            <thead style="color: white !important;">
                <tr class="table-primary">
                    <th>N°</th>
                    <th>NumCompte</th>
                    <th>Libelle</th>
                    <th>NumCp</th>
                    <th>Code</th>
                    <th>Nature</th>
                    <th>Action(s)</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php
            $comptes = getAllCompte();
             $n=0;
            if (!empty($comptes)) {
                foreach ($comptes as $compte) {
                    $n++;
                    echo "<tr>
                    <td>{$n}</td>
                            <td>{$compte['numCompte']}</td>
                            <td>{$compte['libelle']}</td>
                            <td>{$compte['numCp']}</td>
                            <td>{$compte['code']}</td>
                            <td>{$compte['nature']}</td>
                            <td><a href=''>archiver</a></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-danger'>Aucune compte trouvée</td></tr>";
            }
            ?>
            </tbody>
            <tbody id="noResultRow" style="display: none;">
                <tr>
                    <td colspan="5" class="text-danger">Aucun résultat trouvé</td>
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