<?php
session_start();

require_once '../includes/fonctions.php';

global $connexion;

// =====================================================
// VERIFICATION UTILISATEUR
// =====================================================
if (
    !isset($_SESSION['user']) ||
    $_SESSION['user'] !== 'direction'
) {
    exit('Accès non autorisé.');
}

$userId = (int) ($_SESSION['id'] ?? 0);

// =====================================================
// TRAITEMENT : SUPPRESSION D'UN DOCUMENT
// =====================================================
if (isset($_POST['supprimer_document'])) {

    $documentId = (int) ($_POST['id_document'] ?? 0);
    $marcheId = (int) ($_POST['marche_id'] ?? 0);

    if ($documentId <= 0 || $marcheId <= 0) {
        exit('Document ou marché invalide.');
    }

    // Récupération du document
    $sql = "
        SELECT id, marche_id, chemin_fichier, type_document
        FROM sigm_marche_documents
        WHERE id = ? AND marche_id = ?
    ";

    $stmt = mysqli_prepare($connexion, $sql);

    mysqli_stmt_bind_param($stmt, 'ii', $documentId, $marcheId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $document = mysqli_fetch_assoc($result);

    if (!$document) {
        exit('Document introuvable.');
    }

    // Vérifier le type du document
    if (
        strtolower(trim($document['type_document'])) !==
        "autorisation d'engagement"
    ) {
        exit('Vous ne pouvez supprimer que les autorisations d’engagement.');
    }

    // Suppression de l'enregistrement en base
    $sqlDelete = "
        DELETE FROM sigm_marche_documents
        WHERE id = ? AND marche_id = ?
    ";

    $stmtDelete = mysqli_prepare($connexion, $sqlDelete);

    mysqli_stmt_bind_param($stmtDelete, 'ii', $documentId, $marcheId);

    if (!mysqli_stmt_execute($stmtDelete)) {
        exit("Erreur lors de la suppression : " . mysqli_stmt_error($stmtDelete));
    }

    // Suppression du fichier physique
    $nomFichier = basename($document['chemin_fichier']);
    $dossierUpload = realpath('../uploads/marches/');

    if ($dossierUpload !== false) {

        $cheminComplet = $dossierUpload . DIRECTORY_SEPARATOR . $nomFichier;

        if (is_file($cheminComplet)) {
            unlink($cheminComplet);
        }
    }

    // Retour au détail du marché
    header('Location: traitement_marche?id=' . $marcheId . '&delete=success');
    exit();
}


// =====================================================
// TRAITEMENT : AJOUT D'UNE AUTORISATION D'ENGAGEMENT
// =====================================================

// Récupération des données
$marcheId = (int) ($_POST['marche_id'] ?? 0);

if ($marcheId <= 0) {
    exit('Marché invalide.');
}

// Vérification fichier
if (
    !isset($_FILES['document']) ||
    $_FILES['document']['error'] !== UPLOAD_ERR_OK
) {
    exit("Erreur lors de l'envoi du fichier.");
}

$files = $_FILES['document'];

$nomOriginal = basename($files['name']);
$extension = strtolower(pathinfo($nomOriginal, PATHINFO_EXTENSION));

$extensionsAutorisees = [
    'pdf', 'doc', 'docx', 'xls', 'xlsx',
    'jpg', 'jpeg', 'png'
];

if (!in_array($extension, $extensionsAutorisees, true)) {
    exit('Extension non autorisée.');
}

// Taille maximale : 10 Mo
if ($files['size'] > 10 * 1024 * 1024) {
    exit('Le fichier dépasse 10 Mo.');
}

// Vérification du marché
$stmt = mysqli_prepare(
    $connexion,
    "SELECT id FROM sigm_marches WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, 'i', $marcheId);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (!mysqli_fetch_assoc($result)) {
    exit('Marché introuvable.');
}

// Dossier upload
$uploadDir = '../uploads/marches/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Génération du nom
$nomFichier = 'MARCHE_' . $marcheId . '_' .
    bin2hex(random_bytes(8)) . '.' . $extension;

$destination = $uploadDir . $nomFichier;

// Upload
if (!move_uploaded_file($files['tmp_name'], $destination)) {
    exit("Impossible d'enregistrer le fichier.");
}

// Chemin enregistré en base
$chemin = 'uploads/marches/' . $nomFichier;

$typeDocument = $_POST['type_document'];
$taille = (int) $files['size'];

// Insertion
$sql = "
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
    VALUES (?, ?, ?, ?, ?, ?, ?, 'en_entente', ?)
";

$stmt = mysqli_prepare($connexion, $sql);

mysqli_stmt_bind_param(
    $stmt,
    'isssssii',
    $marcheId,
    $nomOriginal,
    $nomFichier,
    $chemin,
    $typeDocument,
    $extension,
    $taille,
    $userId
);

if (!mysqli_stmt_execute($stmt)) {

    unlink($destination);

    exit("Erreur d'enregistrement : " . mysqli_stmt_error($stmt));
}

// Retour au détail du marché
header('Location: traitement_marche?id=' . $marcheId . '&upload=success');
exit();

?>