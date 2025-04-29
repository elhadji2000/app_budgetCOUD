<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php"); // Redirige vers la page de connexion
    exit();
}
?>
<?php include '../../includes/fonctions.php';?>
<?php
// Traitement de l’activation/désactivation
if (isset($_GET['toggle_id'])) {
    $id = intval($_GET['toggle_id']);
    $connexion = connexionBD();
    // Récupérer le statut actuel
    $sql = "SELECT statut FROM users WHERE idUser = $id";
    $result = mysqli_query($connexion, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        $newStatut = ($row['statut'] == 1) ? 0 : 1;

        // Mettre à jour
        $updateSql = "UPDATE users SET statut = $newStatut WHERE idUser = $id";
        mysqli_query($connexion, $updateSql);

        // Rediriger pour éviter le double traitement au rafraîchissement
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>

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
        <h3 class="mb-0 text-center">LES UTILISATEURS ENREGISTRÈS </h3>
        <a href="add_user.php" class="btn btn-success"><strong>nouveau</strong></a>
    </div>

    <!-- Tableau -->
    <div class='container-fluid' style="margin-bottom: 20px;">
        <table class="table table-bordered table-striped text-center" style="margin-top: 10px;">
            <thead style="color: white !important;">
                <tr class="table-primary">
                    <th style="background-color: #4655a4;">N°</th>
                    <th style="background-color: #4655a4;">Nom</th>
                    <th style="background-color: #4655a4;">Log</th>
                    <th style="background-color: #4655a4;">Rôle</th>
                    <th style="background-color: #4655a4;">Email</th>
                    <th style="background-color: #4655a4;">Type_mdp</th>
                    <th style="background-color: #4655a4;">Action(s)</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php
                $users = getAllUsers();
                $n = 1;
                if (!empty($users)) :
                    foreach ($users as $user) : ?>
                <tr>
                    <td><?= $n; ?></td>
                    <td><?= $user['nom']; ?></td>
                    <td><?= $user['log']; ?></td>
                    <td><?= $user['priv']; ?></td>
                    <td><?= $user['email']; ?></td>
                    <td>
                        <span class="badge <?= ($user['type_mdp'] == 'updated') ? 'bg-success' : 'bg-warning'; ?>">
                            <?= ucfirst($user['type_mdp']); ?>
                        </span>
                    </td>
                    <td>
                        <a href="?toggle_id=<?= $user['idUser']; ?>"
                            class="badge <?= ($user['statut']) ? 'bg-danger' : 'bg-success'; ?>">
                            <?= ($user['statut']) ? 'Désactiver' : 'Activer'; ?>
                        </a>
                    </td>

                </tr>
                <?php $n++; endforeach;?>
                <?php else : ?>
                <tr>
                    <td colspan="7" class="text-danger">Aucun utilisateur trouvé</td>
                </tr>
                <?php endif; ?>

            </tbody>
            <tbody id="noResultRow" style="display: none;">
                <tr>
                    <td colspan="7" class="text-danger">Aucun résultat trouvé</td>
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