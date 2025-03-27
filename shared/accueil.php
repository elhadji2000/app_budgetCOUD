<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: /BUDGET/index.php"); // Redirige vers la page de connexion
    exit();
}
?>

<?php include '../includes/header.php';?>
<main>
    <div class='container'>
        <?php include 'menu.php';?>
    </div>

    <div class="text-center container" style="margin-bottom: 50px; margin-top: 70px;">
        <a href="http://localhost/BUDGET/shared/accueil.php">
            <img src="/BUDGET/assets/images/logo-du-coud.jpg" width="600" height="250" alt="Logo-du-coud">
        </a>
    </div>
</main>

<?php include '../includes/footer.php';?>