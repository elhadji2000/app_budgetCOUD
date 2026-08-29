<?php
include '../../includes/fonctions.php';

if (isset($_POST['numFourn'], $_POST['nom'], $_POST['adresse'], $_POST['contact'], $_POST['nature'])) {

    $idFourn = $_POST['idFourn'] ?? null;

    $numFourn = $_POST['numFourn'];
    $nom = $_POST['nom'];
    $adresse = $_POST['adresse'];
    $contact = $_POST['contact'];
    $nature = $_POST['nature'];

    // Nouveaux champs
    $ninea = $_POST['ninea'] ?? '';
    $rccm = $_POST['rccm'] ?? '';
    $email = $_POST['email'] ?? '';
    $observations = $_POST['observations'] ?? '';

    // MODE MODIFICATION
    if (!empty($idFourn)) {

        $resultat = modifierFournisseur(
            $idFourn,
            $numFourn,
            $nom,
            $adresse,
            $contact,
            $nature,
            $ninea,
            $rccm,
            $email,
            $observations
        );

        if ($resultat === true) {
            header("Location: add_fourn.php?idFourn=$idFourn&success=1");
        } else {
            header("Location: add_fourn.php?idFourn=$idFourn&error=" . urlencode($resultat));
        }

    } 
    // MODE AJOUT
    else {

        $resultat = ajouterFournisseur(
            $numFourn,
            $nom,
            $adresse,
            $contact,
            $nature,
            $ninea,
            $rccm,
            $email,
            $observations
        );

        if ($resultat === true) {
            header("Location: add_fourn.php?success=1");
        } else {
            header("Location: add_fourn.php?error=" . urlencode($resultat));
        }
    }
}
?>