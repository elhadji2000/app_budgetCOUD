<?php 
include '../../includes/fonctions.php';
//session_start();

if (isset($_POST['numFourn'], $_POST['nom'], $_POST['adresse'], $_POST['contact'], $_POST['nature'])) {

    $idFourn = $_POST['idFourn'] ?? null;

    $numFourn = $_POST['numFourn'];
    $nom = $_POST['nom'];
    $adresse = $_POST['adresse'];
    $contact = $_POST['contact'];
    $nature = $_POST['nature'];

    // MODE MODIFICATION
    if (!empty($idFourn)) {

        $resultat = modifierFournisseur($idFourn, $numFourn, $nom, $adresse, $contact, $nature);

        if ($resultat === true) {
            header("Location: add_fourn.php?idFourn=$idFourn&success=1");
        } else {
            header("Location: add_fourn.php?idFourn=$idFourn&error=" . urlencode($resultat));
        }

    } 
    // MODE AJOUT
    else {

        $resultat = ajouterFournisseur($numFourn, $nom, $adresse, $contact, $nature);

        if ($resultat === true) {
            header("Location: add_fourn.php?success=1");
        } else {
            header("Location: add_fourn.php?error=" . urlencode($resultat));
        }
    }
}
?>