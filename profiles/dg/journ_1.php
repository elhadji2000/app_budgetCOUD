<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}
?>

<?php include '../../includes/fonctions.php';?>
<?php include '../../includes/header.php';?>

<?php
$annee_connexion = $_SESSION['an'];
$min_date = $annee_connexion . "-01-01";
$max_date = date("Y-m-d");
?>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<main class="container mt-4">

    <!-- HEADER -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body text-center">

            <h5 class="fw-bold text-primary mb-2">
                <i class="bi bi-calendar-day"></i> SITUATION JOURNALIÈRE
            </h5>

            <small class="text-muted">
                Sélectionnez une date pour consulter les opérations journalières
            </small>

        </div>
    </div>

    <!-- FORMULAIRE -->
    <form action="journ_2.php" method="GET">

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <!-- Message erreur -->
                <?php if (!empty($_GET['error'])) : ?>
                <div class="alert alert-danger text-center">
                    <i class="bi bi-exclamation-triangle"></i>
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
                <?php endif; ?>

                <div class="row justify-content-center">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-calendar-event"></i> Date de l’opération
                        </label>

                        <input type="date" name="dateEng" class="form-control" required min="<?= $min_date ?>"
                            max="<?= $max_date ?>">
                    </div>

                </div>

            </div>

            <!-- ACTIONS -->
            <div class="card-footer d-flex justify-content-between">

                <button type="submit" class="btn btn-success btn-sm">
                    <i class="bi bi-check-circle"></i> Valider
                </button>

                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Annuler
                </a>

            </div>
        </div>

    </form>

</main>

<?php include '../../includes/footer.php';?>