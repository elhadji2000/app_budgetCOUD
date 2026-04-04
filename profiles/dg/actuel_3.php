<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php"); // Redirige vers la page de connexion
    exit();
}
?>
<?php include '../../includes/fonctions.php';
$numCompte = $_GET['numCompte'];
$op = isset($_GET['op']) ? $_GET['op'] : 0;
$data = getCompteByNum($numCompte);
$engs = getEngsByCompte($numCompte);
$TDotations = sommeDotByCompte($numCompte);
$TEngs = 0;
?>
<?php include '../../includes/header.php';?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<main class="container-fluid mt-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 text-primary fw-bold">
            <i class="bi bi-folder2-open"></i> Realisation de <?= $data['numCompte']; ?> : <?= $data['libelle']; ?>
        </h5>
        <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <!-- Tableau -->
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="table-responsive">
                <table id="tableComptes" class="table table-striped table-hover align-middle">
                    <thead class="text-white custom-header">
                        <tr>
                            <th>#</th>
                            <th>Numero</th>
                            <th>Compte</th>
                            <th>Date</th>
                            <th>REF</th>
                            <th>Type_eng</th>
                            <th>Bènèficiaire</th>
                            <th>Montant</th>
                            <th>Bon_Eng</th>
                            <?php if ($op == 1): ?>
                            <th>Mandat</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php
                $n=1;
                if (!empty($engs)) :
                    foreach ($engs as $eng) : ?>
                        <tr>
                            <td><?= $n++; ?></td>
                            <td><?= formatNumEng($eng['idEng']); ?></td>
                            <td style="padding: 15px;">
                                <?= $eng['numCompte']; ?>
                            </td>

                            <td>
                                <?= date('d/m/Y', strtotime($eng['dateEng'])) ; ?>
                            </td>
                            <td>
                                <?php if (!empty($eng['numFact'])): ?>
                                <?= strtoupper($eng['numFact']); ?>
                                <?php else: ?>
                                —
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= $eng['type_eng']; ?>
                            </td>
                            <td>
                                <?= $eng['nom']; ?>
                            </td>
                            <td class="text-start fw-bold"><?= number_format($eng['montant'], 0, ',', ' '); ?> F</td>
                            <td class="text-center">
                                <a class="btn btn-sm btn-warning" target="_blank" href="../engagements/be_vue_pdf.php?id=<?= $eng['idEng'] ?>">vue_pdf</a>
                            </td>
                            <?php if ($op == 1): ?>
                            <td class="text-center">
                                <?php if (!empty($eng['numFact'])): ?>
                                <a class="btn btn-sm btn-primary" target="_blank"  href="../engagements/mandat_details.php?id=<?= $eng['idEng'] ?>">vue_pdf</a>
                                <?php else: ?>
                                <span title="Pas d'opération" style="color: grey; cursor: not-allowed;">vue_pdf</span>
                                <?php endif; ?>
                            </td>

                            <?php endif; ?>
                        </tr>
                        <?php 
                        $TEngs += $eng['montant'];
                        endforeach;?>
                        <?php endif; ?>

                    </tbody>
                    <tfooter>
                        <tr>
                            <th colspan="7" class="text-center" style="text-align: left;">TOTAL DES REALISATIONS DU COMPTE</th>
                            <th colspan="3" class="text-center" style="text-align: left;">
                                <?= number_format($TEngs, 0, ',', ' '); ?> F</th>
                        </tr>
                        <tr>
                            <th colspan="7" class="text-center" style="text-align: left;">
                                SOLDE DISPONIBLE DU COMPTE
                            </th>
                            <th colspan="3" class="text-center" style="text-align: left;">
                                <?= number_format(($TDotations-$TEngs), 0, ',', ' '); ?> F</th>
                        </tr>
                    </tfooter>
                </table>
            </div>
        </div>
</main>
<?php include '../../includes/footer.php';?>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#tableComptes').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        responsive: true,
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

<style>
.custom-header {
    background-color: #4655a4;
    color: #fff;
}

#tableComptes th,
#tableComptes td {
    vertical-align: middle;
    font-size: 13px;
}

.text-end {
    text-align: right !important;
}
.text-center {
    text-align: center !important;
}
</style>