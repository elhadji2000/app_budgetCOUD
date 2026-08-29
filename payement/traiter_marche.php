
<?php

session_start();

require_once '../includes/fonctions.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit();
}

global $connexion;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: liste_marches.php");
    exit();
}

$userId = isset($_SESSION['idUser'])
    ? (int) $_SESSION['idUser']
    : null;

$marcheId = (int) ($_POST['marche_id'] ?? 0);
$action = $_POST['action'] ?? '';

if ($marcheId <= 0) {
    $_SESSION['error'] = "Dossier invalide.";
    header("Location: liste_marches.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Récupérer le dossier
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $connexion,
    "SELECT *
     FROM sigm_marches
     WHERE id = ?
     LIMIT 1"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $marcheId
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$marche = mysqli_fetch_assoc($result);

if (!$marche) {

    $_SESSION['error'] =
        "Dossier de marché introuvable.";

    header("Location: liste_marches.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Le dossier doit être en attente
|--------------------------------------------------------------------------
*/

if ($marche['statut'] !== 'en_attente') {

    $_SESSION['error'] =
        "Ce dossier a déjà été traité.";

    header(
        "Location: traitement_marche.php?id="
        . $marcheId
    );

    exit();
}


/*
|--------------------------------------------------------------------------
| VALIDATION
|--------------------------------------------------------------------------
*/

if ($action === 'valider') {

    mysqli_begin_transaction($connexion);

    try {

        /*
        | Mise à jour marché
        */

        $sql = "
            UPDATE sigm_marches
            SET
                statut = 'valide',
                validated_by = ?,
                date_validation = NOW()
            WHERE id = ?
              AND statut = 'en_attente'
        ";

        $stmt = mysqli_prepare(
            $connexion,
            $sql
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ii",
            $userId,
            $marcheId
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(
                "Erreur lors de la validation."
            );
        }

        /*
        | Documents
        */

        $sqlDocs = "
            UPDATE sigm_marche_documents
            SET statut = 'valide'
            WHERE marche_id = ?
              AND statut = 'en_attente'
        ";

        $stmtDocs = mysqli_prepare(
            $connexion,
            $sqlDocs
        );

        mysqli_stmt_bind_param(
            $stmtDocs,
            "i",
            $marcheId
        );

        mysqli_stmt_execute($stmtDocs);

        /*
        | Historique
        */

        $ancienStatut = 'en_attente';
        $nouveauStatut = 'valide';

        $commentaire =
            "Dossier validé.";

        $sqlHist = "
            INSERT INTO sigm_marche_historique
            (
                marche_id,
                ancien_statut,
                nouveau_statut,
                commentaire,
                user_id
            )
            VALUES (?, ?, ?, ?, ?)
        ";

        $stmtHist = mysqli_prepare(
            $connexion,
            $sqlHist
        );

        mysqli_stmt_bind_param(
            $stmtHist,
            "isssi",
            $marcheId,
            $ancienStatut,
            $nouveauStatut,
            $commentaire,
            $userId
        );

        mysqli_stmt_execute($stmtHist);

        mysqli_commit($connexion);

        $_SESSION['success'] =
            "Le dossier "
            . htmlspecialchars($marche['reference'])
            . " a été validé avec succès.";

    } catch (Throwable $e) {

        mysqli_rollback($connexion);

        $_SESSION['error'] =
            "Erreur : "
            . $e->getMessage();
    }

}


/*
|--------------------------------------------------------------------------
| ANNULATION
|--------------------------------------------------------------------------
*/

elseif ($action === 'annuler') {

    $motif = trim(
        $_POST['motif'] ?? ''
    );

    if ($motif === '') {

        $_SESSION['error'] =
            "Le motif d'annulation est obligatoire.";

        header(
            "Location: traitement_marche.php?id="
            . $marcheId
        );

        exit();
    }

    mysqli_begin_transaction($connexion);

    try {

        /*
        | Mise à jour marché
        */

        $sql = "
            UPDATE sigm_marches
            SET
                statut = 'annule',
                motif_annulation = ?,
                cancelled_by = ?,
                date_annulation = NOW()
            WHERE id = ?
              AND statut = 'en_attente'
        ";

        $stmt = mysqli_prepare(
            $connexion,
            $sql
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sii",
            $motif,
            $userId,
            $marcheId
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(
                "Erreur lors de l'annulation."
            );
        }

        /*
        | Documents
        */

        $sqlDocs = "
            UPDATE sigm_marche_documents
            SET statut = 'annule'
            WHERE marche_id = ?
              AND statut = 'en_attente'
        ";

        $stmtDocs = mysqli_prepare(
            $connexion,
            $sqlDocs
        );

        mysqli_stmt_bind_param(
            $stmtDocs,
            "i",
            $marcheId
        );

        mysqli_stmt_execute($stmtDocs);

        /*
        | Historique
        */

        $ancienStatut = 'en_attente';
        $nouveauStatut = 'annule';

        $sqlHist = "
            INSERT INTO sigm_marche_historique
            (
                marche_id,
                ancien_statut,
                nouveau_statut,
                commentaire,
                user_id
            )
            VALUES (?, ?, ?, ?, ?)
        ";

        $stmtHist = mysqli_prepare(
            $connexion,
            $sqlHist
        );

        mysqli_stmt_bind_param(
            $stmtHist,
            "isssi",
            $marcheId,
            $ancienStatut,
            $nouveauStatut,
            $motif,
            $userId
        );

        mysqli_stmt_execute($stmtHist);

        mysqli_commit($connexion);

        $_SESSION['success'] =
            "Le dossier "
            . htmlspecialchars($marche['reference'])
            . " a été annulé.";

    } catch (Throwable $e) {

        mysqli_rollback($connexion);

        $_SESSION['error'] =
            "Erreur : "
            . $e->getMessage();
    }

}

else {

    $_SESSION['error'] =
        "Action non reconnue.";
}


/*
|--------------------------------------------------------------------------
| Retour
|--------------------------------------------------------------------------
*/

header(
    "Location: traitement_marche.php?id="
    . $marcheId
);

exit();
