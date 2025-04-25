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
        <h3 class="mb-0 text-center">LES DOTATIONS</h3>
        <strong></strong>
    </div>

    <!-- Tableau -->
    <div class='container-fluid' style="margin-bottom: 20px;">
        <table class="table table-bordered table-striped text-center" style="margin-top: 10px;">
            <thead style="color: white !important;">
                <tr>
                    <th style="background-color: #4655a4;">N°</th>
                    <th style="background-color: #4655a4;">Num_Compte</th>
                    <th style="background-color: #4655a4;">Date de dotation</th>
                    <th style="background-color: #4655a4;">Volume (fcfa)</th>
                    <th style="background-color: #4655a4;">Type</th>
                    <th style="background-color: #4655a4;">User</th>
                    <th style="background-color: #4655a4;">Action(s)</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php
                $dotations = getAllDotations();
                $n=1;
                if (!empty($dotations)) :
                    foreach ($dotations as $dotation) : ?>
                <tr>
                    <td><?= $n++; ?></td>
                    <td><?= $dotation['numCompte']; ?></td>
                    <td><?= $dotation['date']; ?></td>
                    <td><?= number_format($dotation['volume'], 0, ',', ','); ?> fcfa</td>
                    <td>
                        <span class="badge <?= ($dotation['type'] == 'initiale') ? 'bg-info' : 'bg-warning'; ?>">
                            <?= ucfirst($dotation['type']); ?>
                        </span>
                    </td>
                    <td><?= $dotation['log']; ?></td>
                    <td>
                        <?php if (!isDotationUsed($dotation['idDot'])): ?>
                        <a href="supprimer_engagement.php?id=<?= $dotation['idDot'] ?>"
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet Dotation ?')">Supprimer</a>
                        <?php else: ?>
                        <span style="color: grey; cursor: not-allowed;"
                            title="Dotation utilisé, suppression désactivée">Supprimer</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach;?>
                <?php else : ?>
                <tr>
                    <td colspan="7" class="text-danger">Aucune résultat trouvée</td>
                </tr>
                <?php endif; ?>

            </tbody>
            <tbody id="noResultRow" style="display: none;">
                <tr>
                    <td colspan="7" class="text-danger">Aucun résultat trouvé</td>
                </tr>
            </tbody>
        </table>
        <div id="pagination" class="text-center mt-3"></div>
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

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const rowsPerPage = 15; // Nombre de lignes par page
        const table = document.querySelector("tbody#tableBody");
        const rows = table.querySelectorAll("tr");
        const pagination = document.getElementById("pagination");

        let currentPage = 1;
        const totalPages = Math.ceil(rows.length / rowsPerPage);

        function displayRows(page) {
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            rows.forEach((row, index) => {
                row.style.display = index >= start && index < end ? "" : "none";
            });
        }

        function setupPagination() {
            pagination.innerHTML = "";

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement("button");
                btn.innerText = i;
                btn.classList.add("btn", "btn-sm", "btn-outline-primary", "mx-1");

                if (i === currentPage) {
                    btn.classList.add("active");
                }

                btn.addEventListener("click", () => {
                    currentPage = i;
                    displayRows(currentPage);
                    setupPagination(); // Re-render pour mettre à jour le bouton actif
                });

                pagination.appendChild(btn);
            }
        }

        // Init display
        displayRows(currentPage);
        setupPagination();
    });
    </script>



    <div class="container text-center" style="font-size: 15px; font-weight: 400;margin-bottom:20px;">
        <a href="javascript:history.back()" class="btn btn-info text-center"><strong>retour</strong></a>
    </div>
</main>
<?php include '../../includes/footer.php';?>