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
            <i class="bi bi-folder2-open"></i> LISTE DES ENGAGEMENTS
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
                        <th>NumEngs</th>
                        <th>Date</th>
                        <th>Type_eng</th>
                        <th>Montant</th>
                        <th>Bénéficiaire</th>
                        <th>Statut</th>
                        <th>Action(s)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $n = 1;
                        $engsTemp = getEngagementsTemp();
                        $engs = getEngs();

                    if (!empty($engsTemp) || !empty($engs)):

                    // D'abord les engagements TEMP
                    foreach ($engsTemp as $eng): ?>
                    <tr style="background-color: #fff9e6;">
                        <td><?= $n++; ?></td>
                        <td><?= $eng['numCompte']; ?></td>
                        <td><?= formatNumEng($eng['idEng']); ?></td>
                        <td><?= date('d/m/Y', strtotime($eng['dateEng'])) ; ?></td>
                        <td><?= $eng['type_eng']; ?></td>
                        <td class="text-end fw-bold"><?= number_format($eng['montant'], 0, ',', ' '); ?> F</td>
                        <td><?= $eng['nom']; ?></td>
                        <td>
                            <span class="badge bg-warning text-dark">En attente</span>
                        </td>
                        <td>
                            <?php if ($_SESSION['priv'] === 'admin' || $_SESSION['priv'] === 'eng_val' || $_SESSION['priv'] === 'Cf_D' || $_SESSION['priv'] === 'eng_all' ): ?>
                            <a href="traitement_eng.php?valider_id=<?= $eng['idEng']; ?>"
                                onclick="return confirm('Êtes-vous sûr de vouloir valider cet engagement ?')"
                                class="btn btn-sm btn-success">
                                <i class="bi bi-check-lg"></i> Valider
                            </a>
                            <?php else: ?>
                            <span class="btn btn-sm btn-secondary" style="opacity: 0.6; cursor: not-allowed;"
                                title="Accès réservé">
                                <i class="bi bi-check-lg"></i> Valider
                            </span>
                            <?php endif; ?>

                            <a class="btn btn-sm btn-danger" href="traitement_eng.php?supprTemp=<?= $eng['idEng']; ?>"
                                onclick="return confirm('Annuler cet engagement temporaire ?')">
                                <i class="bi bi-x-lg"></i> Annuler
                            </a>
                        </td>
                    </tr>
                    <?php endforeach;

                    // Ensuite les engagements validés
                    foreach ($engs as $eng): ?>
                    <tr>
                        <td><?= $n++; ?></td>
                        <td><?= $eng['numCompte']; ?></td>
                        <td><?= formatNumEng($eng['idEng']); ?></td>
                        <td><?= date('d/m/Y', strtotime($eng['dateEng'])) ; ?></td>
                        <td><?= $eng['type_eng']; ?></td>
                        <td class="text-center fw-bold"><span class="montant-cell">
                                <?= number_format($eng['montant'], 0, ',', ',') ?></span><small
                                class="text-muted">FCFA</small></td>
                        <td><?= $eng['nom']; ?></td>
                        <td>
                            <span class="badge bg-success">Validé</span>
                        </td>
                        <td>
                            <!-- Si l'engagement est validé, on ne peut plus l'annuler -->
                            <span class="btn btn-sm btn-secondary" style="cursor: not-allowed; opacity: 0.6;"
                                title="Engagement validé, annulation impossible">
                                <i class="bi bi-x-lg"></i> Annuler
                            </span>

                            <a target="_blank" href="be_vue_pdf?id=<?= $eng['idEng']; ?>"
                                class="btn btn-sm btn-warning">
                                <i class="bi bi-file-pdf"></i> BE_PDF
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>

        <!-- Modals de succès -->
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-info">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="successModalLabel">
                            <i class="bi bi-check-circle"></i> Succès
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        Engagement annulé avec succès !
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        });
        </script>
        <?php endif; ?>

        <?php if (isset($_GET['success']) && $_GET['success'] == 2): ?>
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-success">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="successModalLabel">
                            <i class="bi bi-check-circle"></i> Succès
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        Engagement validé avec succès !
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
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

<script>
$(document).ready(function() {
    $('#tableComptes').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        language: {
            search: "<i class='bi bi-search'></i> Rechercher :",
            lengthMenu: "Afficher _MENU_ lignes",
            info: "Affichage de _START_ à _END_ sur _TOTAL_ engagements",
            paginate: {
                previous: "Précédent",
                next: "Suivant"
            },
            zeroRecords: "Aucun résultat trouvé"
        },
        columnDefs: [{
                targets: [0, 1, 2, 3, 4, 6, 7],
                className: 'text-start'
            },
            {
                targets: [5],
                className: 'text-center'
            },
            {
                targets: [8],
                className: 'text-center',
                orderable: false
            }
        ]
    });
});
</script>

<!-- Styles personnalisés -->
<style>
#tableComptes th,
#tableComptes td {
    vertical-align: middle;
    font-size: 13px;
    padding: 8px 10px;
}

#tableComptes thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 11px !important;
    letter-spacing: 0.5px;
    padding: 10px 10px !important;
}

#tableComptes tbody tr:hover {
    background-color: #f8f9ff !important;
}

#tableComptes .btn {
    border-radius: 4px !important;
    padding: 3px 10px;
    font-size: 12px;
}

#tableComptes .btn i {
    font-size: 12px;
}

#tableComptes .badge {
    font-size: 11px;
    padding: 4px 8px;
    font-weight: 500;
}

/* Pour les boutons désactivés */
#tableComptes .btn-secondary[style*="cursor: not-allowed"] {
    pointer-events: none;
}

/* DataTables override */
.dataTables_wrapper .dataTables_filter input {
    border: 1.5px solid #ced4da;
    border-radius: 6px;
    padding: 5px 12px;
    font-size: 13px;
    background: #fafbfc;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #4655a4;
    outline: none;
    box-shadow: 0 0 0 3px rgba(70, 85, 164, 0.15);
}

.dataTables_wrapper .dataTables_length select {
    border: 1.5px solid #ced4da;
    border-radius: 6px;
    padding: 4px 8px;
    font-size: 13px;
    background: #fafbfc;
}

.dataTables_wrapper .dataTables_info {
    font-size: 13px;
    color: #6c757d;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 4px 12px;
    border-radius: 4px;
    border: 1px solid #e0e5ec;
    margin: 0 2px;
    font-size: 13px;
    background: #fff;
    color: #495057 !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #4655a4 !important;
    color: #fff !important;
    border-color: #4655a4;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #e8edff !important;
    border-color: #4655a4;
}

/* Responsive */
@media (max-width: 768px) {

    #tableComptes th,
    #tableComptes td {
        font-size: 11px !important;
        padding: 5px 6px !important;
    }

    #tableComptes .btn {
        font-size: 10px !important;
        padding: 2px 6px !important;
    }

    #tableComptes .btn i {
        font-size: 10px !important;
    }
}
</style>