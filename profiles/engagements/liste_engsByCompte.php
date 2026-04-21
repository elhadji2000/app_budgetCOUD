<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php"); // Redirige vers la page de connexion
    exit();
}
?>
<?php include '../../includes/fonctions.php';
$numCompte = $_GET['numc'];
$data = getCompteByNum($numCompte);
$engs = getEngsByCompte($numCompte);
?>
<?php include '../../includes/header.php';?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<main class="container-fluid mt-3">
    <!-- HEADER -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">

            <div>
                <h5 class="fw-bold text-primary mb-1">
                    <i class="bi bi-graph-up-arrow"></i> LES ENGAGEMENTS 
                </h5>
                <small class="text-muted"><strong>Compte <?= $data['numCompte']; ?> :
                        <?= $data['libelle']; ?></strong></small>
            </div>

            <div class="text-end">
                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>

        </div>
    </div>

    <!-- Tableau -->
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <table id="tableComptes" class="table table-striped table-hover align-middle">
                <thead class="text-white" style="background-color: #4655a4;">
                    <tr>
                    <th>N°</th>
                    <th>NumEngs</th>
                    <th>Date</th>
                    <th>Type_eng</th>
                    <th>Objet</th>
                    <th>Montant</th>
                    <th>Bénéficiaire</th>
                    <th>details</th>
                    <th>Annuler</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $n = 1;
                if (!empty($engs)) :
                    foreach ($engs as $eng) : ?>
                <tr>
                    <td><?= $n; ?></td>
                    <td>
                        <?= formatNumEng($eng['idEng']); ?>
                    </td>
                    <td><?= date('d/m/Y', strtotime($eng['dateEng'])); ?></td>
                    <td><?= $eng['type_eng']; ?></td>
                    <td><?= $eng['objet']; ?></td>
                    <td class="fw-bold"><?= number_format($eng['montant'], 0, ',', ' '); ?> F</td>
                    <td><?= $eng['nom']; ?></td>
                    <td>
                        <a class="btn btn-sm btn-warning" target="_blank" href="be_vue_pdf?id=<?= $eng['idEng'] ?>">BE_Vue_PDF</a>
                    </td>
                    <td>
                        <?php if (!isEngagementUsed($eng['idEng'])): ?>
                        <a class="btn btn-sm btn-danger" href="supprimer_engagement?id=<?= $eng['idEng'] ?>"
                            onclick="return confirm('Êtes-vous sûr de vouloir Annuler cet engagement ?')">Annuler</a>
                        <?php else: ?>
                        <span class="btn btn-sm btn-secondary" style="color: black; cursor: not-allowed;"
                            title="Engagement utilisé, Annulation désactivée">Annuler</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php
                    $n++;
                    endforeach;
                ?>
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
    font-size:12px !important;
}
</style>