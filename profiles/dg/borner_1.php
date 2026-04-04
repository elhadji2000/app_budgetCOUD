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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
    .required::after {
        content: " *";
        color: red;
        font-weight: bold;
    }

    .custom-card {
        border: 1px solid #dee2e6;
        border-radius: 10px;
    }

    .custom-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        border-radius: 10px 10px 0 0;
    }
</style>

<main class="container mt-4">

    <div class="card custom-card">

        <!-- HEADER -->
        <div class="card-header custom-header text-center">
            <h5 class="fw-bold mb-1 text-primary">
                <i class="bi bi-calendar-range"></i> FILTRAGE PAR PÉRIODE
            </h5>
            <small class="text-muted">
                Veuillez sélectionner une période
            </small>
        </div>

        <!-- FORM -->
        <form action="borner_2.php" method="GET">

            <div class="card-body">

                <!-- ERREUR -->
                <?php if (!empty($_GET['error'])) : ?>
                    <div class="alert alert-danger text-center">
                        <i class="bi bi-exclamation-triangle"></i>
                        <?= htmlspecialchars($_GET['error']) ?>
                    </div>
                <?php endif; ?>

                <div class="row">

                    <!-- DATE DEBUT -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold required">
                            <i class="bi bi-calendar-event"></i> Date début
                        </label>
                        <input 
                            type="date" 
                            name="date1" 
                            class="form-control"
                            required
                            min="<?= $min_date ?>" 
                            max="<?= $max_date ?>"
                        >
                    </div>

                    <!-- DATE FIN -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold required">
                            <i class="bi bi-calendar-check"></i> Date fin
                        </label>
                        <input 
                            type="date" 
                            name="date2" 
                            class="form-control"
                            required
                            min="<?= $min_date ?>" 
                            max="<?= $max_date ?>"
                        >
                    </div>

                </div>

                <!-- NOTE -->
                <p class="text-danger fst-italic text-center mb-0">
                    NB : La période doit être comprise dans l’année en cours.
                </p>

            </div>

            <!-- FOOTER -->
            <div class="card-footer d-flex justify-content-between">

                <button type="submit" class="btn btn-success btn-sm">
                    <i class="bi bi-check-circle"></i> Valider
                </button>

                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Annuler
                </a>

            </div>

        </form>

    </div>

</main>

<?php include '../../includes/footer.php';?>