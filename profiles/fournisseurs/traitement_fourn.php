<?php include '../../includes/fonctions.php';?>
<?php 
session_start(); // Démarrer la session
//echo $_SESSION['idUser'];

if (isset($_POST['numFourn'], $_POST['nom'], $_POST['adresse'], $_POST['contact'], $_POST['nature'])) {
    $numFourn = $_POST['numFourn'];
    $nom = $_POST['nom'];
    $adresse = $_POST['adresse'];
    $contact = $_POST['contact'];
    $nature = $_POST['nature'];

    $resultat = ajouterFournisseur($numFourn, $nom, $adresse, $contact, $nature);

    if ($resultat === true) {
        header("Location: add_fourn.php?success=1");
    } else {
        header("Location: add_fourn.php?error=" . urlencode($resultat));
    }
}


?>