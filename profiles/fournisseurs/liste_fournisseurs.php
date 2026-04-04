<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit();
}
?>
<?php include '../../includes/fonctions.php';?>

<?php
// Activation / désactivation
if (isset($_GET['toggle_id'])) {
    $id = intval($_GET['toggle_id']);
    $connexion = connexionBD();

    $sql = "SELECT statut FROM users WHERE idUser = $id";
    $result = mysqli_query($connexion, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        $newStatut = ($row['statut'] == 1) ? 0 : 1;
        mysqli_query($connexion, "UPDATE users SET statut = $newStatut WHERE idUser = $id");
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<?php include '../../includes/header.php';?>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<main class="container-fluid mt-3">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-primary fw-bold mb-0">
            <i class="bi bi-people"></i> LISTE DES FOURNISSEURS ENREGISTRÈS
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
        <div class="card-body">

            <table id="tableUsers" class="table table-striped table-hover align-middle">

                <thead class="custom-header">
                    <tr>
                        <th>N°</th>
                        <th>NumF</th>
                        <th>Nom</th>
                        <th>Adresse</th>
                        <th>Contact</th>
                        <th>Nature</th>
                        <th>Action(s)</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
$fournisseurs = getAllFournisseurs();
$n=1;
if (!empty($fournisseurs)):
    foreach ($fournisseurs as $fournisseur):
?>
                    <tr>
                        <td><?= $n; ?></td>
                        <td><?= $fournisseur['numFourn']; ?></td>
                        <td><?= $fournisseur['nom']; ?></td>
                        <td><?= $fournisseur['adresse']; ?></td>
                        <td><?= $fournisseur['contact']; ?></td>
                        <td><?= $fournisseur['nature']; ?></td>
                        <td>
                            <?php if (!isFournisseurUsed($fournisseur['idFourn'])): ?>
                            <a href="supprimer_engagement.php?id=<?= $fournisseur['idFourn'] ?>"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet Fournisseur ?')">Supprimer</a>
                            <?php else: ?>
                            <span style="color: grey; cursor: not-allowed;"
                                title="Fournisseur utilisé, suppression désactivée">Supprimer</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php $n++; ?>
                    <?php endforeach; ?>

                    <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-danger text-start">
                            <i class="bi bi-exclamation-triangle"></i> Aucun utilisateur trouvé
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>

            </table>

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
    $('#tableUsers').DataTable({
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
            info: "Affichage de _START_ à _END_ sur _TOTAL_ utilisateurs",
            paginate: {
                previous: "Précédent",
                next: "Suivant"
            },
            zeroRecords: "Aucun résultat trouvé"
        }
    });
});
</script>

<!-- STYLE -->
<style>
.custom-header {
    background-color: #4655a4;
    color: #fff;
}

#tableUsers th,
#tableUsers td {
    text-align: left !important;
    vertical-align: middle;
    font-size:13px !important;
}
</style>