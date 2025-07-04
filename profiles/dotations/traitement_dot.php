<?php include '../../includes/fonctions.php';?>
<?php 
session_start(); // Démarrer la session
//echo $_SESSION['idUser'];

if (isset($_POST['ini_numc'], $_POST['ini_date'], $_POST['ini_volume'])) {
    $idCompte = $_POST['ini_numc'];
    $date = $_POST['ini_date'];
    $volume = $_POST['ini_volume'];
    $type="initiale";

    $resultat = enregistrerDotation($idCompte, $date, $volume, $type);

    if ($resultat === true) {
        header("Location: add_ini_dot.php?success=1");
    } else {
        header("Location: add_ini_dot.php?error=" . urlencode($resultat));
    }
}

if (isset($_POST['rem_numc'], $_POST['rem_date'], $_POST['rem_volume'])) {
    $idCompte = $_POST['rem_numc'];
    $date = $_POST['rem_date'];
    $volume = $_POST['rem_volume'];
    $type="remanier";

    $resultat = enregistrerDotation($idCompte, $date, $volume, $type);

    if ($resultat === true) {
        header("Location: add_rem_dot.php?success=1");
    } else {
        header("Location: add_rem_dot.php?error=" . urlencode($resultat));
    }
}

if (isset($_GET['suppr'])) {
    $suppr = $_GET['suppr'];

    $resultat = supprDotation($suppr);

    if ($resultat === true) {
        header("Location: liste_dotations.php?success=1");
    } else {
        header("Location: liste_dotations.php?error=" . urlencode($resultat));
    }
}

?>