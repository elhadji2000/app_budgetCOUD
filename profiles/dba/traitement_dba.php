<?php include '../../includes/fonctions.php';?>
<?php 
session_start(); // Démarrer la session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['nom']) && !empty($_POST['log']) && !empty($_POST['mail']) && !empty($_POST['priv'])) {


        $resultat = ajouterUtilisateur($_POST['nom'], $_POST['log'], $_POST['mail'], $_POST['priv']);
        
        if ($resultat['success']) {
            $messageSuccess = $resultat['message'];
            header("Location: add_user.php?success=1");
            exit();
        } else {
            $messageErreur = $resultat['message'];
            header("Location: add_user.php?error=" . urlencode($messageErreur));
            exit();
        }
    } else {
        $messageErreur = "Veuillez remplir tous les champs.";
        header("Location: add_user.php?error=" . urlencode($messageErreur));
        exit();
    }
}

?>
