<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}

include '../../includes/fonctions.php';

global $connexion;

if (empty($_POST['operations_selectionnees'])) {
    $_SESSION['erreur'] =
        'Veuillez sélectionner au moins un mandat.';

    header('Location: nouveau.php');
    exit();
}

$operations = explode(',', $_POST['operations_selectionnees']);

$connexion->begin_transaction();

try {
    /*
     * Création du bordereau
     */
    $idUser = $_SESSION['idUser'];

    $stmt = $connexion->prepare('
    INSERT INTO bud_bordereaux (idUser)
    VALUES (?)
');

    $stmt->bind_param('i', $idUser);
    $stmt->execute();

    $idBordereau = $connexion->insert_id;

    /*
     * Génération du numéro
     */
    $numeroBordereau =
        'BORD'
        . date('y')
        . '-'
        . str_pad($idBordereau, 4, '0', STR_PAD_LEFT);

    /*
     * Mise à jour du numéro
     */
    $stmt = $connexion->prepare('
        UPDATE bud_bordereaux
        SET numeroBordereau = ?
        WHERE idBordereau = ?
    ');

    $stmt->bind_param(
        'si',
        $numeroBordereau,
        $idBordereau
    );

    $stmt->execute();

    /*
     * Affectation des engagements
     */
    $stmt = $connexion->prepare('
        UPDATE bud_operations
        SET idBordereau = ?
        WHERE idOp = ?
        AND idBordereau IS NULL
    ');

    foreach ($operations as $idOperation) {
        $idOperation = (int) $idOperation;

        $stmt->bind_param(
            'ii',
            $idBordereau,
            $idOperation
        );

        $stmt->execute();
    }

    $connexion->commit();

    $_SESSION['success'] =
        "Le bordereau {$numeroBordereau} a été créé avec succès.";

    header('Location: nouveau.php?id=' . $idBordereau);
    exit();
} catch (Exception $e) {
    $connexion->rollback();

    $_SESSION['erreur'] =
        'Erreur : ' . $e->getMessage();

    header('Location: nouveau.php');
    exit();
}
