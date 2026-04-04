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
            <i class="bi bi-folder2-open"></i> LISTE DES COMPTES
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
                        <th>NumCompte</th>
                        <th>Libellé</th>
                        <th>NumCp</th>
                        <th>Code</th>
                        <th>Nature</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $comptes = getAllCompte();
                        $n = 0;
                    ?>

                    <?php if (!empty($comptes)) : ?>
                    <?php foreach ($comptes as $compte) : ?>
                    <?php $n++; ?>
                    <tr>
                        <td><?= $n ?></td>
                        <td><?= htmlspecialchars($compte['numCompte']) ?></td>
                        <td><?= htmlspecialchars($compte['libelle']) ?></td>
                        <td><?= htmlspecialchars($compte['numCp']) ?></td>
                        <td><?= htmlspecialchars($compte['code']) ?></td>
                        <td><?= htmlspecialchars(strtoupper($compte['nature'])) ?></td>
                        <td>
                            <a href="#" onclick="return confirm('Voulez-vous archiver ce compte ?')"
                                class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-archive"></i> Archiver
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-danger text-start">
                            <i class="bi bi-exclamation-triangle"></i> Aucun compte trouvé
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>

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
    text-align: left !important;
    vertical-align: middle;
    font-size: 13px !important;
}
</style>