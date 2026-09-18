<?php
include '../../includes/fonctions.php';

if (isset($_POST['numFourn'], $_POST['nom'], $_POST['adresse'], $_POST['contact'], $_POST['nature'])) {

    $idFourn = $_POST['idFourn'] ?? null;

    $numFourn = $_POST['numFourn'];
    $nom = $_POST['nom'];
    $adresse = $_POST['adresse'];
    $contact = $_POST['contact'];
    $nature = $_POST['nature'];

    // Nouveaux champs
    $ninea = $_POST['ninea'] ?? '';
    $rccm = $_POST['rccm'] ?? '';
    $email = $_POST['email'] ?? '';
    $observations = $_POST['observations'] ?? '';

    // Champs agrément
    $numAgrement = $_POST['numAgrement'] ?? '';
    $fichierAgrement = null;

    // =====================================================
    // GESTION DE L'UPLOAD DU FICHIER D'AGRÉMENT
    // =====================================================
    if (isset($_FILES['fichierAgrement']) && $_FILES['fichierAgrement']['error'] === UPLOAD_ERR_OK) {

        $uploadDir = '../../uploads/agrements/';

        // Créer le dossier s'il n'existe pas
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = $_FILES['fichierAgrement']['name'];
        $fileTmp  = $_FILES['fichierAgrement']['tmp_name'];
        $fileSize = $_FILES['fichierAgrement']['size'];
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Extensions autorisées
        $extensionsAutorisees = ['pdf', 'jpg', 'jpeg', 'png'];

        // Taille max : 5 Mo
        $tailleMax = 5 * 1024 * 1024;

        if (!in_array($fileExt, $extensionsAutorisees)) {
            $errorMsg = "Format de fichier non autorisé. Formats acceptés : PDF, JPG, JPEG, PNG.";
            $redirectUrl = !empty($idFourn)
                ? "add_fourn.php?idFourn=$idFourn&error=" . urlencode($errorMsg)
                : "add_fourn.php?error=" . urlencode($errorMsg);
            header("Location: $redirectUrl");
            exit();
        }

        if ($fileSize > $tailleMax) {
            $errorMsg = "Le fichier dépasse la taille maximale autorisée (5 Mo).";
            $redirectUrl = !empty($idFourn)
                ? "add_fourn.php?idFourn=$idFourn&error=" . urlencode($errorMsg)
                : "add_fourn.php?error=" . urlencode($errorMsg);
            header("Location: $redirectUrl");
            exit();
        }

        // Nom unique pour éviter les conflits
        $nouveauNom = 'agrement_' . uniqid() . '.' . $fileExt;
        $destination = $uploadDir . $nouveauNom;

        if (move_uploaded_file($fileTmp, $destination)) {
            // Chemin relatif à stocker en BDD
            $fichierAgrement = 'uploads/agrements/' . $nouveauNom;
        } else {
            $errorMsg = "Erreur lors de l'enregistrement du fichier.";
            $redirectUrl = !empty($idFourn)
                ? "add_fourn.php?idFourn=$idFourn&error=" . urlencode($errorMsg)
                : "add_fourn.php?error=" . urlencode($errorMsg);
            header("Location: $redirectUrl");
            exit();
        }
    }

    // MODE MODIFICATION
    if (!empty($idFourn)) {

        // Si aucun nouveau fichier n'a été uploadé, on conserve l'ancien
        if ($fichierAgrement === null) {
            $ancien = getFournisseurById($idFourn);
            $fichierAgrement = $ancien['fichierAgrement'] ?? null;
        }

        $resultat = modifierFournisseur(
            $idFourn,
            $numFourn,
            $nom,
            $adresse,
            $contact,
            $nature,
            $ninea,
            $rccm,
            $email,
            $observations,
            $numAgrement,
            $fichierAgrement
        );

        if ($resultat === true) {
            header("Location: add_fourn.php?idFourn=$idFourn&success=1");
        } else {
            header("Location: add_fourn.php?idFourn=$idFourn&error=" . urlencode($resultat));
        }

    } 
    // MODE AJOUT
    else {

        $resultat = ajouterFournisseur(
            $numFourn,
            $nom,
            $adresse,
            $contact,
            $nature,
            $ninea,
            $rccm,
            $email,
            $observations,
            $numAgrement,
            $fichierAgrement
        );

        if ($resultat === true) {
            header("Location: add_fourn.php?success=1");
        } else {
            header("Location: add_fourn.php?error=" . urlencode($resultat));
        }
    }
}
?>