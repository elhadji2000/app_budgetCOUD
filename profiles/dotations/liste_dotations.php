<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit();
}
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
            <i class="bi bi-folder2-open"></i> LISTE DES DOTATIONS
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
                        <th>Num_Compte</th>
                        <th>Liblelle</th>
                        <th>Date</th>
                        <th>Volume</th>
                        <th>Type</th>
                        <th>Agent</th>
                        <th>Action(s)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                $dotations = getAllDotations();
                $n = 1;
                if (!empty($dotations)) :
                    foreach ($dotations as $dotation) : ?>
                    <tr>
                        <td><?= $n++; ?></td>
                        <td><?= $dotation['numCompte']; ?></td>
                        <td class="text-align-left libelle"><?= $dotation['libelleCompte']; ?></td>
                        <td><?= date('d/m/Y', strtotime($dotation['date'])); ?></td>
                        <td class="fw-bold somme"><?= number_format($dotation['volume'], 0, ',', ' '); ?> F</td>
                        <td>
                            <span
                                class="text-decoration-underline <?= ($dotation['type'] == 'initiale') ? 'text-info' : 'text-warning'; ?>">
                                <?= ucfirst($dotation['type']); ?>
                            </span>
                        </td>
                        <td><?= $dotation['log']; ?></td>
                        <td class="somme">
                            <?php if (!isDotationUsed($dotation['idDot'])): ?>
                            <a href="traitement_dot.php?suppr=<?= $dotation['idDot'] ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('Supprimer cette dotation ?')">
                                Annuler
                            </a>
                            <?php else: ?>
                            <span class="text-muted text-decoration-underline" title="Dotation utilisée">Annuler</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach;?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>


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
                    Dotation supprimer avec succès !
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
</main>
<?php include '../../includes/footer.php';?>

<!-- jQuery + DataTables -->
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
$(document).ready(function() {
    $('#tableComptes').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],

        order: [],

        dom: 'lBfrtip', //  ajout du "l"

        buttons: [{
                extend: 'excel',
                text: 'Exporter Excel',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            },
            {
                extend: 'print',
                text: 'Imprimer',
                exportOptions: {
                    columns: ':not(:last-child)'
                },

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
    text-align: center !important;
    vertical-align: middle;
    font-size: 13px !important;
}

#tableComptes .somme {
    text-align: right !important;
}
#tableComptes .libelle{
    text-align: left !important;
}
</style>