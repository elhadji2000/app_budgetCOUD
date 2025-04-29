<?php include '../../includes/fonctions.php';?>
<?php 
session_start(); // Démarrer la session

if (isset($_POST['dateEng'], $_POST['service'], $_POST['montant'], $_POST['libelle'], $_POST['bc'], $_POST['idCompte'], $_POST['idFourn'])) {
    $dateEng = $_POST['dateEng'];
    $service = $_POST['service'];
    $montant = $_POST['montant'];
    $libelle = $_POST['libelle'];
    $bc = $_POST['bc'];
    $idCompte = $_POST['idCompte'];
    $idFourn = $_POST['idFourn'];

    $resultat = ajouterEngagement_temp($dateEng, $service, $montant, $libelle, $bc, $idCompte, $idFourn);

    if ($resultat === true) {
        header("Location: add_eng1.php?success=1");
    } else {
        header("Location: add_eng2.php?error=" . urlencode($resultat));
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
        $insert = "INSERT INTO engagements (dateEng, service, montant, libelle, bc, idCompte, idFourn)
                   VALUES ('{$row['dateEng']}', '{$row['service']}', '{$row['montant']}',
                           '{$row['libelle']}', '{$row['bc']}', '{$row['idCompte']}', '{$row['idFourn']}')";
        if (mysqli_query($conn, $insert)) {
            // Supprime de engagements_temp
            mysqli_query($conn, "DELETE FROM engagements_temp WHERE idEng = $idTemp");
            header("Location: liste_engs.php?success=2");
        } else {
            header("Location: liste_engs.php?error=Erreur lors de l'insertion");
        }
    } else {
        header("Location: liste_engs.php?error=Enregistrement introuvable");
    }

    exit();
}

if (isset($_GET['supprTemp'])) {
    $suppr = $_GET['supprTemp'];

    $resultat = supprEngagementTemp($suppr);

    if ($resultat === true) {
        header("Location: liste_engs.php?success=1");
    } else {
        header("Location: liste_engs.php?error=" . urlencode($resultat));
    }
}

if (isset($_GET['suppr'])) {
    $suppr = $_GET['suppr'];

    $resultat = supprEngagement($suppr);

    if ($resultat === true) {
        header("Location: liste_engs.php?success=1");
    } else {
        header("Location: liste_engs.php?error=" . urlencode($resultat));
    }
}
?>


