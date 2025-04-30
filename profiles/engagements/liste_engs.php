<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php"); // Redirige vers la page de connexion
    exit();
}
?>
<?php include '../../includes/fonctions.php';
$an = $_SESSION['an'];
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
        <h3 class="mb-0 text-center">LES ENGAGEMENTS</h3>
        <strong></strong>
    </div>

    <!-- Tableau -->
    <div class='container-fluid' style="margin-bottom: 20px;">
        <table class="table table-bordered table-striped text-center" style="margin-top: 10px;">
            <thead style="color: white !important;">
                <tr class="table-primary">
                    <th style="background-color: #4655a4;">N°</th>
                    <th style="background-color: #4655a4;">NumCompte</th>
                    <th style="background-color: #4655a4;">NumEngs</th>
                    <th style="background-color: #4655a4;">date</th>
                    <th style="background-color: #4655a4;">service</th>
                    <th style="background-color: #4655a4;">libelle</th>
                    <th style="background-color: #4655a4;">bc</th>
                    <th style="background-color: #4655a4;">Montant</th>
                    <th style="background-color: #4655a4;">Fourniseur</th>
                    <th style="background-color: #4655a4;">Valider</th>
                    <th style="background-color: #4655a4;">Action(s)</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php
    $n = 1;
    $engsTemp = getEngagementsTemp();
    $engs = getEngs();

    if (!empty($engsTemp) || !empty($engs)):

        // D'abord les engagements TEMP
        foreach ($engsTemp as $eng): ?>
                <tr style="background-color: #fff8e1;">
                    <!-- Jaune clair pour temp -->
                    <td><?= $n++; ?></td>
                    <td><?= $eng['numCompte']; ?></td>
                    <td><?= formatNumEng($eng['idEng']); ?> <small class="text-warning">(temp)</small></td>
                    <td><?= $eng['dateEng']; ?></td>
                    <td><?= $eng['service']; ?></td>
                    <td><?= $eng['libelle']; ?></td>
                    <td><?= $eng['bc']; ?></td>
                    <td><?= number_format($eng['montant'], 0, ',', ','); ?> FCFA</td>
                    <td><?= $eng['numFourn']; ?></td>
                    <td>
                        <?php if ($_SESSION['priv'] === 'admin'): ?>
                        <a href="traitement_eng.php?valider_id=<?= $eng['idEng']; ?>"
                            onclick="return confirm('Êtes-vous sûr de vouloir valider cet engagement ?')"
                            class="badge bg-success">Valider</a>
                        <?php else: ?>
                        <span class="badge bg-success" style="opacity: 0.5; cursor: not-allowed;"
                            title="Accès réservé à l'administrateur">
                            valider
                        </span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <a href="traitement_eng.php?supprTemp=<?= $eng['idEng']; ?>"
                            onclick="return confirm('Supprimer cet engagement temporaire ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach;

        // Ensuite les engagements validés
        foreach ($engs as $eng): ?>
                <tr>
                    <td><?= $n++; ?></td>
                    <td><?= $eng['numCompte']; ?></td>
                    <td><?= formatNumEng($eng['idEng']); ?></td>
                    <td><?= $eng['dateEng']; ?></td>
                    <td><?= $eng['service']; ?></td>
                    <td><?= $eng['libelle']; ?></td>
                    <td><?= $eng['bc']; ?></td>
                    <td><?= number_format($eng['montant'], 0, ',', ','); ?> FCFA</td>
                    <td><?= $eng['numFourn']; ?></td>
                    <td><span style="cursor: not-allowed;" title="Engagement valider"
                            class="badge bg-secondary">Validé</span></td>
                    <td>
                        <?php if (!isEngagementUsed($eng['idEng'])): ?>
                        <a href="traitement_eng.php?suppr=<?= $eng['idEng'] ?>"
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet engagement ?')">Supprimer</a>
                        <?php else: ?>
                        <span style="color: grey; cursor: not-allowed;"
                            title="Engagement utilisé, suppression désactivée">Supprimer</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>

                <?php else: ?>
                <tr>
                    <td colspan="11" class="text-danger">Aucun engagement trouvé.</td>
                </tr>
                <?php endif; ?>
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

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
<!-- Modal Bootstrap -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-info">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="successModalLabel">Succès</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                🎉 Engagement supprimer avec succès !
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
<script>
// Une fois le DOM chargé, on lance la modal
document.addEventListener('DOMContentLoaded', function() {
    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
});
</script>
<?php endif; ?>

<?php if (isset($_GET['success']) && $_GET['success'] == 2): ?>
<!-- Modal Bootstrap -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-success">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel">Succès</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                🎉 Engagement valider avec succès !
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (isset($_GET['success']) && $_GET['success'] == 2): ?>
<script>
// Une fois le DOM chargé, on lance la modal
document.addEventListener('DOMContentLoaded', function() {
    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
});
</script>
<?php endif; ?>
<?php include '../../includes/footer.php';?>