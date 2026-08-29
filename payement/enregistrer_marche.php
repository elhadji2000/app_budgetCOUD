<?php

session_start();

require_once '../includes/fonctions.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit();
}

global $connexion;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: add_drp.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Sécurité
|--------------------------------------------------------------------------
*/

$userId = isset($_SESSION['idUser'])
    ? (int) $_SESSION['idUser']
    : null;

$annee = date('Y');

/*
|--------------------------------------------------------------------------
| Récupération des données
|--------------------------------------------------------------------------
*/

$idFourn = isset($_POST['idFourn'])
    ? (int) $_POST['idFourn']
    : 0;

$montant = isset($_POST['montant'])
    ? (float) $_POST['montant']
    : 0;

$typeMarche = trim($_POST['type'] ?? '');
$objet = trim($_POST['objet'] ?? '');
$reference = trim($_POST['reference'] ?? '');

$typesDocuments = $_POST['types'] ?? [];

/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

$erreurs = [];

if ($idFourn <= 0) {
    $erreurs[] = "Veuillez sélectionner un fournisseur.";
}

if ($montant <= 0) {
    $erreurs[] = "Le montant du marché est invalide.";
}

if ($typeMarche === '') {
    $erreurs[] = "Le type de marché est obligatoire.";
}

if ($objet === '') {
    $erreurs[] = "L'objet du marché est obligatoire.";
}

if ($reference === '') {
    $erreurs[] = "La référence du marché est obligatoire.";
}

if (!isset($_FILES['files']) || empty($_FILES['files']['name'][0])) {
    $erreurs[] = "Veuillez joindre au moins deux documents.";
}

if (!empty($erreurs)) {
    $_SESSION['error'] = implode('<br>', $erreurs);
    header("Location: add_drp.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Vérifier la référence
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $connexion,
    "SELECT id
     FROM sigm_marches
     WHERE reference = ?
     LIMIT 1"
);

mysqli_stmt_bind_param(
    $stmt,
    "s",
    $reference
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $_SESSION['error'] = "Cette référence de marché existe déjà.";
    header("Location: add_drp.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Vérifier le fournisseur
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $connexion,
    "SELECT idFourn, nom
     FROM bud_fournisseur
     WHERE idFourn = ?
     LIMIT 1"
);

mysqli_stmt_bind_param($stmt, "i", $idFourn);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    $_SESSION['error'] = "Le fournisseur sélectionné n'existe pas.";
    header("Location: add_drp.php");
    exit();
}

$fournisseur = mysqli_fetch_assoc($result);

/*
|--------------------------------------------------------------------------
| Vérifier nombre fichiers
|--------------------------------------------------------------------------
*/

$files = $_FILES['files'];

$nombreFichiers = count($files['name']);

if ($nombreFichiers < 2) {
    $_SESSION['error'] = "Veuillez joindre au minimum deux documents.";
    header("Location: add_drp.php");
    exit();
}

if ($nombreFichiers > 10) {
    $_SESSION['error'] = "Vous ne pouvez pas joindre plus de 10 documents.";
    header("Location: add_drp.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Dossier upload
|--------------------------------------------------------------------------
*/

$uploadDir = '../uploads/marches/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0775, true);
}

/*
|--------------------------------------------------------------------------
| Transaction
|--------------------------------------------------------------------------
*/

mysqli_begin_transaction($connexion);

try {

    /*
    |--------------------------------------------------------------------------
    | Création du marché avec le fournisseur
    |--------------------------------------------------------------------------
    */

    $sql = "INSERT INTO sigm_marches
            (
                reference,
                annee,
                montant,
                type_marche,
                objet,
                id_fournisseur,
                statut,
                created_by
            )
            VALUES
            (?, ?, ?, ?, ?, ?, 'en_attente', ?)";

    $stmt = mysqli_prepare($connexion, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sidssii",
        $reference,
        $annee,
        $montant,
        $typeMarche,
        $objet,
        $idFourn,
        $userId
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception(
            "Impossible d'enregistrer le marché : " . mysqli_error($connexion)
        );
    }

    $marcheId = mysqli_insert_id($connexion);

    /*
    |--------------------------------------------------------------------------
    | Historique initial
    |--------------------------------------------------------------------------
    */

    $ancienStatut = null;
    $nouveauStatut = 'en_attente';

    $sqlHistorique = "
        INSERT INTO sigm_marche_historique
        (
            marche_id,
            ancien_statut,
            nouveau_statut,
            commentaire,
            user_id
        )
        VALUES (?, NULL, ?, ?, ?)
    ";

    $commentaire = "Création du dossier de marché pour le fournisseur : " . $fournisseur['nom'];

    $stmtHist = mysqli_prepare(
        $connexion,
        $sqlHistorique
    );

    mysqli_stmt_bind_param(
        $stmtHist,
        "issi",
        $marcheId,
        $nouveauStatut,
        $commentaire,
        $userId
    );

    mysqli_stmt_execute($stmtHist);

    /*
    |--------------------------------------------------------------------------
    | Enregistrement des documents
    |--------------------------------------------------------------------------
    */

    $extensionsAutorisees = [
        'pdf',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'jpg',
        'jpeg',
        'png'
    ];

    for ($i = 0; $i < $nombreFichiers; $i++) {

        if ($files['error'][$i] !== UPLOAD_ERR_OK) {
            throw new Exception(
                "Erreur lors de l'envoi du fichier : "
                . $files['name'][$i]
            );
        }

        $nomOriginal = $files['name'][$i];

        $extension = strtolower(
            pathinfo($nomOriginal, PATHINFO_EXTENSION)
        );

        if (!in_array(
            $extension,
            $extensionsAutorisees,
            true
        )) {
            throw new Exception(
                "Extension non autorisée : "
                . $nomOriginal
            );
        }

        if ($files['size'][$i] > 10 * 1024 * 1024) {
            throw new Exception(
                "Le fichier "
                . $nomOriginal
                . " dépasse 10 Mo."
            );
        }

        $typeDocument = trim(
            $typesDocuments[$i] ?? ''
        );

        if ($typeDocument === '') {
            throw new Exception(
                "Le type du document "
                . $nomOriginal
                . " est obligatoire."
            );
        }

        /*
        | Nom sécurisé
        */

        $nomFichier =
            'MARCHE_' .
            $marcheId .
            '_' .
            bin2hex(random_bytes(8)) .
            '.' .
            $extension;

        $destination =
            $uploadDir . $nomFichier;

        if (!move_uploaded_file(
            $files['tmp_name'][$i],
            $destination
        )) {
            throw new Exception(
                "Impossible d'enregistrer le fichier "
                . $nomOriginal
            );
        }

        $chemin = 'uploads/marches/' . $nomFichier;

        /*
        |--------------------------------------------------------------------------
        | Insertion document
        |--------------------------------------------------------------------------
        */

        $sqlDocument = "
            INSERT INTO sigm_marche_documents
            (
                marche_id,
                nom_original,
                nom_fichier,
                chemin_fichier,
                type_document,
                extension,
                taille,
                statut,
                uploaded_by
            )
            VALUES
            (?, ?, ?, ?, ?, ?, ?, 'en_attente', ?)
        ";

        $stmtDoc = mysqli_prepare(
            $connexion,
            $sqlDocument
        );

        $taille = (int) $files['size'][$i];

        mysqli_stmt_bind_param(
            $stmtDoc,
            "isssssis",
            $marcheId,
            $nomOriginal,
            $nomFichier,
            $chemin,
            $typeDocument,
            $extension,
            $taille,
            $userId
        );

        if (!mysqli_stmt_execute($stmtDoc)) {
            throw new Exception(
                "Erreur lors de l'enregistrement du document."
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Validation transaction
    |--------------------------------------------------------------------------
    */

    mysqli_commit($connexion);

    $_SESSION['success'] =
        "Le dossier de marché "
        . htmlspecialchars($reference)
        . " pour le fournisseur "
        . htmlspecialchars($fournisseur['nom'])
        . " a été enregistré avec succès. "
        . "Il est maintenant en attente de validation.";

    header(
        "Location: traitement_marche.php?id="
        . $marcheId
    );

    exit();

} catch (Throwable $e) {

    mysqli_rollback($connexion);

    $_SESSION['error'] =
        "Erreur lors de l'enregistrement : "
        . $e->getMessage();

    header("Location: add_drp.php");

    exit();
}
?>