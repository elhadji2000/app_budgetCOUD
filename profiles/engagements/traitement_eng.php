<?php include '../../includes/fonctions.php';?>
<?php 
//session_start(); // Démarrer la session

if (isset($_POST['dateEng'], $_POST['type_eng'], $_POST['montant'], $_POST['objet'], $_POST['idCompte'], $_POST['idFourn'])) {
    $dateEng = $_POST['dateEng'];
    $numc = $_POST['numc'];
    $type_eng = $_POST['type_eng'];
    $montant = $_POST['montant'];
    $objet = $_POST['objet'];
    $idCompte = $_POST['idCompte'];
    $idFourn = $_POST['idFourn'];
    $credit = $_POST['credit'];

    /* if ($montant > $credit) {
    header("Location: ton_formulaire.php?error=Montant supérieur au crédit disponible");
    exit();
    } */

    $resultat = ajouterEngagement_temp($dateEng, $type_eng, $montant, $objet, $idCompte, $idFourn);

    if ($resultat === true) {
        header("Location: add_eng1?success=1");
    } else {
        header("Location: add_eng2?numc=".$numc."&error=" . urlencode($resultat));
    }
}
?>

<?php
if (isset($_GET['valider_id'])) {
    $idTemp = intval($_GET['valider_id']);

    // Connexion
    $conn = connexionBD();

    // Récupère la ligne depuis engagements_temp
    $query = "SELECT * FROM engagements_temp WHERE idEng = $idTemp";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // Insère dans engagements
        $insert = "INSERT INTO engagements (dateEng, type_eng, montant, objet,idCompte, idFourn)
                   VALUES ('{$row['dateEng']}', '{$row['type_eng']}', '{$row['montant']}',
                           '{$row['objet']}','{$row['idCompte']}', '{$row['idFourn']}')";
        if (mysqli_query($conn, $insert)) {
            // Supprime de engagements_temp
            mysqli_query($conn, "DELETE FROM engagements_temp WHERE idEng = $idTemp");
            header("Location: liste_engs?success=2");
        } else {
            header("Location: liste_engs?error=Erreur lors de l'insertion");
        }
    } else {
        header("Location: liste_engs?error=Enregistrement introuvable");
    }

    exit();
}

if (isset($_GET['supprTemp'])) {
    $suppr = $_GET['supprTemp'];

    $resultat = supprEngagementTemp($suppr);

    if ($resultat === true) {
        header("Location: liste_engs?success=1");
    } else {
        header("Location: liste_engs?error=" . urlencode($resultat));
    }
}

if (isset($_GET['suppr'])) {
    $suppr = $_GET['suppr'];

    $resultat = supprEngagement($suppr);

    if ($resultat === true) {
        header("Location: liste_engs?success=1");
    } else {
        header("Location: liste_engs?error=" . urlencode($resultat));
    }
}
?>


