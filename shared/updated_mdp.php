<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}
?>

<?php include '../includes/fonctions.php';?>

<?php
$annee_connexion = $_SESSION['an'];
$min_date = $annee_connexion . "-01-01";
$max_date = date("Y-m-d");
?>

<?php include '../includes/header.php';?>

<main>
    <div class="container">

        <!-- Titre -->
        <div class="text-center mb-4" style="color: #4655a4;">
            <h3>Changement de mot de passe</h3>
            <p style="color:rgb(46, 176, 67);">
                Veuillez saisir votre ancien mot de passe, puis entrer et confirmer le nouveau.
            </p>
        </div>

        <!-- Formulaire -->
        <form action="traitement_password.php" method="post">

            <div class="card shadow-sm mx-auto" style="max-width: 700px; border-top: 4px solid #4655a4; border-bottom: 4px solid #4655a4;">
                <div class="card-body">

                    <!-- Message erreur -->
                    <?php if (!empty($_GET['error'])): ?>
                        <div class="alert alert-danger text-center">
                            <?php echo $_GET['error']; ?> !!
                        </div>
                    <?php endif; ?>

                    <div class="row g-3">

                        <!-- Ancien mot de passe -->
                        <div class="col-md-12 px-4">
                            <label class="form-label"><strong>Ancien mot de passe</strong></label>
                            <input type="password" name="old_password" class="form-control" style="border: 1px solid black;" required>
                        </div>

                        <!-- Nouveau mot de passe -->
                        <div class="col-md-12 px-4">
                            <label class="form-label"><strong>Nouveau mot de passe</strong></label>
                            <input type="password" name="new_password" class="form-control" style="border: 1px solid black;" required>
                        </div>

                        <!-- Confirmation -->
                        <div class="col-md-12 px-4">
                            <label class="form-label"><strong>Confirmer le nouveau mot de passe</strong></label>
                            <input type="password" name="confirm_password" class="form-control" style="border: 1px solid black;" required>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Boutons -->
            <div class="d-flex justify-content-between align-items-center mt-3 mx-auto" style="max-width: 700px;">
                <button type="submit" class="btn btn-success">
                    <strong>Enregistrer</strong>
                </button>

                <a href="javascript:history.back()" class="btn btn-danger">
                    <strong>Annuler</strong>
                </a>
            </div>

        </form>

    </div>
</main>

<?php include '../includes/footer.php'; ?>