<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit();
}
include '../../includes/fonctions.php';
$idCp = $_GET['idCp'];
$op = isset($_GET['op']) ? $_GET['op'] : 0;
$comptep = getComptePById($idCp);

$TDotations = $TEngs = $TOp = $TDotationInitiale = $TDotationRemanier = 0;

$execs1 = getExecution_2($idCp);
$showRemanier = false;

foreach ($execs1 as $exec) {
    if ($exec['totalDotRemanier'] != 0) {
        $showRemanier = true;
        break;
    }
}
?>
<?php include '../../includes/header.php';?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<main class="container-fluid mt-3">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 text-primary fw-bold">
            <i class="bi bi-folder2-open"></i> Etat du compte principal <?= $comptep['numCp']; ?> :
            <?= $comptep['libelle']; ?>
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
                            <th>Compte_principal</th>
                            <th>Libelle</th>
                            <th class="text-end">Dot_Initiale</th>
                            <?php if ($showRemanier): ?>
                            <th class="text-end">Variation</th>
                            <th class="text-end">Dot_Remaniee</th>
                            <?php endif; ?>
                            <th class="text-end">Realisation</th>
                            <th class="text-end">Taux</th>
                            <th class="text-end">Disponible</th>
                            <?php if ($op == 1): ?>
                            <th class="text-end">O.P</th>
                            <th class="text-end">Diff Eng/Op</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $TDotations = $TEngs = $TOp = $TDotationInitiale = $TDotationRemanier = 0;

                        if (!empty($execs1)) :
                            foreach ($execs1 as $exec) :

                                //  REALISATION
                                $realisation = ($exec['typeCompte'] == 'ressource' || $exec['typeCompte'] == 'produit' )
                                    ? $exec['totalRecettes']
                                    : $exec['totalEngs'];

                                //  TAUX
                                $taux = ($exec['totalDotations'] == 0) 
                                    ? 0 
                                    : ($realisation * 100) / $exec['totalDotations'];

                                //  DISPONIBLE
                                $disponible = ($exec['typeCompte'] == 'ressource' || $exec['typeCompte'] == 'produit' ) 
                                    ? null 
                                    : ($exec['totalDotations'] - $realisation);

                                //  COULEURS
                                if ($exec['typeCompte'] == 'ressource' || $exec['typeCompte'] == 'produit' ) {
                                    $badgeClass = ($taux < 100) ? 'bg-danger' : (($taux == 100) ? 'bg-success' : 'bg-primary');
                                } else {
                                    $badgeClass = ($taux >= 80) ? 'bg-danger' : (($taux >= 50) ? 'bg-warning text-dark' : 'bg-success');
                                }
                        ?>

                        <tr
                            class="<?= ($exec['typeCompte'] == 'ressource' || $exec['typeCompte'] == 'produit' ) ? 'table-primary' : 'table-danger' ?>">

                            <td><?= $exec['numCompte']; ?></td>
                            <td><?= $exec['libelleC']; ?></td>

                            <td class="text-end">
                                <?= number_format($exec['totalDotInitial'], 0, ',', ' '); ?>
                            </td>

                            <?php if ($showRemanier): ?>
                            <td class="text-end">
                                <?= number_format($exec['totalDotRemanier'], 0, ',', ' '); ?>
                            </td>
                            <td class="text-end">
                                <?= number_format($exec['totalDotations'], 0, ',', ' '); ?>
                            </td>
                            <?php endif; ?>

                            <!-- REALISATION -->
                            <td class="text-end">
                                <?php if ($exec['typeCompte'] == 'ressource' || $exec['typeCompte'] == 'produit' ): ?>

                                <span class="fw-bold text-danger">
                                    <?= number_format($realisation, 0, ',', ' '); ?>
                                </span>

                                <?php else: ?>

                                <a href="actuel_3?numCompte=<?= urlencode($exec['numCompte']); ?>"
                                    class="text-decoration-underline fw-bold text-primary">
                                    <?= number_format($realisation, 0, ',', ' '); ?>
                                </a>

                                <?php endif; ?>
                            </td>

                            <!-- TAUX -->
                            <td class="text-end">
                                <span class="badge <?= $badgeClass ?>">
                                    <?= number_format($taux, 2); ?> %
                                </span>
                            </td>

                            <!-- DISPONIBLE -->
                            <td class="text-end">
                                <?= ($exec['typeCompte'] == 'ressource' || $exec['typeCompte'] == 'produit' ) 
                                    ? '-' 
                                    : number_format($disponible, 0, ',', ' ') ?>
                            </td>

                            <?php if ($op == 1): ?>
                            <td class="text-end">
                                <?= number_format($exec['totalOp'], 0, ',', ' '); ?>
                            </td>
                            <td class="text-end">
                                <?= number_format($realisation - $exec['totalOp'], 0, ',', ' '); ?>
                            </td>
                            <?php endif; ?>

                        </tr>

                        <?php
                        //  TOTAUX
                            $TDotations += $exec['totalDotations'];
                            $TDotationInitiale += $exec['totalDotInitial'];
                            $TDotationRemanier += $exec['totalDotRemanier'];

                            $TEngs += $realisation;
                            $TOp += $exec['totalOp'];

                        endforeach;
                        endif;
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-center" colspan="2">TOTAL</th>

                            <th class="text-end"><?= number_format($TDotationInitiale, 0, ',', ' '); ?></th>

                            <?php if ($showRemanier): ?>
                            <th class="text-end"><?= number_format($TDotationRemanier, 0, ',', ' '); ?></th>
                            <th class="text-end"><?= number_format($TDotations, 0, ',', ' '); ?></th>
                            <?php endif; ?>

                            <th class="text-end"><?= number_format($TEngs, 0, ',', ' '); ?></th>

                            <th class="text-end">-</th>

                            <th class="text-end">
                                <?= ($exec['typeCompte'] == 'ressource' || $exec['typeCompte'] == 'produit' ) 
                                    ? '-' 
                                    : number_format(max(0, $TDotations - $TEngs), 0, ',', ' ') ?>
                            </th>

                            <?php if ($op == 1): ?>
                            <th class="text-end"><?= number_format($TOp, 0, ',', ' '); ?></th>
                            <th class="text-end"><?= number_format($TEngs - $TOp, 0, ',', ' '); ?></th>
                            <?php endif; ?>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
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
$(document).ready(function() {
    $('#tableComptes').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        responsive: true,
        order: [],

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
</style>