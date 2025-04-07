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

    $resultat = ajouterEngagement($dateEng, $service, $montant, $libelle, $bc, $idCompte, $idFourn);

    if ($resultat === true) {
        header("Location: add_eng1.php?success=1");
    } else {
        header("Location: add_eng2.php?error=" . urlencode($resultat));
    }
}

?>