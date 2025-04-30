<?php include '../../includes/fonctions.php';?>
<?php 
session_start(); // Démarrer la session

if (isset($_POST['dateOp'], $_POST['idEng'], $_POST['numFact'])) {
    $dateOp = $_POST['dateOp'];
    $idEng = $_POST['idEng'];
    $numFact = $_POST['numFact'];
    $typeOp = "paiement";

    $resultat = ajouterOp_temp($dateOp, $idEng, $numFact, $typeOp);

    if ($resultat === true) {
        header("Location: add_paie1.php?success=1");
    } else {
        header("Location: add_paie2.php?error=" . urlencode($resultat));
    }
}

if (isset($_POST['dateOr'], $_POST['idEng'], $_POST['numFact'])) {
    $dateOp = $_POST['dateOr'];
    $idEng = $_POST['idEng'];
    $numFact = $_POST['numFact'];
    $typeOp = "recette";

    $resultat = ajouterOp_temp($dateOp, $idEng, $numFact, $typeOp);

    if ($resultat === true) {
        header("Location: add_paie1.php?success=1");
    } else {
        header("Location: add_paie2.php?error=" . urlencode($resultat));
    }
}

if (isset($_GET['valider_id'])) {
    $idTemp = intval($_GET['valider_id']);

    $conn = connexionBD(); // Connexion à la BD

    // Récupération de la ligne temporaire
    $query = "SELECT * FROM operations_temp WHERE idOp = $idTemp";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // Préparation des données
        $dateOp   = mysqli_real_escape_string($conn, $row['dateOp']);
        $idEng    = (int) $row['idEng'];
        $numFact  = mysqli_real_escape_string($conn, $row['numFact']);
        $typeOp   = mysqli_real_escape_string($conn, $row['typeOp']); // devrait être "paiement"

        // Insertion dans la table operations
        $insert = "
            INSERT INTO operations (dateOp, idEng, numFact, typeOp)
            VALUES ('$dateOp', $idEng, '$numFact', '$typeOp')
        ";

        if (mysqli_query($conn, $insert)) {
            // Suppression de la ligne temporaire
            mysqli_query($conn, "DELETE FROM operations_temp WHERE idOp = $idTemp");
            if($typeOp==="recette"){
                header("Location: ../recettes/liste_rec.php?success=2");
                exit();
            }
            header("Location: liste_op.php?success=2");
        } else {
            if($typeOp==="recette"){
                header("Location: ../recettes/liste_rec.php?error=Erreur lors de l'insertion");
                exit();
            }
            header("Location: liste_op.php?error=Erreur lors de l'insertion");
        }
    } else {
        header("Location: liste_op.php?error=Opération introuvable");
    }

    exit();
}

if (isset($_GET['supprTempOp'])) {
    $suppr = $_GET['supprTempOp'];

    $resultat = supprOpTemp($suppr);

    if ($resultat === true) {
        header("Location: liste_op.php?success=1");
    } else {
        header("Location: liste_op.php?error=" . urlencode($resultat));
    }
}
if (isset($_GET['supprOp'])) {
    $suppr = intval($_GET['supprOp']);
    $conn = connexionBD();

    // 1. Récupérer les données de l'opération à supprimer
    $query = "SELECT * FROM operations WHERE idOp = $suppr";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // 2. Sauvegarde dans la table operations_suppr
        $dateOp  = mysqli_real_escape_string($conn, $row['dateOp']);
        $idEng   = (int) $row['idEng'];
        $numFact = mysqli_real_escape_string($conn, $row['numFact']);
        $typeOp  = mysqli_real_escape_string($conn, $row['typeOp']);

        $backup = "
            INSERT INTO operations_suppr (dateOp, idEng, numFact, typeOp)
            VALUES ('$dateOp', $idEng, '$numFact', '$typeOp')
        ";

        if (mysqli_query($conn, $backup)) {
            // 3. Suppression réelle
            $resultat = supprOp($suppr);

            if ($resultat === true) {
                header("Location: liste_op.php?success=1");
                exit();
            } else {
                header("Location: liste_op.php?error=" . urlencode($resultat));
                exit();
            }
        } else {
            header("Location: liste_op.php?error=" . urlencode("Erreur lors de la sauvegarde de l'opération supprimée"));
            exit();
        }
    } else {
        header("Location: liste_op.php?error=Opération introuvable");
        exit();
    }
}


if (isset($_GET['supprTempOr'])) {
    $suppr = $_GET['supprTempOr'];

    $resultat = supprOpTemp($suppr);

    if ($resultat === true) {
        header("Location: ../recettes/liste_rec.php?success=1");
    } else {
        header("Location: ../recettes/liste_rec.php?error=" . urlencode($resultat));
    }
}
/* if (isset($_GET['supprOr'])) {
    $suppr = $_GET['supprOr'];

    $resultat = supprOp($suppr);

    if ($resultat === true) {
        header("Location: ../recettes/liste_rec.php?success=1");
    } else {
        header("Location: ../recettes/liste_rec.php?error=" . urlencode($resultat));
    }
} */
if (isset($_GET['supprOr'])) {
    $suppr = intval($_GET['supprOr']);
    $conn = connexionBD();

    // 1. Récupérer les données de l'opération à supprimer
    $query = "SELECT * FROM operations WHERE idOp = $suppr";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // 2. Sauvegarde dans la table operations_suppr
        $dateOp  = mysqli_real_escape_string($conn, $row['dateOp']);
        $idEng   = (int) $row['idEng'];
        $numFact = mysqli_real_escape_string($conn, $row['numFact']);
        $typeOp  = mysqli_real_escape_string($conn, $row['typeOp']);

        $backup = "
            INSERT INTO operations_suppr (dateOp, idEng, numFact, typeOp)
            VALUES ('$dateOp', $idEng, '$numFact', '$typeOp')
        ";

        if (mysqli_query($conn, $backup)) {
            // 3. Suppression réelle
            $resultat = supprOp($suppr);

            if ($resultat === true) {
                header("Location: ../recettes/liste_rec.php?success=1");
                exit();
            } else {
                header("Location: ../recettes/liste_rec.php?error=" . urlencode($resultat));
                exit();
            }
        } else {
            header("Location: ../recettes/liste_rec.php?error=" . urlencode("Erreur lors de la sauvegarde de l'opération supprimée"));
            exit();
        }
    } else {
        header("Location: ../recettes/liste_rec.php?error=Opération introuvable");
        exit();
    }
}

?>