<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit();
}
?>

<?php 
include '../../includes/fonctions.php';

$sommeDotations = sommeDot();
$sommeEngs = sommeEngs();
$taux = ($sommeDotations != 0) ? ($sommeEngs * 100) / $sommeDotations : 0;

$execs1 = getExecutionOp_1();

$showRemanier = false;
foreach ($execs1 as $exec) {
    if ($exec['totalDotRemanier'] != 0) {
        $showRemanier = true;
        break;
    }
}
?>

<?php include '../../includes/header.php';?>

<!-- DataTables + Icons -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<main class="container-fluid mt-3">

    <!-- HEADER -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">

            <div>
                <h5 class="fw-bold text-primary mb-1">
                    <i class="bi bi-graph-up-arrow"></i> RAPPORT GLOBAL AVEC O.P
                </h5>
                <small class="text-muted">Analyse des engagements et opérations</small>
            </div>

            <div class="text-end">
                <div class="fw-bold">
                    <?= number_format($sommeEngs, 0, ',', ' ') ?> FCFA /
                    <?= number_format($sommeDotations, 0, ',', ' ') ?> FCFA
                </div>
                <span class="badge <?= ($taux >= 80) ? 'bg-danger' : (($taux >= 50) ? 'bg-warning text-dark' : 'bg-success') ?>">
                                    <?= number_format($taux, 2) ?> %
                </span>
            </div>

        </div>
    </div>
    <!-- TABLE -->
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <table id="tableOP" class="table table-striped table-hover align-middle">

                <thead class="custom-header">
                    <tr>
                        <th>Compte</th>
                        <th>Libellé</th>
                        <th>Dotation Initiale</th>

                        <?php if ($showRemanier): ?>
                            <th>Variation</th>
                            <th>Dotation Remaniée</th>
                        <?php endif; ?>

                        <th>Engagement</th>
                        <th>Taux (%)</th>
                        <th>Disponible</th>
                        <th>O.P</th>
                        <th>Différence</th>
                    </tr>
                </thead>

                <tbody>

                <?php if (!empty($execs1)) : ?>
                    <?php foreach ($execs1 as $exec) : ?>
                        <tr>

                            <td><?= htmlspecialchars($exec['numCp']) ?></td>

                            <td><?= htmlspecialchars($exec['libelle']) ?></td>

                            <td class="text-end fw-semibold">
                                <?= number_format($exec['totalDotInitial'], 0, ',', ' ') ?> F
                            </td>

                            <?php if ($showRemanier): ?>
                                <td class="text-end">
                                    <?= number_format($exec['totalDotRemanier'], 0, ',', ' ') ?> F
                                </td>
                                <td class="text-end fw-semibold">
                                    <?= number_format($exec['totalDotations'], 0, ',', ' ') ?> F
                                </td>
                            <?php endif; ?>

                            <td class="text-end">
                                <a href="actuel_2.php?idCp=<?= $exec['idCp'] ?>&op=1"
                                   class="fw-bold text-primary text-decoration-underline">
                                    <?= number_format($exec['totalEngs'], 0, ',', ' ') ?> F
                                </a>
                            </td>

                            <td class="text-end">
                                <span class="badge 
                                    <?= ($exec['taux'] >= 80) ? 'bg-danger' : (($exec['taux'] >= 50) ? 'bg-warning text-dark' : 'bg-success') ?>">
                                    <?= number_format($exec['taux'], 2) ?> %
                                </span>
                            </td>

                            <td class="text-end">
                                <?= number_format(($exec['totalDotations'] - $exec['totalEngs']), 0, ',', ' ') ?> F
                            </td>

                            <td class="text-end">
                                <?= number_format($exec['totalOp'], 0, ',', ' ') ?> F
                            </td>

                            <td class="text-end fw-bold 
                                <?= (($exec['totalEngs'] - $exec['totalOp']) > 0) ? 'text-success' : 'text-danger' ?>">
                                <?= number_format(($exec['totalEngs'] - $exec['totalOp']), 0, ',', ' ') ?> F
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

                </tbody>
            </table>

        </div>
    </div>

    <!-- ACTIONS -->
    <div class="d-flex justify-content-between mt-3">
        <div>
           
        </div>

        <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

</main>

<?php include '../../includes/footer.php';?>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- Buttons DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<!-- Export Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<!-- Export PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function () {
    $('#tableOP').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        dom: 'lBfrtip', //  ajout du "l"

        buttons: [{
                extend: 'excel',
                text: 'Exporter Excel',
            },
            {
                extend: 'print',
                text: 'Imprimer',

                customize: function(win) {
                    // Réduire taille du texte
                    $(win.document.body).css('font-size', '10px');

                    // Ajuster le tableau
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', '10px');

                    // Centrer le titre
                    $(win.document.body).find('h1').css('text-align', 'center');

                    // Marges de page
                    $(win.document.body).css('margin', '20px');
                }
            }
        ],
        language: {
            search: "<i class='bi bi-search'></i> Rechercher :",
            lengthMenu: "Afficher _MENU_ lignes",
            info: "Affichage de _START_ à _END_ sur _TOTAL_ lignes",
            paginate: {
                previous: "Précédent",
                next: "Suivant"
            },
            zeroRecords: "Aucun résultat trouvé"
        }
    });
});
</script>

<!-- STYLE -->
<style>
.custom-header {
    background-color: #4655a4;
    color: #fff;
}

#tableOP th,
#tableOP td {
    text-align: left !important;
    vertical-align: middle;
    font-size:13px;
}

.text-end {
    text-align: right !important;
}
</style>