<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}

include '../includes/fonctions.php';

global $connexion;

/*
|--------------------------------------------------------------------------
| Récupération des marchés avec le fournisseur
|--------------------------------------------------------------------------
*/

$sql = '
    SELECT 
        m.id,
        m.reference,
        m.annee,
        m.montant,
        m.type_marche,
        m.objet,
        m.statut,
        m.date_creation,
        m.id_fournisseur,
        f.nom AS nom_fournisseur,
        f.numFourn AS num_fournisseur,
        COUNT(d.id) AS nombre_documents
    FROM sigm_marches m
    LEFT JOIN bud_fournisseur f ON f.idFourn = m.id_fournisseur
    LEFT JOIN sigm_marche_documents d ON d.marche_id = m.id
    GROUP BY m.id
    ORDER BY m.date_creation DESC
';

$result = mysqli_query($connexion, $sql);

if (!$result) {
    die('Erreur SQL : ' . mysqli_error($connexion));
}

include '../includes/header.php';
?>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
.statut-badge {
    font-size: 11px;
    padding: 3px 12px;
    border-radius: 20px;
    font-weight: 500;
    display: inline-block;
}

.statut-badge.en_attente {
    background: #fff3cd;
    color: #856404;
}
.statut-badge.engager {
    background: #2247c4;
    color: #0a0745;
}

.statut-badge.valide {
    background: #d4edda;
    color: #155724;
}

.statut-badge.annule {
    background: #f8d7da;
    color: #721c24;
}

.statut-badge.rejete {
    background: #f8d7da;
    color: #721c24;
}

#tableMarches th,
#tableMarches td {
    font-size: 13px;
    padding: 8px 8px;
    vertical-align: middle;
}

#tableMarches th {
    background-color: #4655a4;
    color: #fff;
    font-weight: 600;
    font-size: 12px !important;
    white-space: nowrap;
}

#tableMarches th:first-child {
    border-radius: 8px 0 0 0;
}

#tableMarches th:last-child {
    border-radius: 0 8px 0 0;
}

.btn-action {
    font-size: 12px;
    padding: 2px 8px;
    border-radius: 4px;
    border: none;
    display: inline-block;
    margin: 0 2px;
    text-decoration: none;
    color: #fff;
}

.btn-action.btn-view {
    background: #17a2b8;
}

.btn-action.btn-view:hover {
    background: #138496;
    color: #fff;
}

.btn-action.btn-validate {
    background: #28a745;
}

.btn-action.btn-validate:hover {
    background: #218838;
    color: #fff;
}

.btn-action.btn-cancel {
    background: #dc3545;
}

.btn-action.btn-cancel:hover {
    background: #c82333;
    color: #fff;
}

.btn-action.btn-edit {
    background: #4655a4;
}

.btn-action.btn-edit:hover {
    background: #35438a;
    color: #fff;
}

.text-ellipsis {
    max-width: 150px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: inline-block;
}

.fournisseur-info {
    font-size: 12px;
}

.fournisseur-info .nom {
    font-weight: 600;
}

.fournisseur-info .num {
    font-size: 10px;
    color: #6c757d;
}

.doc-count {
    font-size: 13px;
    font-weight: 500;
}

.montant-cell {
    font-weight: 600;
}

.dataTables_wrapper .dataTables_filter input {
    border: 1.5px solid #ced4da;
    border-radius: 6px;
    padding: 4px 10px;
    font-size: 13px;
    background: #fafbfc;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #4655a4;
    outline: none;
}

.dataTables_wrapper .dataTables_length select {
    border: 1.5px solid #ced4da;
    border-radius: 6px;
    padding: 3px 6px;
    font-size: 13px;
    background: #fafbfc;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 4px 10px;
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
    font-size: 12px;
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

@media (max-width: 768px) {

    #tableMarches th,
    #tableMarches td {
        font-size: 11px !important;
        padding: 4px 4px !important;
    }

    .text-ellipsis {
        max-width: 80px;
    }

    .btn-action {
        font-size: 10px;
        padding: 1px 6px;
    }
}
</style>

<main class="container-fluid mt-3">

    <!-- =====================================================
     EN-TÊTE
    ====================================================== -->

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold text-dark mb-1">
                    <i class="bi bi-folder2-open"></i>
                    Liste des marchés
                </h5>
                <small class="text-muted">
                    Gestion des dossiers de marchés
                </small>
            </div>
            <div>
                <a href="add_drp.php" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i>
                    Nouveau
                </a>
            </div>
        </div>
    </div>

    <!-- =====================================================
     TABLEAU
    ====================================================== -->

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="table-responsive">

                <table id="tableMarches" class="table table-striped table-hover align-middle">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Référence</th>
                            <th>Fournisseur</th>
                            <th>Objet</th>
                            <th>Type</th>
                            <th>Montant</th>
                            <th>Pièces</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php  $n = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $n; ?></td>
                            <!-- Référence -->
                            <td>
                                <strong>
                                    <?= htmlspecialchars($row['reference']) ?>
                                </strong>
                            </td>

                            <!-- Fournisseur -->
                            <td>
                                <?php if ($row['nom_fournisseur']): ?>
                                <div class="fournisseur-info">
                                    <span class="nom"><?= htmlspecialchars($row['nom_fournisseur']) ?></span>
                                </div>
                                <?php else: ?>
                                <span class="text-muted">Non défini</span>
                                <?php endif; ?>
                            </td>

                            <!-- Objet -->
                            <td>
                                <span class="text-ellipsis" title="<?= htmlspecialchars($row['objet']) ?>">
                                    <?= htmlspecialchars($row['objet']) ?>
                                </span>
                            </td>

                            <!-- Type -->
                            <td>
                                <?= htmlspecialchars($row['type_marche']) ?>
                            </td>

                            <!-- Montant -->
                            <td class="text-end">
                                <span class="montant-cell">
                                    <?= number_format($row['montant'], 0, ',', ' ') ?>
                                </span>
                                <small class="text-muted">FCFA</small>
                            </td>

                            <!-- Documents -->
                            <td class="text-center">
                                <span class="doc-count">
                                    <?= (int) $row['nombre_documents'] ?>
                                </span>
                            </td>

                            <!-- Statut -->
                            <td>
                                <span class="statut-badge <?= $row['statut'] ?>">
                                    <?php
                                            $statuts = [
                                                'en_attente' => 'En attente',
                                                'valide' => 'Validé',
                                                'annule' => 'Annulé',
                                                'engager' => 'Engager',
                                                'rejete' => 'Rejeté'
                                            ];
                                            echo $statuts[$row['statut']] ?? $row['statut'];
                                            ?>
                                </span>
                            </td>

                            <!-- Date -->
                            <td>
                                <?= date('d/m/Y', strtotime($row['date_creation'])) ?>
                            </td>

                            <!-- Actions -->
                            <td>
                                <div class="d-flex gap-1">

                                    <!-- Voir -->
                                    <a href="traitement_marche.php?id=<?= $row['id'] ?>" class="btn-action btn-view"
                                        title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <?php if ($row['statut'] === 'en_attente'): ?>
                                    <!-- Valider -->
                                    <form method="POST" action="traiter_marche.php" class="d-inline">
                                        <input type="hidden" name="marche_id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="action" value="valider">
                                        <button type="submit" class="btn-action btn-validate" title="Valider"
                                            onclick="return confirm('Voulez-vous valider ce marché ?')">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>

                                    <!-- Annuler -->
                                    <button type="button" class="btn-action btn-cancel" title="Annuler"
                                        data-bs-toggle="modal" data-bs-target="#annulationModal"
                                        data-id="<?= $row['id'] ?>">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                    <?php endif; ?>

                                </div>
                            </td>
                        </tr>
                        <?php $n++; ?>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="bi bi-folder2-open d-block mb-2"></i>
                                Aucun marché enregistré
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>

                </table>

            </div>

        </div>
    </div>

</main>

<!-- =========================================================
     MODAL ANNULATION
========================================================= -->

<div class="modal fade" id="annulationModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="traiter_marche.php" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Annuler le marché</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">
                    Veuillez indiquer le motif de l'annulation.
                </p>
                <div class="mb-3">
                    <label class="form-label">Motif</label>
                    <textarea name="motif" class="form-control" rows="4" required></textarea>
                </div>
                <input type="hidden" name="marche_id" id="modalMarcheId">
                <input type="hidden" name="action" value="annuler">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    Fermer
                </button>
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="bi bi-x-circle"></i>
                    Annuler le marché
                </button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<!-- =========================================================
     JAVASCRIPT
========================================================= -->

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

    $('#tableMarches').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        dom: 'lBfrtip',
        buttons: [{
            extend: 'excel',
            text: 'Exporter Excel'
        }, {
            extend: 'print',
            text: 'Imprimer'
        }],
        language: {
            search: "Rechercher :",
            lengthMenu: "Afficher _MENU_ lignes",
            info: "Affichage de _START_ à _END_ sur _TOTAL_ lignes",
            infoEmpty: "Aucune ligne à afficher",
            zeroRecords: "Aucun résultat trouvé",
            emptyTable: "Aucun marché enregistré",
            paginate: {
                previous: "Précédent",
                next: "Suivant"
            }
        },
        columnDefs: [{
            targets: [5, 6, 8],
            orderable: false
        }]
    });

    /*
    |--------------------------------------------------------------------------
    | Modal annulation
    |--------------------------------------------------------------------------
    */

    $('#annulationModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const id = button.data('id');
        $('#modalMarcheId').val(id);
    });

});
</script>