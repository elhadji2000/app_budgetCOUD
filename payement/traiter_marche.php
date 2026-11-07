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
$action   = $_POST['action'] ?? '';

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
| Vérifier les droits du profil connecté
|--------------------------------------------------------------------------
|
| Correspondance PROFIL → STATUT attendu :
|
| drp       → en_entente
| direction → direction
| controle   → controle
| budget    → budget
|
*/

$profil = strtolower(trim($_SESSION['priv'] ?? ''));

$statutsAutorises = [
    'drp'       => 'en_entente',
    'direction' => 'direction',
    'controle'   => 'controle',
    'budget'    => 'budget'
];

$statutAttendu = $statutsAutorises[$profil] ?? null;

$statutMarche = strtolower(trim($marche['statut']));

/*
|--------------------------------------------------------------------------
| Le profil connecté ne peut pas traiter ce dossier
|--------------------------------------------------------------------------
*/

if ($statutAttendu === null || $statutMarche !== $statutAttendu) {

    $_SESSION['error'] =
        "Vous n'êtes pas autorisé à traiter ce dossier à cette étape.";

    header(
        "Location: traitement_marche.php?id="
        . $marcheId
    );

    exit();
}


/*
|--------------------------------------------------------------------------
| Déterminer le statut suivant selon le profil
|--------------------------------------------------------------------------
*/

$statutsSuivants = [
    'drp'       => 'direction',
    'direction' => 'controle',
    'controle'   => 'budget',
    'budget'    => 'valide'
];

$nouveauStatut = $statutsSuivants[$profil] ?? null;


/*
|--------------------------------------------------------------------------
| VALIDATION
|--------------------------------------------------------------------------
*/

if ($action === 'valider') {

    $ancienStatut = $marche['statut'];

    if (!$nouveauStatut) {

        $_SESSION['error'] =
            "Impossible de déterminer le prochain statut.";

        header(
            "Location: traitement_marche.php?id="
            . $marcheId
        );

        exit();
    }


    mysqli_begin_transaction($connexion);

    try {

        /*
        |--------------------------------------------------------------------------
        | Mise à jour du marché
        |--------------------------------------------------------------------------
        */

        $sql = "
            UPDATE sigm_marches
            SET
                statut = ?,
                validated_by = ?,
                date_validation = NOW()
            WHERE id = ?
              AND statut = ?
        ";

        $stmt = mysqli_prepare(
            $connexion,
            $sql
        );

        mysqli_stmt_bind_param(
            $stmt,
            "siis",
            $nouveauStatut,
            $userId,
            $marcheId,
            $ancienStatut
        );

        if (!mysqli_stmt_execute($stmt)) {

            throw new Exception(
                "Erreur lors de la validation."
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Documents
        |--------------------------------------------------------------------------
        */

        $sqlDocs = "
            UPDATE sigm_marche_documents
            SET statut = ?
            WHERE marche_id = ?
              AND statut = ?
        ";

        $stmtDocs = mysqli_prepare(
            $connexion,
            $sqlDocs
        );

        mysqli_stmt_bind_param(
            $stmtDocs,
            "sis",
            $nouveauStatut,
            $marcheId,
            $ancienStatut
        );

        mysqli_stmt_execute($stmtDocs);


        /*
        |--------------------------------------------------------------------------
        | Historique
        |--------------------------------------------------------------------------
        */

        $commentaire =
            "Dossier validé et transmis à l'étape suivante.";

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


        /*
        |--------------------------------------------------------------------------
        | Valider la transaction
        |--------------------------------------------------------------------------
        */

        mysqli_commit($connexion);

        $_SESSION['success'] =
            "Le dossier "
            . htmlspecialchars($marche['reference'])
            . " a été validé et transmis avec succès.";

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
|
| IMPORTANT :
|
| Annuler ne met PAS le dossier à "rejete".
|
| Il retourne le dossier à :
|
|                  en_entente
|
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


    /*
    |--------------------------------------------------------------------------
    | Ancien statut
    |--------------------------------------------------------------------------
    */

    $ancienStatut = $marche['statut'];

    /*
    |--------------------------------------------------------------------------
    | Nouveau statut après annulation
    |--------------------------------------------------------------------------
    */

    $nouveauStatut = 'rejete';


    mysqli_begin_transaction($connexion);

    try {

        /*
        |--------------------------------------------------------------------------
        | Mise à jour marché
        |--------------------------------------------------------------------------
        */

        $sql = "
            UPDATE sigm_marches
            SET
                statut = ?,
                motif_annulation = ?,
                cancelled_by = ?,
                date_annulation = NOW()
            WHERE id = ?
              AND statut = ?
        ";

        $stmt = mysqli_prepare(
            $connexion,
            $sql
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssiis",
            $nouveauStatut,
            $motif,
            $userId,
            $marcheId,
            $ancienStatut
        );

        if (!mysqli_stmt_execute($stmt)) {

            throw new Exception(
                "Erreur lors de l'annulation."
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Documents
        |--------------------------------------------------------------------------
        */

        $sqlDocs = "
            UPDATE sigm_marche_documents
            SET statut = ?
            WHERE marche_id = ?
              AND statut = ?
        ";

        $stmtDocs = mysqli_prepare(
            $connexion,
            $sqlDocs
        );

        mysqli_stmt_bind_param(
            $stmtDocs,
            "sis",
            $nouveauStatut,
            $marcheId,
            $ancienStatut
        );

        mysqli_stmt_execute($stmtDocs);


        /*
        |--------------------------------------------------------------------------
        | Historique
        |--------------------------------------------------------------------------
        */

        $commentaire =
            "Dossier annulé à l'étape "
            . $ancienStatut
            . " et retourné en entente. Motif : "
            . $motif;

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


        /*
        |--------------------------------------------------------------------------
        | Valider la transaction
        |--------------------------------------------------------------------------
        */

        mysqli_commit($connexion);

        $_SESSION['success'] =
            "Le dossier "
            . htmlspecialchars($marche['reference'])
            . " a été annulé et retourné en entente.";

    } catch (Throwable $e) {

        mysqli_rollback($connexion);

        $_SESSION['error'] =
            "Erreur : "
            . $e->getMessage();
    }

}


/*
|--------------------------------------------------------------------------
| Action inconnue
|--------------------------------------------------------------------------
*/

else {

    $_SESSION['error'] =
        "Action non reconnue.";
}


/*
|--------------------------------------------------------------------------
| Retour
|--------------------------------------------------------------------------
*/

/* header(
    "Location: traitement_marche.php?id="
    . $marcheId
); */
header(
    "Location: liste_all.php"
);

exit();