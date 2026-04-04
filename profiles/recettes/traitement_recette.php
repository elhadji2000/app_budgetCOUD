<?php 
include '../../includes/fonctions.php';
session_start();

// ================== AJOUT ==================
if (isset($_POST['dateOr'], $_POST['montant'], $_POST['pieces_annexees'], $_POST['objet_recette'], $_POST['idCompte'], $_POST['idFourn'])) {
    
    $dateOr = $_POST['dateOr'];
    $montant = $_POST['montant'];
    $objet_recette = $_POST['objet_recette'];
    $pieces_annexees = $_POST['pieces_annexees'];
    $idCompte = $_POST['idCompte'];
    $idFourn = $_POST['idFourn'];

    $resultat = ajouter_Ordre_Recette_temp($dateOr, $objet_recette, $montant, $pieces_annexees, $idCompte, $idFourn);

    if ($resultat === true) {
        header("Location: add_rec1.php?success=1");
    } else {
        header("Location: add_rec2.php?error=" . urlencode($resultat));
    }
    exit();
}


// ================== VALIDATION ==================
if (isset($_GET['valider_id'])) {
    $idTemp = intval($_GET['valider_id']);

    $conn = connexionBD();

    // Récupération depuis ordre_recette_temp
    $query = "SELECT * FROM ordre_recette_temp WHERE idOr = $idTemp";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {

        // Insertion dans ordre_recette
        $insert = "INSERT INTO ordre_recette (dateOr, objet_recette, montant, pieces_annexees, idCompte, idFourn, idUser)
                   VALUES ('{$row['dateOr']}', '{$row['objet_recette']}', '{$row['montant']}',
                           '{$row['pieces_annexees']}', '{$row['idCompte']}', '{$row['idFourn']}', '{$row['idUser']}')";

        if (mysqli_query($conn, $insert)) {

            // Suppression dans temp
            mysqli_query($conn, "DELETE FROM ordre_recette_temp WHERE idOr = $idTemp");

            header("Location: liste_rec.php?success=2");
        } else {
            header("Location: liste_rec.php?error=Erreur lors de l'insertion");
        }

    } else {
        header("Location: liste_rec.php?error=Enregistrement introuvable");
    }

    exit();
}


// ================== SUPPRESSION TEMP ==================
if (isset($_GET['supprTemp'])) {
    $suppr = intval($_GET['supprTemp']);

    $resultat = supprimerLigne('ordre_recette_temp', 'idOr', $suppr);

    if ($resultat === true) {
        header("Location: liste_recettes.php?success=1");
    } else {
        header("Location: liste_recettes.php?error=" . urlencode($resultat));
    }
    exit();
}


// ================== SUPPRESSION DEFINITIVE ==================
if (isset($_GET['suppr'])) {
    $suppr = intval($_GET['suppr']);

    $resultat = supprimerLigne('ordre_recette', 'idOr', $suppr);

    if ($resultat === true) {
        header("Location: liste_rec.php?success=1");
    } else {
        header("Location: liste_rec.php?error=" . urlencode($resultat));
    }
    exit();
}
?>