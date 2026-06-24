<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}

include '../../includes/fonctions.php';

global $connexion;

if (!isset($_GET['suppr']) || empty($_GET['suppr'])) {

    $_SESSION['erreur'] = "Bordereau introuvable.";

    header("Location: consulter.php");
    exit();
}

$idBordereau = (int) $_GET['suppr'];

$connexion->begin_transaction();

try {

    /*
    |--------------------------------------------------------------------------
    | 1. LIBÉRER TOUTES LES OPÉRATIONS DU BORDEREAU
    |--------------------------------------------------------------------------
    */
    $stmt = $connexion->prepare("
        UPDATE bud_operations
        SET idBordereau = NULL
        WHERE idBordereau = ?
    ");

    $stmt->bind_param("i", $idBordereau);
    $stmt->execute();

    /*
    |--------------------------------------------------------------------------
    | 2. SUPPRIMER LE BORDEREAU
    |--------------------------------------------------------------------------
    */
    $stmt = $connexion->prepare("
        DELETE FROM bud_bordereaux
        WHERE idBordereau = ?
    ");

    $stmt->bind_param("i", $idBordereau);
    $stmt->execute();

    /*
    |--------------------------------------------------------------------------
    | 3. VALIDATION
    |--------------------------------------------------------------------------
    */
    $connexion->commit();

    $_SESSION['success'] = "Bordereau supprimé avec succès. Les opérations associées sont redevenues disponibles.";

} catch (Exception $e) {

    $connexion->rollback();

    $_SESSION['erreur'] = "Erreur lors de la suppression du bordereau : " . $e->getMessage();
}

header("Location: consulter.php");
exit();