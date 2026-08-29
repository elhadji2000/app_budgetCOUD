<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit();
}

include '../../includes/fonctions.php';
include '../../includes/header.php';
?>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
/* ========== STYLES ========== */
.custom-header th {
    background-color: #4655a4;
    color: #fff;
    font-weight: 600;
    font-size: 12px !important;
    padding: 8px 6px !important;
    border-bottom: 0 !important;
    white-space: nowrap;
}

#tableFournisseurs th,
#tableFournisseurs td {
    text-align: center !important;
    vertical-align: middle;
    font-size: 12px !important;
    padding: 6px 4px !important;
}

#tableFournisseurs .action {
    text-align: right !important;
}

.btn-sm {
    font-size: 11px !important;
    padding: 2px 10px !important;
}

.btn-action {
    font-size: 11px;
    padding: 2px 10px;
    border-radius: 4px;
    border: none;
    display: inline-block;
    margin: 0 2px;
    text-decoration: none;
    color: #fff;
}

.btn-action.btn-edit {
    background: #4655a4;
}

.btn-action.btn-edit:hover {
    background: #35438a;
    color: #fff;
}

.btn-action.btn-delete {
    background: #dc3545;
}

.btn-action.btn-delete:hover {
    background: #c82333;
    color: #fff;
}

.btn-action.btn-delete.disabled {
    background: #adb5bd;
    cursor: not-allowed;
    opacity: 0.6;
}

.btn-action.btn-delete.disabled:hover {
    background: #adb5bd;
}

/* ========== DATATABLES ========== */
.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 4px 10px;
    font-size: 12px;
}

.dataTables_wrapper .dataTables_length select {
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 2px 6px;
    font-size: 12px;
}

.dataTables_wrapper .dataTables_info {
    font-size: 12px;
    color: #6c757d;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 3px 10px;
    border-radius: 4px;
    border: 1px solid #e0e5ec;
    margin: 0 2px;
    font-size: 12px;
    background: #fff;
    color: #495057 !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #4655a4 !important;
    color: #fff !important;
    border-color: #4655a4;
}

.dt-buttons .btn {
    font-size: 11px;
    padding: 3px 12px;
    border-radius: 4px;
    border: 1px solid #ced4da;
    background: #fff;
    color: #495057;
    margin-right: 4px;
}

.dt-buttons .btn:hover {
    background: #f8f9fa;
    border-color: #4655a4;
    color: #4655a4;
}

/* ========== BADGE NATURE ========== */
.badge-nature {
    font-size: 10px;
    padding: 2px 10px;
    border-radius: 12px;
    font-weight: 500;
    display: inline-block;
}

.badge-nature.entreprise {
    background: #dbeafe;
    color: #1e40af;
}

.badge-nature.physique {
    background: #fce4ec;
    color: #b71c1c;
}

.badge-nature.association {
    background: #e8f5e9;
    color: #1b5e20;
}

.badge-nature.autre {
    background: #f3e5f5;
    color: #4a148c;
}

.badge-nature.non-defini {
    background: #f1f3f5;
    color: #6c757d;
}

.text-ellipsis {
    max-width: 120px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: inline-block;
}

@media (max-width: 768px) {

    #tableFournisseurs th,
    #tableFournisseurs td {
        font-size: 11px !important;
        padding: 4px 3px !important;
    }

    .text-ellipsis {
        max-width: 60px;
    }

    .btn-action {
        font-size: 10px;
        padding: 1px 6px;
    }
}
</style>

<main class="container-fluid mt-3">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-primary fw-bold mb-0" style="font-size:14px;">
            <i class="bi bi-people"></i> LISTE DES FOURNISSEURS ENREGISTRÉS
        </h5>

        <div>
            <a href="add_fourn.php" class="btn btn-success btn-sm">
                <i class="bi bi-plus-circle"></i> Nouveau
            </a>
            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card shadow-sm border-0">
        <div class="card-body" style="padding: 10px;">

            <table id="tableFournisseurs" class="table table-striped table-hover align-middle">

                <thead class="custom-header">
                    <tr>
                        <th style="width:40px;">N°</th>
                        <th>NumF</th>
                        <th>Nom</th>
                        <th>Adresse</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Nature</th>
                        <th>NINEA</th>
                        <th>RCCM</th>
                        <th style="width:120px;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $fournisseurs = getAllFournisseurs();
                    $n = 1;
                    if (!empty($fournisseurs)):
                        foreach ($fournisseurs as $fournisseur):
                            // Classe CSS pour le badge nature
                            $natureClass = 'non-defini';
                            $natureLabel = $fournisseur['nature'] ?? 'Non défini';
                            if (strtolower($natureLabel) == 'entreprise') $natureClass = 'entreprise';
                            elseif (strtolower($natureLabel) == 'personne physique') $natureClass = 'physique';
                            elseif (strtolower($natureLabel) == 'association') $natureClass = 'association';
                    ?>
                    <tr>
                        <td><?= $n; ?></td>
                        <td><strong><?= htmlspecialchars($fournisseur['numFourn'] ?? ''); ?></strong></td>
                        <td class="text-start"><?= htmlspecialchars($fournisseur['nom'] ?? ''); ?></td>
                        <td class="text-start
                            title="<?= htmlspecialchars($fournisseur['adresse'] ?? ''); ?>">
                            <?= htmlspecialchars($fournisseur['adresse'] ?? ''); ?>
                        </td>
                        <td><?= htmlspecialchars($fournisseur['contact'] ?? ''); ?></td>
                        <td>
                            <?php if (!empty($fournisseur['email'])): ?>
                            <a href="mailto:<?= htmlspecialchars($fournisseur['email']) ?>"
                                style="text-decoration:none;font-size:11px;">
                                <?= htmlspecialchars($fournisseur['email']) ?>
                            </a>
                            <?php else: ?>
                            <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge-nature <?= $natureClass ?>">
                                <?= htmlspecialchars($natureLabel) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($fournisseur['ninea'] ?? '—'); ?></td>
                        <td><?= htmlspecialchars($fournisseur['rccm'] ?? '—'); ?></td>
                        <td class="action">
                            <?php if (!isFournisseurUsed($fournisseur['idFourn'])): ?>
                            <a href="supprimer_fournisseur.php?id=<?= $fournisseur['idFourn'] ?>"
                                class="btn-action btn-delete"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?')">
                                <i class="bi bi-trash"></i>
                            </a>
                            <?php else: ?>
                            <span class="btn-action btn-delete disabled"
                                title="Fournisseur utilisé, suppression désactivée">
                                <i class="bi bi-trash"></i>
                            </span>
                            <?php endif; ?>
                            <a href="add_fourn.php?idFourn=<?= $fournisseur['idFourn']; ?>" class="btn-action btn-edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    <?php $n++; ?>
                    <?php endforeach; ?>

                    <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-danger text-start">
                            <i class="bi bi-exclamation-triangle"></i> Aucun fournisseur trouvé
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>

            </table>

        </div>
    </div>

</main>

<?php include '../../includes/footer.php'; ?>

<!-- =====================================================
     SCRIPTS
====================================================== -->

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    $('#tableFournisseurs').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        order: [],
        dom: 'lBfrtip',
        buttons: [{
            extend: 'excel',
            text: 'Exporter Excel',
            exportOptions: {
                columns: ':not(:last-child)'
            }
        }, {
            extend: 'print',
            text: 'Imprimer',
            exportOptions: {
                columns: ':not(:last-child)'
            },
            customize: function(win) {
                $(win.document.body).css('font-size', '10px');
                $(win.document.body).find('table')
                    .css('font-size', '9px')
                    .addClass('compact');
                $(win.document.body).find('h1').css('text-align', 'center');
                $(win.document.body).css('margin', '20px');
            }
        }],
        language: {
            search: "<i class='bi bi-search'></i> Rechercher :",
            lengthMenu: "Afficher _MENU_ lignes",
            info: "Affichage de _START_ à _END_ sur _TOTAL_ fournisseurs",
            paginate: {
                previous: "Précédent",
                next: "Suivant"
            },
            zeroRecords: "Aucun résultat trouvé"
        }
    });
});
</script>