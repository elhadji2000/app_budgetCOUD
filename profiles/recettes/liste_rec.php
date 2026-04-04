<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit();
}

$typeOp = "recette";
$an = $_SESSION['an'];
?>
<?php include '../../includes/fonctions.php';?>
<?php include '../../includes/header.php';?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<main class="container-fluid mt-3">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 text-primary fw-bold">
            <i class="bi bi-folder2-open"></i> ORDRE(S) DES RECETTE(S)
        </h5>
        <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <!-- Tableau -->
    <!-- Tableau -->
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <table id="tableComptes" class="table table-striped table-hover align-middle">
                <thead class="text-white" style="background-color: #4655a4;">
                    <tr>
                        <th>N°</th>
                        <th>Compte</th>
                        <th>Num O.R</th>
                        <th>Date</th>
                        <th>Objet_recette</th>
                        <th>Débiteur(s)</th>
                        <th>Pièces_Annexées</th>
                        <th>Montant</th>
                        <th>Valider</th>
                        <th>Action(s)</th>
                    </tr>
                </thead>

                <tbody id="tableBody">
                    <?php
                $n = 1;
                $opsTemp = getRecettesTemp();
                $ops = getRecettes();

                if (!empty($opsTemp) || !empty($ops)):

                    // ================= TEMP =================
                    foreach ($opsTemp as $op): ?>
                    <tr style="background-color: #fff8e1;">
                        <td><?= $n++; ?></td>

                        <td><?= $op['numCompte']; ?></td>

                        <td>
                            <?= formatNumOr($op['idOr']); ?>
                            <small class="text-warning">(temp)</small>
                        </td>

                        <td><?= date('d/m/Y', strtotime($op['dateOr'])) ; ?></td>

                        <td><?= $op['objet_recette']; ?></td>

                        <td><?= $op['nom']; ?></td>

                        <td><?= $op['pieces_annexees']; ?></td>

                        <td class="fw-bold">
                            <?= number_format($op['montant'], 0, ',', ' '); ?> F
                        </td>

                        <!-- VALIDATION -->
                        <td>
                            <?php if ($_SESSION['priv'] === 'admin'): ?>
                            <a href="traitement_recette.php?valider_id=<?= $op['idOr']; ?>"
                                onclick="return confirm('Valider cette recette ?')" class="badge bg-success">
                                Valider
                            </a>
                            <?php else: ?>
                            <span class="badge bg-secondary" style="opacity:0.5;">
                                Valider
                            </span>
                            <?php endif; ?>
                        </td>

                        <!-- ACTION -->
                        <td>
                            <a href="traitement_recette.php?supprTemp=<?= $op['idOr']; ?>"
                                onclick="return confirm('Annuler cette recette temporaire ?')"
                                class="btn btn-sm btn-danger">
                                Annuler
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>


                    <!-- ================= VALIDÉ ================= -->
                    <?php foreach ($ops as $op): ?>
                    <tr>
                        <td><?= $n++; ?></td>

                        <td><?= $op['numCompte']; ?></td>

                        <td><?= formatNumOr($op['idOr']); ?></td>

                        <td><?= date('d/m/Y', strtotime($op['dateOr'])) ; ?></td>

                        <td><?= $op['objet_recette']; ?></td>

                        <td><?= $op['nom']; ?></td>

                        <td><?= $op['pieces_annexees']; ?></td>

                        <td class="fw-bold">
                            <?= number_format($op['montant'], 0, ',', ' '); ?> F
                        </td>

                        <!-- VALIDATION -->
                        <td>
                            <span class="badge bg-secondary">
                                Valider
                            </span>
                        </td>

                        <!-- ACTION -->
                        <td>
                            <?php if ($_SESSION['priv'] === 'admin'): ?>
                            <a href="traitement_recette.php?suppr=<?= $op['idOr']; ?>"
                                onclick="return confirm('Annuler cette recette ?')" class="btn btn-sm btn-danger">
                                Annuler
                            </a>
                            <?php else: ?>
                            <span class="text-muted text-decoration-underline">Annuler</span>
                            <?php endif; ?>
                            | <a target="_blank" href="or_rec_pdf.php?id=<?= $op['idOr']; ?>"
                                class="btn btn-sm btn-warning">
                                Vue_PDF
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <?php endif; ?>
                </tbody>
            </table>

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
                O.R Annuler avec succès !
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
                O.R valider avec succès !
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

<!-- jQuery + DataTables -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#tableComptes').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        language: {
            search: "<i class='bi bi-search'></i> Rechercher :",
            lengthMenu: "Afficher _MENU_ lignes",
            info: "Affichage de _START_ à _END_ sur _TOTAL_ comptes",
            paginate: {
                previous: "Précédent",
                next: "Suivant"
            },
            zeroRecords: "Aucun résultat trouvé"
        }
    });
});
</script>

<!-- Alignement à gauche -->
<style>
#tableComptes th,
#tableComptes td {
    text-align: left !important;
    vertical-align: middle;
    font-size: 13px;
}
</style>