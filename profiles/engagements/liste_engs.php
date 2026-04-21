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
                        <!-- <th>Objet</th> -->
                        <th>Montant</th>
                        <th>Beneficiare</th>
                        <th>Valider</th>
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
                    <tr>
                        <!-- Jaune clair pour temp -->
                        <td><?= $n++; ?></td>
                        <td><?= $eng['numCompte']; ?></td>
                        <td><?= formatNumEng($eng['idEng']); ?> <small class="text-warning">(temp)</small></td>
                        <td><?= date('d/m/Y', strtotime($eng['dateEng'])) ; ?></td>
                        <td><?= $eng['type_eng']; ?></td>
                        <!-- <td><?= $eng['objet']; ?></td> -->
                        <td class="text-align-rigth somme fw-bold"><?= number_format($eng['montant'], 0, ',', ' '); ?> F </td>
                        <td><?= $eng['nom']; ?></td>
                        <td>
                            <?php if ($_SESSION['priv'] === 'admin'): ?>
                            <a href="traitement_eng.php?valider_id=<?= $eng['idEng']; ?>"
                                onclick="return confirm('Êtes-vous sûr de vouloir valider cet engagement ?')"
                                class="btn btn-sm  btn-success">Valider</a>
                            <?php else: ?>
                            <span class="btn btn-sm btn-secondary" style="opacity: 0.5; cursor: not-allowed;"
                                title="Accès réservé à l'administrateur">
                                valider
                            </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <a class="btn btn-sm btn-danger" href="traitement_eng.php?supprTemp=<?= $eng['idEng']; ?>"
                                onclick="return confirm('Annuler cet engagement temporaire ?')">Annuler</a>
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
                        <!-- <td><?= $eng['objet']; ?></td> -->
                        <td class="text-align-right somme fw-bold"><?= number_format($eng['montant'], 0, ',', ' '); ?> F</td>
                        <td><?= $eng['nom']; ?></td>
                        <td><span style="cursor: not-allowed;" title="Engagement valider"
                                class="badge bg-secondary">Valider</span></td>
                        <td class="somme">
                            <?php if (!isEngagementUsed($eng['idEng'])): ?>
                            <a class="btn btn-sm btn-danger" href="traitement_eng.php?suppr=<?= $eng['idEng'] ?>"
                                onclick="return confirm('Êtes-vous sûr de vouloir Annuler cet engagement ?')">Annuler</a>
                            <?php else: ?>
                            <span class="btn btn-sm btn-secondary" style="cursor: not-allowed; text-decoration:underline;"
                                title="Engagement utilisé, Annuler désactivée">Annuler</span>
                            <?php endif; ?>
                            | <a target="_blank" href="be_vue_pdf?id=<?= $eng['idEng']; ?>"
                                class="btn btn-sm btn-warning">
                                BE_PDF
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>

            </table>
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
                        Engagement Annuler avec succès !
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
                        Engagement valider avec succès !
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
    font-size:13px;
}
#tableComptes .somme {
    text-align: right !important;
}
</style> 