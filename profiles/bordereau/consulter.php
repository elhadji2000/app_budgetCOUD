<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}
?>
<?php include '../../includes/fonctions.php'; ?>
<?php include '../../includes/header.php'; ?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<main class="container-fluid mt-3">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 text-primary fw-bold">
            <i class="bi bi-folder2-open"></i> LISTE DES BORDEREAUX
        </h5>
        <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <!-- Tableau -->
    <div class="card shadow-sm border-0">
        <?php if (!empty($_SESSION['success'])): ?>
        <script>
        alert("<?= $_SESSION['success']; ?>");
        </script>
        <?php unset($_SESSION['success']); ?>
        <?php endif; ?>


        <?php if (!empty($_SESSION['erreur'])): ?>
        <script>
        alert("<?= $_SESSION['erreur']; ?>");
        </script>
        <?php unset($_SESSION['erreur']); ?>
        <?php endif; ?>
        <div class="card-body">

            <table id="tableComptes" class="table table-striped table-hover align-middle">
                <thead class="text-white" style="background-color: #4655a4;">
                    <tr>
                        <th>N°</th>
                        <th>Bordereau</th>
                        <th>Date</th>
                        <th>Opération(s)</th>
                        <th>Engagement(s)</th>
                        <th>Nb Op.</th>
                        <th>Total</th>
                        <th>Action(s)</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    $n = 1;
                    $bordereaux = getBordereauxOperations();

                    foreach ($bordereaux as $bord):
                        ?>

                    <tr>

                        <td><?= $n++; ?></td>

                        <td>
                            <span class="badge bg-primary fs-6">
                                <?= htmlspecialchars($bord['numeroBordereau']); ?>
                            </span>
                        </td>

                        <td>
                            <?= date('d/m/Y', strtotime($bord['dateSys'])); ?>
                        </td>

                        <td>
                            <?= $bord['operations']; ?>
                        </td>

                        <td>
                            <?= $bord['engagements']; ?>
                        </td>

                        <td class="text-center">
                            <span class="badge bg-secondary">
                                <?= $bord['nbOperations']; ?>
                            </span>
                        </td>

                        <td class="somme fw-bold">
                            <?= number_format($bord['total'], 0, ',', ' '); ?> F
                        </td>

                        <td>

                            <a target="_blank" href="vue_pdf_bordereau.php?id=<?= $bord['idBordereau']; ?>"
                                class="btn btn-sm btn-warning">
                                <i class="bi bi-file-earmark-pdf"></i>
                                PDF
                            </a>

                            <?php if ($_SESSION['priv'] === 'admin' || $_SESSION['priv'] === 'dg'): ?>

                            <a href="traitement_bordereau_delete.php?suppr=<?= $bord['idBordereau']; ?>"
                                class="btn btn-sm btn-danger" onclick="return confirm(
                            'Supprimer ce bordereau ? Les opérations redeviendront disponibles.'
                        )">

                                <i class="bi bi-trash"></i>
                                Supprimer

                            </a>

                            <?php endif; ?>

                        </td>

                    </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>


        <script>
        setTimeout(() => {
            let alerts = document.querySelectorAll('.alert');
            alerts.forEach(a => {
                a.classList.remove('show');
                a.classList.add('hide');
            });
        }, 3000);
        </script>
</main>
<?php include '../../includes/footer.php'; ?>

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
    font-size: 13px;
}

#tableComptes .somme {
    text-align: left !important;
}
</style>