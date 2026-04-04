<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}

include '../../includes/fonctions.php';
include '../../includes/header.php';

$numCompte = $_GET['numc'] ?? '';
$compte = getCompteByNum($numCompte);
$nums = getEngsAvecPaiement($numCompte);

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
                    <i class="bi bi-graph-up-arrow"></i> MANDATS DE PAIEMENT
                </h5>
                <small class="text-muted"><strong><?= htmlspecialchars($compte['numCompte'] ?? '') ?> -
                        <?= htmlspecialchars($compte['libelle'] ?? '') ?></small>
            </div>

            <div class="text-end">
                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>

        </div>
    </div>

    <div class="row">

        <!-- FORMULAIRE GAUCHE -->
        <div class="col-lg-5">
            <div class="pdf-card">

                <form action="traitement_paie.php" method="POST" id="paymentForm">

                    <input type="hidden" name="idEng" id="selectedEngagement" required>

                    <!-- ENGAGEMENT -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <strong><i class="bi bi-folder"></i> Engagement sélectionné</strong>
                        </label>
                        <input type="text" id="selectedEngagementText" class="form-control"
                            placeholder="Cliquez sur un engagement à droite" readonly>
                    </div>

                    <!-- DATE -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <strong><i class="bi bi-calendar"></i> Date OP</strong>
                        </label>
                        <input type="date" name="dateOp" class="form-control" required min="<?= $min_date ?>"
                            max="<?= $max_date ?>">
                    </div>

                    <!-- PIECE -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <strong><i class="bi bi-file-earmark-text"></i> Pièce justificative</strong>
                        </label>
                        <input type="text" name="numFact" class="form-control" required>
                    </div>
                    <!-- PIECE -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <strong><i class="bi bi-file-earmark-text"></i>Montant</strong>
                        </label>
                        <input type="number" name="montant" class="form-control" required>
                    </div>

                    <!-- ACTIONS -->
                    <div class="d-flex justify-content-between mt-4">
                        <button class="btn btn-success btn-sm">
                            <i class="bi bi-check-circle"></i> Valider
                        </button>
                        <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                    </div>

                </form>

            </div>
        </div>

        <!-- LISTE DROITE -->
        <div class="col-lg-7">
            <div class="pdf-card">

                <h6 class="mb-3 fw-bold text-primary">
                    <i class="bi bi-table"></i> Liste des engagements
                </h6>

                <?php if (empty($nums)): ?>
                <div class="alert alert-info">Aucun engagement disponible</div>
                <?php else: ?>
                <div class="table-responsive">
                    <table id="tableUsers" class="table table-bordered table-hover table-primary-custom align-middle">

                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-start">N°</th>
                                <th class="text-start">Service</th>
                                <th class="text-end">Montant</th>
                                <th class="text-end">N_Paie</th>
                                <th class="text-end">M_Paiement</th>
                                <th class="text-end">Reste</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($nums as $num):
                                $montant = $num['montant'] ?? 0;
                                $nbPaiements = $num['nb_paiements'] ?? 0;
                                $totalPaye = $num['total_paye'] ?? 0;
                                $reste = $montant - $totalPaye;

                                ?>
                            <tr>
                                <!-- CHECK -->
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input select-eng"
                                        data-id="<?= htmlspecialchars($num['idEng']) ?>"
                                        data-label="<?= htmlspecialchars(formatNumEng($num['idEng'])) ?>"
                                        data-reste="<?= $reste ?>">
                                </td>

                                <!-- NUM -->
                                <td class="text-start fw-semibold">
                                    <?= formatNumEng($num['idEng']) ?>
                                </td>

                                <!-- SERVICE -->
                                <td class="text-start">
                                    <?= htmlspecialchars($num['type_eng'] ?? '-') ?>
                                </td>

                                <!-- MONTANT -->
                                <td class="text-end">
                                    <?= number_format($montant, 0, ',', ' ') ?> F
                                </td>

                                <!-- NOMBRE DE PAIEMENTS -->
                                <td class="text-end">
                                    <?= $nbPaiements ?>
                                </td>

                                <!-- TOTAL PAYÉ -->
                                <td class="text-end text-success fw-bold">
                                    <?= number_format($totalPaye, 0, ',', ' ') ?> F
                                </td>

                                <!-- RESTE -->
                                <td class="text-end fw-bold <?= $reste > 0 ? 'text-danger' : 'text-success' ?>">
                                    <?= number_format($reste, 0, ',', ' ') ?> F
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

</main>

<script>
let resteSelectionne = 0;
const montantInput = document.querySelector('input[name="montant"]');
const checkboxes = document.querySelectorAll('.select-eng');

checkboxes.forEach(cb => {
    cb.addEventListener('change', function() {

        if (this.checked) {

            checkboxes.forEach(other => {
                if (other !== this) {
                    other.checked = false;
                    other.closest('tr').classList.remove('table-active-custom');
                }
            });

            let id = this.dataset.id;
            let label = this.dataset.label;
            resteSelectionne = parseFloat(this.dataset.reste);

            document.getElementById('selectedEngagement').value = id;
            document.getElementById('selectedEngagementText').value = "Engagement N° " + label;

            document.querySelectorAll('tr').forEach(tr => tr.classList.remove('table-active-custom'));
            this.closest('tr').classList.add('table-active-custom');

        } else {
            document.getElementById('selectedEngagement').value = '';
            document.getElementById('selectedEngagementText').value = '';
            resteSelectionne = 0;
            this.closest('tr').classList.remove('table-active-custom');
        }
    });
});

document.getElementById('paymentForm').addEventListener('submit', function(e) {

    const montant = parseFloat(montantInput.value);

    if (!document.getElementById('selectedEngagement').value) {
        e.preventDefault();
        alert("Veuillez sélectionner un engagement");
        return;
    }

    if (montant > resteSelectionne) {
        e.preventDefault();
        alert("Montant supérieur au reste à payer !");
        montantInput.focus();
        return;
    }

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