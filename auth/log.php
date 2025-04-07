<?php 
include('../includes/fonctions.php');
session_start(); // Démarrer la session

$error = "";

if (!empty($_POST['utilisateur']) && !empty($_POST['motdepasse']) && !empty($_POST['annee_budgetaire'])) {
    $username = $_POST['utilisateur'];
    $password = $_POST['motdepasse'];
    $an = $_POST['annee_budgetaire'];

    // Mettre en majuscules et éliminer les espaces éventuels
    $username = trim($username);

    // Vérifier les identifiants via la fonction login()
    $row = login($username, $password);

    if ($row) {
        // Stocker les informations de l'utilisateur dans la session
        $_SESSION['user'] = $row['nom']; // Stocker le nom de l'utilisateur
        $_SESSION['priv'] = $row['priv']; // Stocker le rôle si nécessaire
        $_SESSION['an'] = $an;
        $_SESSION['matricule'] = $row['log'];
        $_SESSION['idUser'] = $row['idUser'];

        // Redirection vers la page du profil après connexion
        header("Location: ../shared/accueil.php");
        exit();
    } else {
        $error = "Identifiants incorrects. Veuillez réessayer.";
        header("Location: ../index.php?error='Identifiants incorrects. Veuillez réessayer.'");
        exit();
    }
}
?>