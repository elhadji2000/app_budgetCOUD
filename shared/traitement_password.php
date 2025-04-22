<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}

include '../includes/fonctions.php';

$userId = $_SESSION['idUser'];
$oldPassword = SHA1($_POST['old_password']);
$newPassword = $_POST['new_password'];
$confirmPassword = $_POST['confirm_password'];

// Exemple : récupération du mot de passe actuel depuis la base
$currentPasswordHash = getPasswordHashByUserId($userId); // à implémenter dans fonctions.php

// Vérifications
if ($oldPassword !== $currentPasswordHash) {
    header("Location: updated_mdp.php?error=Ancien mot de passe incorrect.");
    exit();
}

if ($newPassword !== $confirmPassword) {
    header("Location: updated_mdp.php?error=Les mots de passe ne correspondent pas.");
    exit();
}

if (strlen($newPassword) < 6) {
    header("Location: updated_mdp.php?error=Le mot de passe doit contenir au moins 6 caractères.");
    exit();
}

// Hachage et mise à jour
$newHash = SHA1($newPassword);
updateUserPassword($userId, $newHash); // à implémenter dans fonctions.php

// Redirection
header("Location: accueil.php?success=1");
exit();
