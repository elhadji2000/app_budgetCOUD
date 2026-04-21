<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../../index.php');
    exit();
}

$typeOp = 'paiement';
$an = $_SESSION['an'];
?>
<?php
include '../../../includes/fonctions.php';

$charges = get_charges_administratif();
$produits = get_produits_administratif();
// Trouver le max pour boucler correctement
$data = get_resultats();

$data_produits = $data['produits'];
$data_charges = $data['charges'];
?>

<?php include '../../../includes/header.php'; ?>
<style>

</style>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<main class="container-fluid mt-3">
    <div class="container-fluid mt-0">
        <div class="container mt-0">

            <ul class="nav nav-tabs  justify-content-center" id="myTabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#" data-target="produit">PRODUITS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-target="charges">CHARGES</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-target="resultat">RESULTAT</a>
                </li>
            </ul>
        </div>

        <div class="mt-3 contenu">
            <div class="tab-content produit">
                <?php

                ?>
                <table id="tableProduits" class="table table-striped table-hover align-middle">

                    <thead class="custom-header text-center">
                        <tr>
                            <th>N° Compte</th>
                            <th>Libellé</th>
                            <th>Prévisions Initiales</th>
                            <th>Remaniement(s)</th>
                            <th>Prévisions Global</th>
                            <th>Réalisations</th>
                            <th>Reste / Écart</th>
                            <th>Taux (%)</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                        foreach ($produits as $p): 
                            $prevision = $p['total_dot'];
                            $remanier = $p['totalDotRemanier'];
                            $initiale = $p['totalDotInitiale'];
                            $realisation = $p['total_paye'];

                            $reste = $prevision - $realisation;
                            $taux = ($prevision > 0) ? ($realisation / $prevision) * 100 : 0;
                        ?>

                        <tr class="table-success">

                            <td><?= htmlspecialchars($p['numCp']) ?></td>

                            <td><?= htmlspecialchars($p['libelle']) ?></td>

                            <td class="text-end fw-semibold">
                                <?= number_format($initiale, 0, ',', ' ') ?> F
                            </td>

                            <td class="text-end fw-semibold">
                                <?= number_format($remanier, 0, ',', ' ') ?> F
                            </td>

                            <td class="text-end fw-semibold">
                                <?= number_format($prevision, 0, ',', ' ') ?> F
                            </td>

                            <td class="text-end text-success fw-semibold">
                                <?= number_format($realisation, 0, ',', ' ') ?> F
                            </td>

                            <td class="text-end <?= $reste < 0 ? 'text-danger' : 'text-primary' ?>">
                                <?= number_format($reste, 0, ',', ' ') ?> F
                            </td>

                            <td class="text-center">
                                <?= number_format($taux, 2, ',', ' ') ?> %
                            </td>
                        </tr>

                        <?php endforeach; ?>
                    </tbody>

                    <?php
                    $total_prevision = array_sum(array_column($produits, 'total_dot'));
                    $total_initiale = array_sum(array_column($produits, 'totalDotInitiale'));
                    $total_remanier = array_sum(array_column($produits, 'totalDotRemanier'));
                    $total_realisation = array_sum(array_column($produits, 'total_paye'));

                    $total_ecart = $total_prevision - $total_realisation;

                    $taux_global = ($total_prevision > 0)
                        ? ($total_realisation / $total_prevision) * 100
                        : 0;
                    ?>

                    <!-- BON ENDROIT -->
                    <tfoot>
                        <tr class="fw-bold table-secondary">
                            <td colspan="2" class="text-end">TOTAL FONCTIONNEMENT(S)</td>

                            <td class="text-end">
                                <?= number_format($total_initiale, 0, ',', ' ') ?> F
                            </td>

                            <td class="text-end">
                                <?= number_format($total_remanier, 0, ',', ' ') ?> F
                            </td>

                            <td class="text-end">
                                <?= number_format($total_prevision, 0, ',', ' ') ?> F
                            </td>

                            <td class="text-end text-success">
                                <?= number_format($total_realisation, 0, ',', ' ') ?> F
                            </td>

                            <td class="text-end <?= $total_ecart < 0 ? 'text-danger' : 'text-primary' ?>">
                                <?= number_format($total_ecart, 0, ',', ' ') ?> F
                            </td>

                            <td class="text-center">
                                <?= number_format($taux_global, 2, ',', ' ') ?> %
                            </td>
                        </tr>
                    </tfoot>

                </table>
            </div>
            <div class="tab-content charges d-none">
                <table id="tableCharges" class="table table-striped table-hover align-middle">

                    <thead class="custom-header text-center">
                        <tr>
                            <th>N° Compte</th>
                            <th>Libellé</th>
                            <th>Prévisions Initiales</th>
                            <th>Remaniement(s)</th>
                            <th>Prévisions Global</th>
                            <th>Engagements</th>
                            <th>Liquidation</th>
                            <th>Dépassement</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        foreach ($charges as $c):
                            $prevision = $c['total_dot'];
                            $remanier = $c['totalDotRemanier'];
                            $initiale = $c['totalDotInitiale'];
                            $engagement = $c['total_eng'];
                            $paye = $c['total_paye'];

                            //  Dépassement logique
                            $depassement = max(0, $engagement - $prevision);

                            ?>
                        <tr class="table-warning">
                            <td><?= htmlspecialchars($c['numCp']) ?></td>

                            <td><?= htmlspecialchars($c['libelle']) ?></td>

                            <td class="text-end fw-semibold">
                                <?= number_format($initiale, 0, ',', ' ') ?> F
                            </td>
                            <td class="text-end fw-semibold">
                                <?= number_format($remanier, 0, ',', ' ') ?> F
                            </td>
                            <td class="text-end fw-semibold">
                                <?= number_format($prevision, 0, ',', ' ') ?> F
                            </td>

                            <td class="text-end text-primary">
                                <?= number_format($engagement, 0, ',', ' ') ?> F
                            </td>

                            <td class="text-end text-success">
                                <?= number_format($paye, 0, ',', ' ') ?> F
                            </td>

                            <td class="text-end <?= $depassement > 0 ? 'text-danger fw-bold' : 'text-muted' ?>">
                                <?= number_format($depassement, 0, ',', ' ') ?> F
                            </td>
                        </tr>

                        <?php endforeach; ?>
                    </tbody>

                    <?php
                    $total_prevision = array_sum(array_column($charges, 'total_dot'));
                    $total_initiale = array_sum(array_column($charges, 'totalDotInitiale'));
                    $total_remanier = array_sum(array_column($charges, 'totalDotRemanier'));
                    $total_engagement = array_sum(array_column($charges, 'total_eng'));
                    $total_paye = array_sum(array_column($charges, 'total_paye'));

                    $total_depassement = max(0, $total_engagement - $total_prevision);
                    ?>

                    <tfoot>
                        <tr class="fw-bold table-secondary">
                            <td colspan="2" class="text-end">TOTAL</td>

                            <td class="text-end">
                                <?= number_format($total_initiale, 0, ',', ' ') ?> F
                            </td>
                            <td class="text-end">
                                <?= number_format($total_remanier, 0, ',', ' ') ?> F
                            </td>
                            <td class="text-end">
                                <?= number_format($total_prevision, 0, ',', ' ') ?> F
                            </td>

                            <td class="text-end text-primary">
                                <?= number_format($total_engagement, 0, ',', ' ') ?> F
                            </td>

                            <td class="text-end text-success">
                                <?= number_format($total_paye, 0, ',', ' ') ?> F
                            </td>

                            <td class="text-end <?= $total_depassement > 0 ? 'text-danger' : '' ?>">
                                <?= number_format($total_depassement, 0, ',', ' ') ?> F
                            </td>
                        </tr>
                    </tfoot>

                </table>
            </div>
            <div class="tab-content resultat d-none">
                <table id="tableResultats" class="table table-striped table-hover align-middle">

                    <thead class="text-center">

                        <tr>
                            <th>Libellé</th>
                            <th>Montant</th>
                            <th>Libellé</th>
                            <th>Montant</th>
                        </tr>
                        <!-- <tr>
                            <th colspan="2">CHARGES</th>
                            <th colspan="2">PRODUITS</th>
                        </tr> -->
                    </thead>

                    <tbody class="table-dark">
                        <?php
                        $nbProduits = count($produits);
                        $nbCharges = count($charges);
                        $max = max($nbProduits, $nbCharges);

                        for ($i = 0; $i < $max; $i++):
                            ?>
                        <tr>
                            <!-- CHARGES -->
                            <td>
                                <?= isset($charges[$i]) ? htmlspecialchars($charges[$i]['libelle']) : '' ?>
                            </td>
                            <td class="text-end text-danger fw-semibold">
                                <?= isset($charges[$i])
                                    ? number_format($charges[$i]['total_dot'], 0, ',', ' ') . ' F'
                                    : '' ?>
                            </td>

                            <!-- PRODUITS -->
                            <td>
                                <?= isset($produits[$i]) ? htmlspecialchars($produits[$i]['libelle']) : '' ?>
                            </td>
                            <td class="text-end text-success fw-semibold">
                                <?= isset($produits[$i])
                                    ? number_format($produits[$i]['total_dot'], 0, ',', ' ') . ' F'
                                    : '' ?>
                            </td>
                        </tr>
                        <?php endfor; ?>
                    </tbody>

                    <?php
                    $total_produits = array_sum(array_column($data_produits, 'total_dot'));
                    $total_charges = array_sum(array_column($data_charges, 'total_dot'));

                    $resultat = $total_produits - $total_charges;
                    ?>
                    <tfoot>
                        <tr class="fw-bold table-secondary">
                            <td class="text-end">TOTAL CHARGES</td>
                            <td class="text-end text-danger">
                                <?= number_format($total_charges, 0, ',', ' ') ?> F
                            </td>
                            <td class="text-end">TOTAL PRODUITS</td>
                            <td class="text-end text-success">
                                <?= number_format($total_produits, 0, ',', ' ') ?> F
                            </td>
                        </tr>
                        <!-- RESULTAT -->
                        <tr class="fw-bold <?= $resultat >= 0 ? 'table-success' : 'table-danger' ?>">
                            <td colspan="2" class="text-end">
                                RESULTAT
                            </td>
                            <td colspan="2" class="text-start">
                                <?= number_format($resultat, 0, ',', ' ') ?> F
                            </td>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
    </div>
    <script>
    const tabs = document.querySelectorAll("#myTabs .nav-link");
    const contents = document.querySelectorAll(".tab-content");

    tabs.forEach(tab => {
        tab.addEventListener("click", function(e) {
            e.preventDefault();

            //  retirer active sur tous les tabs
            tabs.forEach(t => t.classList.remove("active"));

            //  ajouter active sur le tab cliqué
            this.classList.add("active");

            //  cacher tous les contenus
            contents.forEach(c => c.classList.add("d-none"));

            //  afficher le bon contenu
            const target = this.getAttribute("data-target");
            document.querySelector("." + target).classList.remove("d-none");
        });
    });
    tabs.forEach(tab => {
        tab.addEventListener("click", function(e) {
            e.preventDefault();

            tabs.forEach(t => t.classList.remove("active"));
            this.classList.add("active");

            contents.forEach(c => c.classList.add("d-none"));

            const target = this.getAttribute("data-target");
            const activeContent = document.querySelector("." + target);

            activeContent.classList.remove("d-none");

            //  FIX DataTable affichage
            setTimeout(() => {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            }, 200);
        });
    });
    </script>
</main>

<?php include '../../../includes/footer.php'; ?>

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
    $('#tableProduits').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        dom: 'lBfrtip', //  ajout du "l"

        buttons: [{
                extend: 'excel',
                text: 'Exporter Excel',
                title: "PRODUITS ADMINISTRATIFS <?= $_SESSION['an'] ?>",
                footer: true //  IMPORTANT
            },
            {
                extend: 'print',
                text: 'Imprimer',
                title: "PRODUITS ADMINISTRATIFS <?= $_SESSION['an'] ?>",
                footer: true, //  important

                customize: function(win) {

                    //  Ajouter un vrai titre
                    $(win.document.title).css(
                        'font-size', '13px'
                    );

                    //  Style
                    $(win.document.body).css('font-size', '10px');

                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', '10px');

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

$(document).ready(function() {
    $('#tableCharges').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        dom: 'lBfrtip', //  ajout du "l"

        buttons: [{
                extend: 'excel',
                text: 'Exporter Excel',
                title: "CHARGES ADMINISTRATIFS <?= $_SESSION['an'] ?>",
                footer: true //  IMPORTANT
            },
            {
                extend: 'print',
                text: 'Imprimer',
                title: "CHARGES ADMINISTRATIFS <?= $_SESSION['an'] ?>",
                footer: true, //  important

                customize: function(win) {

                    //  Ajouter un vrai titre
                    $(win.document.title).css(
                        'font-size', '13px'
                    );

                    //  Style
                    $(win.document.body).css('font-size', '10px');

                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', '10px');

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

$(document).ready(function() {
    $('#tableResultats').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        responsive: true,
        order: [],
        dom: 'lBfrtip', //  ajout du "l"

        buttons: [{
                extend: 'excel',
                text: 'Exporter Excel',
                title: "CHARGES ADMINISTRATIFS <?= $_SESSION['an'] ?>",
                footer: true //  IMPORTANT
            },
            {
                extend: 'print',
                text: 'Imprimer',
                title: "CHARGES ADMINISTRATIFS <?= $_SESSION['an'] ?>",
                footer: true, //  important

                customize: function(win) {

                    //  Ajouter un vrai titre
                    $(win.document.title).css(
                        'font-size', '13px'
                    );

                    //  Style
                    $(win.document.body).css('font-size', '10px');

                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', '10px');

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
</script>

<!-- STYLE -->
<style>
#tableResultats th,
#tableResultats td {
    font-size: 13px;
}

#tableCharges th,
#tableCharges td {
    font-size: 13px;
}

#tableProduits th,
#tableProduits td {
    font-size: 13px;
}

#tableProduits th,
#tableCharges th,
#tableResultats th {
    font-size: 11px;
}
</style>