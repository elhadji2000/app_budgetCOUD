<?php include '../../includes/fonctions.php';?>
<?php 
session_start(); // Démarrer la session

if (isset($_POST['dateOp'], $_POST['idEng'], $_POST['numFact'])) {
    $dateOp = $_POST['dateOp'];
    $idEng = $_POST['idEng'];
    $numFact = $_POST['numFact'];
    $typeOp = "paiement";

    $resultat = ajouterOp($dateOp, $idEng, $numFact, $typeOp);

    if ($resultat === true) {
        header("Location: add_paie1.php?success=1");
    } else {
        header("Location: add_paie2.php?error=" . urlencode($resultat));
    }
}

if (isset($_POST['dateOr'], $_POST['idEng'], $_POST['numFact'])) {
    $dateOp = $_POST['dateOp'];
    $idEng = $_POST['idEng'];
    $numFact = $_POST['numFact'];
    $typeOp = "recette";

    $resultat = ajouterOp($dateOp, $idEng, $numFact, $typeOp);

    if ($resultat === true) {
        header("Location: add_paie1.php?success=1");
    } else {
        header("Location: add_paie2.php?error=" . urlencode($resultat));
    }
}

?>