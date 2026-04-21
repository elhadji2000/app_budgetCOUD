<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php"); // Redirige vers la page de connexion
    exit();
}
?>
<?php include '../../includes/fonctions.php';
$date1 = $_GET['date1'];
$date2 = $_GET['date2'];
$numCompte = $_GET['numCompte'];
$data = getCompteByNum($numCompte);
$engs = getEngsByCompteAndDate2($numCompte, $date1, $date2);
$TDotations = 0;
$TEngs = 0;
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
                    <i class="bi bi-graph-up-arrow"></i> EXECUTION DU :<?= date('d/m/Y', strtotime($date1)); ?> AU
                    <?= date('d/m/Y', strtotime($date2)); ?>
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
                        <th>Numero</th>
                        <th>Compte</th>
                        <th>Date_Realisation</th>
                        <th>Objet</th>
                        <th>Type_Eng</th>
                        <th>Bènèficiaire</th>
                        <th>Montant</th>
                        <th>Bon_Eng</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php
                $n=1;
                if (!empty($engs)) :
                    foreach ($engs as $eng) : ?>
                    <tr>
                        <td><?= formatNumEng($eng['idEng']); ?></td>
                        <td>
                            <?= $eng['numCompte']; ?>
                        </td>

                        <td>
                            <?= date('d/m/Y', strtotime($eng['dateEng'])); ?>
                        </td>
                        <td>
                            <?= $eng['objet']; ?>
                        </td>
                        <td>
                            <?= $eng['type_eng']; ?>
                        </td>
                        <td>
                            <?= $eng['nom']; ?>
                        </td>
                        <td><?= number_format($eng['montant'], 0, ',', ' '); ?>F</td>
                        <td>
                            <a target="_blank" href="../engagements/be_vue_pdf.php?id=<?= $eng['idEng']; ?>"
                                class="btn btn-sm btn-warning">
                                BE_PDF
                            </a>
                        </td>
                    </tr>
                    <?php 
                    $TEngs += $eng['montant'];
                    endforeach;?>
                    <?php endif; ?>

                </tbody>
                <tfooter>
                    <tr>
                        <th colspan="6" style="text-align: center;color: black;">TOTAL DES REALISATIONS DU COMPTE</th>
                        <th colspan="2" style="text-align: left;color: black;">
                            <?= number_format($TEngs, 0, ',', ' '); ?>F</th>
                    </tr>
                </tfooter>
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
    font-size: 12px !important;
}
</style>