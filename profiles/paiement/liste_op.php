<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php"); // Redirige vers la page de connexion
    exit();
}
?>
<?php include '../../includes/fonctions.php';
$typeOp = "paiement";
$an = $_SESSION['an'];
?>
<?php include '../../includes/header.php';?>
<main>
    <div class='container'>
        <?php include '../../shared/menu.php';?>
    </div>

    <div class="d-flex container justify-content-between align-items-center py-2 px-3"
        style="color: #4655a4; font-size: 13px; font-weight: 400;">

        <input type="text" id="searchInput" class="form-control w-25" placeholder="Rechercher..."
            style="max-width: 250px;" onkeyup="filterTable()">
        <h3 class="mb-0 text-center"> ORDRE(S) DES PAIEMENTS</h3>
        <a href="add_paie1.php" class="btn btn-success"><strong>ajouter</strong></a>
    </div>

    <div class='container-fluid' style="margin-bottom: 20px;">
        <table class="table table-bordered table-hover text-center" style="margin-top: 10px;">
            <thead style="color: white !important;">
                <tr class="table-primary">
                    <th style="background-color: #4655a4;">N°</th>
                    <th style="background-color: #4655a4;">Compte</th>
                    <th style="background-color: #4655a4;">Num_Engagement</th>
                    <th style="background-color: #4655a4;">Num O.P</th>
                    <th style="background-color: #4655a4;">Date_Engagement</th>
                    <th style="background-color: #4655a4;">Objet</th>
                    <th style="background-color: #4655a4;">Service</th>
                    <th style="background-color: #4655a4;">Fournisseur</th>
                    <th style="background-color: #4655a4;">Date O.P</th>
                    <th style="background-color: #4655a4;">Num_Fact</th>
                    <th style="background-color: #4655a4;">Montant</th>
                    <th style="background-color: #4655a4;">Validation</th>
                    <th style="background-color: #4655a4;">Action</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php
    $n = 1;
    $opsTemp = getOperationsTemp($typeOp); // À définir dans ton modèle
    $ops = getOperationsByType($typeOp); // Liste validée

    if (!empty($opsTemp) || !empty($ops)):

        // 1. Afficher les opérations TEMPORAIRES
        foreach ($opsTemp as $op): ?>
                <tr style="background-color: #fff8e1;">
                    <td><?= $n++; ?></td>
                    <td><?= $op['numCompte']; ?></td>
                    <td><?= formatNumEng($op['idEng']); ?> <small class="text-warning">(temp)</small></td>
                    <td><?= $op['idOp']; ?></td>
                    <td><?= $op['dateEng']; ?></td>
                    <td><?= $op['libelle']; ?></td>
                    <td><?= $op['service']; ?></td>
                    <td><?= $op['numFourn']; ?></td>
                    <td><?= $op['dateOp']; ?></td>
                    <td><?= $op['numFact']; ?></td>
                    <td><?= number_format($op['montant'], 0, ',', ','); ?> FCFA</td>
                    <td>
                        <?php if ($_SESSION['priv'] === 'admin'): ?>
                        <a href="traitement_paie.php?valider_id=<?= $op['idOp']; ?>"
                            onclick="return confirm('Valider cette opération ?')" class="badge bg-success">Valider</a>
                        <?php else: ?>
                        <span class="badge bg-success" style="opacity: 0.5; cursor: not-allowed;"
                            title="Accès restreint">
                            Valider
                        </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="traitement_paie.php?supprTempOp=<?= $op['idOp']; ?>"
                            onclick="return confirm('Supprimer cette opération temporaire ?')" class="badge bg-danger">
                            Supprimer
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>

                <!-- 2. Afficher les opérations VALIDÉES -->
                <?php foreach ($ops as $op): ?>
                <tr>
                    <td><?= $n++; ?></td>
                    <td><?= $op['numCompte']; ?></td>
                    <td><?= formatNumEng($op['idEng']); ?></td>
                    <td><?= $op['idOp']; ?></td>
                    <td><?= $op['dateEng']; ?></td>
                    <td><?= $op['libelle']; ?></td>
                    <td><?= $op['service']; ?></td>
                    <td><?= $op['numFourn']; ?></td>
                    <td><?= $op['dateOp']; ?></td>
                    <td><?= $op['numFact']; ?></td>
                    <td><?= number_format($op['montant'], 0, ',', ','); ?> FCFA</td>
                    <td>
                        <span class="badge bg-secondary" style="cursor: not-allowed;" title="Déjà validée">
                            Validée
                        </span>
                    </td>
                    <td>
                        <?php if ($_SESSION['priv'] === 'admin'): ?>
                        <a href="traitement_paie.php?supprOp=<?= $op['idOp']; ?>"
                            onclick="return confirm('Supprimer cette opération ?')" class="badge bg-danger">
                            Supprimer
                        </a>
                        <?php else: ?>
                        <span class="text-muted" style="cursor: not-allowed;" title="Opération utilisée">
                            Supprimer
                        </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>

                <?php else: ?>
                <tr>
                    <td colspan="13" class="text-danger">Aucune opération trouvée.</td>
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
                🎉 O.P supprimer avec succès !
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
                🎉 O.P valider avec succès !
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