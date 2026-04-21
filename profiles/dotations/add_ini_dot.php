<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}

include '../../includes/fonctions.php';

$annee_connexion = $_SESSION['an'] ?? date("Y");
$min_date = $annee_connexion . "-01-01";
$max_date = date("Y-m-d");
$nums = getNumCompteSansInitiale();

include '../../includes/header.php';
?>

<main>
    <div class="container py-4">

        <!-- HEADER -->
        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary">DOTATION INITIALE</h3>
            <p class="text-muted">Gestion des dotations initiales & import Excel</p>
        </div>

        <div class="row g-4">

            <!-- 🟢 CARD FORMULAIRE -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <strong>Saisie manuelle</strong>
                    </div>

                    <div class="card-body">

                        <!-- Messages -->
                        <?php if (!empty($_GET['error'])): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($_GET['error']); ?>
                        </div>
                        <?php endif; ?>

                        <form action="traitement_dot.php" method="POST" class="needs-validation" novalidate>

                            <div class="mb-3">
                                <label class="fw-semibold">Compte *</label>
                                <select name="ini_numc" class="form-select" required>
                                    <option value="">-- Sélectionner --</option>
                                    <?php foreach ($nums as $num): ?>
                                    <option value="<?= $num["idCompte"]; ?>">
                                        <?= $num["numCompte"]; ?> - <?= $num["libelle"]; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="fw-semibold">Date *</label>
                                <input type="date" name="ini_date" class="form-control" required min="<?= $min_date ?>"
                                    max="<?= $max_date ?>">
                            </div>

                            <div class="mb-3">
                                <label class="fw-semibold">Montant *</label>
                                <input type="number" name="ini_volume" class="form-control" required min="0">
                            </div>

                            <div class="d-flex gap-2">
                                <button class="btn btn-success w-100">
                                    Enregistrer
                                </button>
                                <a href="javascript:history.back()" class="btn btn-outline-danger w-100">
                                    Annuler
                                </a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <!-- 🔵 CARD IMPORT EXCEL -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-dark text-white rounded-top-4">
                        <strong>Import Excel</strong>
                    </div>

                    <div class="card-body">

                        <!-- Résultat import -->
                        <?php if (isset($_SESSION['import_success'])): ?>
                        <div class="alert alert-success">
                            ✅ <?= $_SESSION['import_success'] ?> ligne(s) importée(s)
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($_SESSION['import_errors'])): ?>
                        <div class="alert alert-warning">
                            ⚠️ Certaines lignes ignorées :
                            <ul class="mb-0">
                                <?php foreach ($_SESSION['import_errors'] as $err): ?>
                                <li><?= htmlspecialchars($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <?php
                        unset($_SESSION['import_success']);
                        unset($_SESSION['import_errors']);
                        ?>

                        <!-- Form import -->
                        <form action="import_excel.php" method="POST" enctype="multipart/form-data">

                            <div class="mb-3">
                                <label class="fw-semibold">Fichier Excel</label>
                                <input type="file" name="excel_file" class="form-control" accept=".xls,.xlsx" required>
                            </div>

                            <button class="btn btn-primary w-100">
                                Importer les données
                            </button>
                        </form>

                        <!-- Aide -->
                        <div class="mt-3 small text-muted">
                            Format attendu :
                            <ul class="mb-0">
                                <li>Colonne A : Numéro compte</li>
                                <li>Colonne B : Date</li>
                                <li>Colonne C : Montant</li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<!-- Modal succès -->
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-success">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Succès</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                La dotation a été enregistrée avec succès !
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-success" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    new bootstrap.Modal(document.getElementById('successModal')).show();
});

// Validation Bootstrap
(() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', e => {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
})();
</script>
<?php endif; ?>

<?php include '../../includes/footer.php'; ?>