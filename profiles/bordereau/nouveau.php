<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}

include '../../includes/fonctions.php';
include '../../includes/header.php';
// $nums = getEngsAvecPaiement($numCompte);
$operations = getOperationsSansBordereau();

$annee_connexion = $_SESSION['an'] ?? date('Y');
$min_date = $annee_connexion . '-01-01';
$max_date = date('Y-m-d');
?>
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
body {
    background-color: #f4f6f9;
}

.pdf-card {
    background: #fff;
    border-radius: 6px;
    box-shadow: 0 1px 8px rgba(0, 0, 0, 0.08);
    padding: 20px;
    margin-bottom: 20px;
}

.title {
    color: #4655a4;
    font-weight: 600;
}

.small-text {
    font-size: 13px;
    color: #6c757d;
}

.table-primary-custom thead {
    background-color: #4655a4;
    color: white;
}

.select-eng-btn:hover {
    transform: scale(1.05);
    transition: 0.2s;
}

.table-active-custom {
    background-color: #e9edff !important;
}

.form-check-input {
    transform: scale(1.2);
    cursor: pointer;
}

.table-active-custom {
    background-color: #e9edff !important;
}
</style>

<main class="container-fluid">


    <!-- HEADER -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">

            <div>
                <h5 class="fw-bold text-primary mb-1">
                    <i class="bi bi-graph-up-arrow"></i> BORDEREAUX
                </h5>
                <small class="text-muted">Selectionnez les engagement et valider </small>
            </div>

            <div class="text-end">
                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>

        </div>
    </div>

    <div class="row">

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

        <!-- LISTE DROITE -->
        <form action="traitement_bordereau.php" method="POST" id="paymentForm">

            <input type="hidden" name="operations_selectionnees" id="operations_selectionnees">

            <div class="col-lg-12">
                <div class="pdf-card">

                    <h6 class="mb-3 fw-bold text-primary">
                        <i class="bi bi-table"></i> Liste des engagements
                    </h6>


                    <?php if (empty($operations)): ?>
                    <div class="alert alert-info">
                        Aucune opération disponible
                    </div>
                    <?php else: ?>

                    <div class="table-responsive">
                        <table id="tableUsers"
                            class="table table-bordered table-hover table-primary-custom align-middle">

                            <thead>
                                <tr>
                                    <th>N°</th>

                                    <th class="text-center">
                                        #
                                        <input type="checkbox" id="checkAll">
                                    </th>

                                    <th>Compte</th>

                                    <th>Mandat(s)</th>

                                    <th>BE</th>

                                    <th>Libelle</th>

                                    <th>Montant_Op</th>

                                    <th>Bénéficiaire</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                $n = 1;

                                foreach ($operations as $op):
                                    ?>

                                <tr>

                                    <td><?= $n++; ?></td>

                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input select-op"
                                            value="<?= $op['idOp']; ?>">
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($op['numCompte']); ?>
                                    </td>

                                    <td>
                                        <?= 'MD'
                                            . date('y')
                                            . '-'
                                            . str_pad($op['idOp'], 4, '0', STR_PAD_LEFT); ?>
                                    </td>

                                    <td>
                                        <?= formatNumEng($op['idEng']); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($op['objet']); ?>
                                    </td>

                                    <td class="text-end somme fw-bold">
                                        <?= number_format($op['montantOperation'], 0, ',', ' '); ?> F
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($op['nom']); ?>
                                    </td>

                                </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>
                    </div>

                    <?php endif; ?>

                </div>
            </div>

    </div>

    <!-- FORMULAIRE GAUCHE -->
    <div class="col-lg-12">
        <div class="pdf-card">
            <!-- ACTIONS -->
            <div class="d-flex justify-content-between mt-4">
                <button class="btn btn-success btn-sm">
                    <i class="bi bi-check-circle"></i> Valider
                </button>

                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>



        </div>
    </div>
    </form>

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

<script>
const engagementsSelectionnes = new Set();

/* Tout sélectionner */
document.addEventListener('change', function(e) {

    if (e.target.id === 'checkAll') {

        document.querySelectorAll('.select-op').forEach(cb => {

            cb.checked = e.target.checked;

            if (cb.checked) {
                engagementsSelectionnes.add(cb.value);
            } else {
                engagementsSelectionnes.delete(cb.value);
            }
        });
    }

    if (e.target.classList.contains('select-op')) {

        if (e.target.checked) {
            engagementsSelectionnes.add(e.target.value);
        } else {
            engagementsSelectionnes.delete(e.target.value);
        }
    }
});

/* Avant soumission */
document.getElementById('paymentForm').addEventListener('submit', function(e) {

    if (engagementsSelectionnes.size === 0) {

        e.preventDefault();

        alert("Veuillez sélectionner au moins un engagement.");

        return;
    }

    document.getElementById('operations_selectionnees').value =
        Array.from(engagementsSelectionnes).join(',');
});
</script>
<?php include '../../includes/footer.php'; ?>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#tableUsers').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        language: {
            search: "<i class='bi bi-search'></i> Rechercher :",
            lengthMenu: "Afficher _MENU_ lignes",
            info: "Affichage de _START_ à _END_ sur _TOTAL_ engagement(s)",
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
    font-size: 12px !important;
}
</style>