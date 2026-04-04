<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}
?>

<?php 
include '../../includes/fonctions.php';

$date1 = $_GET['date1'] ?? '';
$date2 = $_GET['date2'] ?? '';

$nums = getCompteEngsByDate2($date1, $date2);
?>

<?php include '../../includes/header.php';?>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<main class="container mt-4">

    <!-- HEADER -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body text-center">

            <h5 class="fw-bold text-primary mb-2">
                <i class="bi bi-journal-text"></i> SÉLECTION DU COMPTE
            </h5>

            <small class="text-muted">
                Période sélectionnée :
                <strong><?= htmlspecialchars($date1) ?></strong> →
                <strong><?= htmlspecialchars($date2) ?></strong>
            </small>

        </div>
    </div>

    <!-- FORMULAIRE -->
    <form action="borner_3.php" method="GET">

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <!-- Message erreur -->
                <?php if (!empty($_GET['error'])) : ?>
                    <div class="alert alert-danger text-center">
                        <i class="bi bi-exclamation-triangle"></i>
                        <?= htmlspecialchars($_GET['error']) ?>
                    </div>
                <?php endif; ?>

                <!-- Champs cachés -->
                <input type="hidden" name="date1" value="<?= htmlspecialchars($date1) ?>">
                <input type="hidden" name="date2" value="<?= htmlspecialchars($date2) ?>">

                <div class="row justify-content-center">

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            <i class="bi bi-folder"></i> Numéro du compte
                        </label>

                        <select name="numCompte" class="form-select" required>
                            <option value="">-- Sélectionner un compte --</option>

                            <?php if (!empty($nums)) : ?>
                                <?php foreach ($nums as $num) : ?>
                                    <option value="<?= htmlspecialchars($num['numCompte']) ?>">
                                        <?= htmlspecialchars($num['numCompte']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <option disabled>Aucun compte disponible</option>
                            <?php endif; ?>

                        </select>

                    </div>

                </div>

            </div>

            <!-- ACTIONS -->
            <div class="card-footer d-flex justify-content-between">

                <button type="submit" class="btn btn-success btn-sm">
                    <i class="bi bi-check-circle"></i> Valider
                </button>

                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>

            </div>
        </div>

    </form>

</main>

<?php include '../../includes/footer.php';?>