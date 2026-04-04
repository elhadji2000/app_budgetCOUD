<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit();
}

$typeOp = "paiement";
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
            <i class="bi bi-folder2-open"></i> ORDRE(S) DES PAIEMENT(S)
        </h5>
        <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <!-- Tableau -->
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <table id="tableComptes" class="table table-striped table-hover align-middle">
                <thead class="text-white" style="background-color: #4655a4;">
                    <tr>
                        <th>N°</th>
                        <th>Compte</th>
                        <th>N° Bon</th>
                        <th>Date_Eng</th>
                        <th>Montant_Eng</th>
                        <th>Num O.P</th>
                        <th>Date O.P</th>
                        <th>Pieces_justificatifs</th>
                        <th>Montant_O.P</th>
                        <th>Validation</th>
                        <th>Action(s)</th>
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
                        <td><?= formatNumEng($op['idEng']); ?></td>
                        <td><?= date('j/m/Y', strtotime($op['dateEng'])); ?></td>
                        <td class="fw-bold somme"><?= number_format($op['montant'], 0, ',', ' '); ?> F</td>
                        <td><?= formatNumOP($op['idOp']); ?><small class="text-warning">(temp)</small></td>
                        <td><?= date('j/m/Y', strtotime($op['dateOp'])); ?></td>
                        <td><?= strtoupper($op['numFact']); ?></td>
                        <td class="fw-bold somme"><?= number_format($op['montant_op'], 0, ',', ' '); ?> F</td>
                        <td>
                            <?php if ($_SESSION['priv'] === 'admin'): ?>
                            <a href="traitement_paie.php?valider_id=<?= $op['idOp']; ?>"
                                onclick="return confirm('Valider cette opération ?')"
                                class="btn btn-sm  btn-success">Valider</a>
                            <?php else: ?>
                            <span class="btn btn-sm btn-secondary" style="opacity: 0.5; cursor: not-allowed;"
                                title="Accès réservé à l'administrateur">
                                valider
                            </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="traitement_paie.php?supprTempOp=<?= $op['idOp']; ?>"
                                onclick="return confirm('Annuler cette opération temporaire ?')"
                                class="btn btn-sm  btn-dabger">
                                Annuler
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
                        <td><?= date('j/m/Y', strtotime($op['dateEng'])); ?></td>
                        <td class="fw-bold somme"><?= number_format($op['montant'], 0, ',', ' '); ?> F</td>
                        <td><?= formatNumOP($op['idOp']); ?></td>
                        <td><?= date('j/m/Y', strtotime($op['dateOp'])); ?></td>
                        <td><?= strtoupper($op['numFact']); ?></td>
                        <td class="fw-bold somme"><?= number_format($op['montant_op'], 0, ',', ' '); ?> F</td>
                        <td>
                            <span class="btn btn-sm btn-secondary" style="cursor: not-allowed;" title="Déjà validée">
                                Valider
                            </span>
                        </td>
                        <td>
                            <a target="_blank" href="mp_vue_pdf.php?id=<?= $op['idOp']; ?>"
                                class="btn btn-sm btn-warning">
                                MP_PDF
                            </a> |
                            <?php if ($_SESSION['priv'] === 'admin'): ?>
                            <a href="traitement_paie.php?supprOp=<?= $op['idOp']; ?>"
                                onclick="return confirm('Annuler cette opération ?')" class="btn btn-sm btn-danger">
                                Annuler
                            </a>
                            <?php else: ?>
                            <span class="btn btn-sm btn-secondary" style="cursor: not-allowed;"
                                title="Opération utilisée">
                                Annuler
                            </span>
                            <?php endif; ?>

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
                O.P supprimer avec succès !
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
                O.P valider avec succès !
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
    font-size: 12px;
}

#tableComptes .somme {
    text-align: right !important;
}
</style>