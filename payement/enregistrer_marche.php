<?php

session_start();

require_once '../includes/fonctions.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}

global $connexion;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: add_drp.php');
    exit();
}

/*
 * |--------------------------------------------------------------------------
 * | Fonction pour bind_param dynamique
 * |--------------------------------------------------------------------------
 */
function bindDynamicParams($stmt, $types, &$params)
{
    $bindParams = [$types];

    foreach ($params as $key => &$value) {
        $bindParams[] = &$value;
    }

    if (!call_user_func_array([$stmt, 'bind_param'], $bindParams)) {
        throw new Exception(
            'Erreur lors de la liaison des paramètres : ' . mysqli_stmt_error($stmt)
        );
    }
}

/*
 * |--------------------------------------------------------------------------
 * | Sécurité
 * |--------------------------------------------------------------------------
 */

$userId = isset($_SESSION['idUser'])
    ? (int) $_SESSION['idUser']
    : null;

$annee = date('Y');

/*
 * |--------------------------------------------------------------------------
 * | Récupération des données
 * |--------------------------------------------------------------------------
 */

$idMarche = isset($_POST['idMarche'])
    ? (int) $_POST['idMarche']
    : 0;

$idFourn = isset($_POST['idFourn'])
    ? (int) $_POST['idFourn']
    : 0;

$montant = isset($_POST['montant'])
    ? (float) $_POST['montant']
    : 0;

$typeMarche = trim($_POST['type'] ?? '');
$objet = trim($_POST['objet'] ?? '');
$reference = trim($_POST['reference'] ?? '');

/*
 * |--------------------------------------------------------------------------
 * | Déterminer si modification ou création
 * |--------------------------------------------------------------------------
 */

$isEdit = $idMarche > 0;

/*
 * |--------------------------------------------------------------------------
 * | Récupération des types selon le mode
 * |--------------------------------------------------------------------------
 */

if ($isEdit) {
    $existingDocIds = $_POST['existing_doc_ids'] ?? [];
    $existingTypes = $_POST['existing_types'] ?? [];
    $deletedDocIds = $_POST['deleted_doc_ids'] ?? [];
    $newTypes = $_POST['new_types'] ?? [];

    $files = $_FILES['new_files'] ?? [
        'name' => [],
        'error' => [],
        'size' => [],
        'tmp_name' => [],
        'type' => []
    ];
} else {
    $typesDocuments = $_POST['types'] ?? [];

    $files = $_FILES['files'] ?? [
        'name' => [],
        'error' => [],
        'size' => [],
        'tmp_name' => [],
        'type' => []
    ];
}

/*
 * |--------------------------------------------------------------------------
 * | Normalisation des IDs supprimés
 * |--------------------------------------------------------------------------
 */

if (!empty($deletedDocIds)) {
    $deletedDocIds = array_map('intval', (array) $deletedDocIds);

    // Supprimer les valeurs invalides
    $deletedDocIds = array_filter(
        $deletedDocIds,
        function ($id) {
            return $id > 0;
        }
    );

    // Réindexer
    $deletedDocIds = array_values($deletedDocIds);
}

/*
 * |--------------------------------------------------------------------------
 * | Validation commune
 * |--------------------------------------------------------------------------
 */

$erreurs = [];

if ($idFourn <= 0) {
    $erreurs[] = 'Veuillez sélectionner un fournisseur.';
}

if ($montant <= 0) {
    $erreurs[] = 'Le montant du marché est invalide.';
}

if ($typeMarche === '') {
    $erreurs[] = 'Le type de marché est obligatoire.';
}

if ($objet === '') {
    $erreurs[] = "L'objet du marché est obligatoire.";
}

if ($reference === '') {
    $erreurs[] = 'La référence du marché est obligatoire.';
}

/*
 * |--------------------------------------------------------------------------
 * | Validation spécifique selon le mode
 * |--------------------------------------------------------------------------
 */

if ($isEdit) {
    /*
     * |--------------------------------------------------------------------------
     * | Vérifier que le marché existe
     * |--------------------------------------------------------------------------
     */

    $stmtCheck = mysqli_prepare(
        $connexion,
        '
        SELECT statut, reference
        FROM sigm_marches
        WHERE id = ?
        '
    );

    if (!$stmtCheck) {
        throw new Exception(
            'Erreur préparation vérification marché : ' . mysqli_error($connexion)
        );
    }

    mysqli_stmt_bind_param($stmtCheck, 'i', $idMarche);

    if (!mysqli_stmt_execute($stmtCheck)) {
        throw new Exception(
            'Erreur vérification marché : ' . mysqli_stmt_error($stmtCheck)
        );
    }

    $resultCheck = mysqli_stmt_get_result($stmtCheck);

    $marcheActuel = mysqli_fetch_assoc($resultCheck);

    if (!$marcheActuel) {
        $erreurs[] = "Le marché n'existe pas.";
    }

    /*
     * |--------------------------------------------------------------------------
     * | Vérifier le statut
     * |--------------------------------------------------------------------------
     */

    if (
        $marcheActuel &&
        $marcheActuel['statut'] !== 'rejete'
    ) {
        $erreurs[] =
            'Ce marché ne peut plus être modifié car il est déjà validé ou engagé.';
    }

    /*
     * |--------------------------------------------------------------------------
     * | Vérifier la référence
     * |--------------------------------------------------------------------------
     */

    if (
        $marcheActuel &&
        $reference !== $marcheActuel['reference']
    ) {
        $stmtRef = mysqli_prepare(
            $connexion,
            '
            SELECT id
            FROM sigm_marches
            WHERE reference = ?
            AND id != ?
            LIMIT 1
            '
        );

        if (!$stmtRef) {
            throw new Exception(
                'Erreur préparation vérification référence : '
                . mysqli_error($connexion)
            );
        }

        mysqli_stmt_bind_param(
            $stmtRef,
            'si',
            $reference,
            $idMarche
        );

        if (!mysqli_stmt_execute($stmtRef)) {
            throw new Exception(
                'Erreur vérification référence : '
                . mysqli_stmt_error($stmtRef)
            );
        }

        $resultRef = mysqli_stmt_get_result($stmtRef);

        if (mysqli_num_rows($resultRef) > 0) {
            $erreurs[] = 'Cette référence de marché existe déjà.';
        }
    }

    /*
     * |--------------------------------------------------------------------------
     * | Vérifier nombre total de documents
     * |--------------------------------------------------------------------------
     */

    if ($marcheActuel) {
        $stmtCount = mysqli_prepare(
            $connexion,
            '
            SELECT COUNT(*) AS total
            FROM sigm_marche_documents
            WHERE marche_id = ?
            '
        );

        if (!$stmtCount) {
            throw new Exception(
                'Erreur préparation comptage documents : '
                . mysqli_error($connexion)
            );
        }

        mysqli_stmt_bind_param(
            $stmtCount,
            'i',
            $idMarche
        );

        if (!mysqli_stmt_execute($stmtCount)) {
            throw new Exception(
                'Erreur comptage documents : '
                . mysqli_stmt_error($stmtCount)
            );
        }

        $resultCount = mysqli_stmt_get_result($stmtCount);

        $rowCount = mysqli_fetch_assoc($resultCount);

        $existingCount = (int) $rowCount['total'];

        /*
         * |--------------------------------------------------------------
         * | Ne pas compter les documents supprimés
         * |--------------------------------------------------------------
         */

        $existingCount -= count($deletedDocIds);

        if ($existingCount < 0) {
            $existingCount = 0;
        }

        /*
         * |--------------------------------------------------------------
         * | Nouveaux fichiers
         * |--------------------------------------------------------------
         */

        $newFilesCount = 0;

        if (
            isset($files['name']) &&
            is_array($files['name']) &&
            !empty($files['name'][0])
        ) {
            $newFilesCount = count($files['name']);
        }

        $totalDocs = $existingCount + $newFilesCount;

        if ($totalDocs < 2) {
            $erreurs[] =
                'Veuillez avoir au minimum deux documents.';
        }

        if ($totalDocs > 10) {
            $erreurs[] =
                'Vous ne pouvez pas avoir plus de 10 documents.';
        }
    }
} else {
    /*
     * |--------------------------------------------------------------------------
     * | Mode création : référence unique
     * |--------------------------------------------------------------------------
     */

    $stmt = mysqli_prepare(
        $connexion,
        '
        SELECT id
        FROM sigm_marches
        WHERE reference = ?
        LIMIT 1
        '
    );

    if (!$stmt) {
        throw new Exception(
            'Erreur préparation référence : ' . mysqli_error($connexion)
        );
    }

    mysqli_stmt_bind_param(
        $stmt,
        's',
        $reference
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception(
            'Erreur vérification référence : '
            . mysqli_stmt_error($stmt)
        );
    }

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $erreurs[] =
            'Cette référence de marché existe déjà.';
    }

    /*
     * |--------------------------------------------------------------------------
     * | Vérifier les fichiers
     * |--------------------------------------------------------------------------
     */

    if (
        !isset($_FILES['files']) ||
        empty($_FILES['files']['name'][0])
    ) {
        $erreurs[] =
            'Veuillez joindre au moins deux documents.';
    } else {
        $nombreFichiers =
            count($_FILES['files']['name']);

        if ($nombreFichiers < 2) {
            $erreurs[] =
                'Veuillez joindre au minimum deux documents.';
        }

        if ($nombreFichiers > 10) {
            $erreurs[] =
                'Vous ne pouvez pas joindre plus de 10 documents.';
        }
    }
}

/*
 * |--------------------------------------------------------------------------
 * | Si erreurs : redirection
 * |--------------------------------------------------------------------------
 */

if (!empty($erreurs)) {
    $_SESSION['error'] =
        implode('<br>', $erreurs);

    header(
        'Location: '
        . ($isEdit
            ? 'add_drp.php?id=' . $idMarche
            : 'add_drp.php')
    );

    exit();
}

/*
 * |--------------------------------------------------------------------------
 * | Vérifier le fournisseur
 * |--------------------------------------------------------------------------
 */

$stmt = mysqli_prepare(
    $connexion,
    '
    SELECT idFourn, nom
    FROM bud_fournisseur
    WHERE idFourn = ?
    LIMIT 1
    '
);

if (!$stmt) {
    $_SESSION['error'] =
        'Erreur lors de la préparation de la vérification du fournisseur.';

    header(
        'Location: '
        . ($isEdit
            ? 'add_drp.php?id=' . $idMarche
            : 'add_drp.php')
    );

    exit();
}

mysqli_stmt_bind_param(
    $stmt,
    'i',
    $idFourn
);

if (!mysqli_stmt_execute($stmt)) {
    $_SESSION['error'] =
        'Erreur lors de la vérification du fournisseur : '
        . mysqli_stmt_error($stmt);

    header(
        'Location: '
        . ($isEdit
            ? 'add_drp.php?id=' . $idMarche
            : 'add_drp.php')
    );

    exit();
}

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    $_SESSION['error'] =
        "Le fournisseur sélectionné n'existe pas.";

    header(
        'Location: '
        . ($isEdit
            ? 'add_drp.php?id=' . $idMarche
            : 'add_drp.php')
    );

    exit();
}

$fournisseur = mysqli_fetch_assoc($result);

/*
 * |--------------------------------------------------------------------------
 * | Dossier upload
 * |--------------------------------------------------------------------------
 */

$uploadDir = '../uploads/marches/';

if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0775, true)) {
        $_SESSION['error'] =
            "Impossible de créer le dossier d'upload.";

        header(
            'Location: '
            . ($isEdit
                ? 'add_drp.php?id=' . $idMarche
                : 'add_drp.php')
        );

        exit();
    }
}

/*
 * |--------------------------------------------------------------------------
 * | Transaction
 * |--------------------------------------------------------------------------
 */

mysqli_begin_transaction($connexion);

try {
    /*
     * |--------------------------------------------------------------------------
     * | MODE MODIFICATION
     * |--------------------------------------------------------------------------
     */

    if ($isEdit) {
        /*
         * |--------------------------------------------------------------------------
         * | Mise à jour du marché
         * |--------------------------------------------------------------------------
         */

        $sql = "
        UPDATE sigm_marches
        SET
            reference = ?,
            montant = ?,
            type_marche = ?,
            objet = ?,
            id_fournisseur = ?,
            statut = 'en_attente'
        WHERE id = ?
        ";

        $stmt = mysqli_prepare(
            $connexion,
            $sql
        );

        if (!$stmt) {
            throw new Exception(
                'Impossible de préparer la mise à jour du marché : '
                . mysqli_error($connexion)
            );
        }

        mysqli_stmt_bind_param(
            $stmt,
            'sdssii',
            $reference,
            $montant,
            $typeMarche,
            $objet,
            $idFourn,
            $idMarche
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(
                'Impossible de mettre à jour le marché : '
                . mysqli_stmt_error($stmt)
            );
        }

        /*
         * |--------------------------------------------------------------------------
         * | Suppression des documents
         * |--------------------------------------------------------------------------
         */

        if (!empty($deletedDocIds)) {
            $placeholders =
                implode(
                    ',',
                    array_fill(
                        0,
                        count($deletedDocIds),
                        '?'
                    )
                );

            /*
             * |--------------------------------------------------------------------------
             * | Récupérer les fichiers
             * |--------------------------------------------------------------------------
             */

            $stmtGetDocs = mysqli_prepare(
                $connexion,
                "
                SELECT chemin_fichier
                FROM sigm_marche_documents
                WHERE id IN ($placeholders)
                AND marche_id = ?
                "
            );

            if (!$stmtGetDocs) {
                throw new Exception(
                    'Erreur préparation récupération documents : '
                    . mysqli_error($connexion)
                );
            }

            /*
             * | Ajouter l'id du marché à la liste des paramètres
             */

            $paramsGetDocs = $deletedDocIds;
            $paramsGetDocs[] = $idMarche;

            $typesGetDocs =
                str_repeat(
                    'i',
                    count($deletedDocIds) + 1
                );

            bindDynamicParams(
                $stmtGetDocs,
                $typesGetDocs,
                $paramsGetDocs
            );

            if (!mysqli_stmt_execute($stmtGetDocs)) {
                throw new Exception(
                    'Erreur récupération documents : '
                    . mysqli_stmt_error($stmtGetDocs)
                );
            }

            $resultDocs =
                mysqli_stmt_get_result($stmtGetDocs);

            while (
                $doc =
                    mysqli_fetch_assoc($resultDocs)
            ) {
                $filePath =
                    '../'
                    . $doc['chemin_fichier'];

                if (
                    file_exists($filePath) &&
                    is_file($filePath)
                ) {
                    if (!unlink($filePath)) {
                        // On ne bloque pas la transaction
                        // si le fichier physique ne peut pas être supprimé
                    }
                }
            }

            /*
             * |--------------------------------------------------------------------------
             * | Supprimer les enregistrements
             * |--------------------------------------------------------------------------
             */

            $stmtDelete = mysqli_prepare(
                $connexion,
                "
                DELETE FROM sigm_marche_documents
                WHERE id IN ($placeholders)
                AND marche_id = ?
                "
            );

            if (!$stmtDelete) {
                throw new Exception(
                    'Erreur préparation suppression documents : '
                    . mysqli_error($connexion)
                );
            }

            $paramsDelete = $deletedDocIds;
            $paramsDelete[] = $idMarche;

            $typesDelete =
                str_repeat(
                    'i',
                    count($deletedDocIds) + 1
                );

            bindDynamicParams(
                $stmtDelete,
                $typesDelete,
                $paramsDelete
            );

            if (!mysqli_stmt_execute($stmtDelete)) {
                throw new Exception(
                    'Erreur lors de la suppression des documents : '
                    . mysqli_stmt_error($stmtDelete)
                );
            }
        }

        /*
         * |--------------------------------------------------------------------------
         * | Mise à jour des types des documents existants
         * |--------------------------------------------------------------------------
         */

        foreach (
            $existingTypes as $docId => $newType
        ) {
            $docId = (int) $docId;

            $newType = trim($newType);

            if (
                !in_array(
                    $docId,
                    $deletedDocIds,
                    true
                ) &&
                $newType !== ''
            ) {
                $stmtUpdateType =
                    mysqli_prepare(
                        $connexion,
                        '
                        UPDATE sigm_marche_documents
                        SET type_document = ?
                        WHERE id = ?
                        AND marche_id = ?
                        '
                    );

                if (!$stmtUpdateType) {
                    throw new Exception(
                        'Erreur préparation mise à jour type document : '
                        . mysqli_error($connexion)
                    );
                }

                mysqli_stmt_bind_param(
                    $stmtUpdateType,
                    'sii',
                    $newType,
                    $docId,
                    $idMarche
                );

                if (
                    !mysqli_stmt_execute(
                        $stmtUpdateType
                    )
                ) {
                    throw new Exception(
                        'Erreur lors de la mise à jour du type du document : '
                        . mysqli_stmt_error($stmtUpdateType)
                    );
                }
            }
        }

        /*
         * |--------------------------------------------------------------------------
         * | Upload des nouveaux documents
         * |--------------------------------------------------------------------------
         */

        if (
            isset($files['name']) &&
            is_array($files['name']) &&
            !empty($files['name'][0])
        ) {
            $nombreFichiers =
                count($files['name']);

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

            for (
                $i = 0;
                $i < $nombreFichiers;
                $i++
            ) {
                /*
                 * |--------------------------------------------------------------------------
                 * | Vérification upload
                 * |--------------------------------------------------------------------------
                 */

                if (
                    $files['error'][$i] !==
                    UPLOAD_ERR_OK
                ) {
                    throw new Exception(
                        "Erreur lors de l'envoi du fichier : "
                        . $files['name'][$i]
                    );
                }

                $nomOriginal =
                    $files['name'][$i];

                $extension =
                    strtolower(
                        pathinfo(
                            $nomOriginal,
                            PATHINFO_EXTENSION
                        )
                    );

                /*
                 * |--------------------------------------------------------------------------
                 * | Extension
                 * |--------------------------------------------------------------------------
                 */

                if (
                    !in_array(
                        $extension,
                        $extensionsAutorisees,
                        true
                    )
                ) {
                    throw new Exception(
                        'Extension non autorisée : '
                        . $nomOriginal
                    );
                }

                /*
                 * |--------------------------------------------------------------------------
                 * | Taille
                 * |--------------------------------------------------------------------------
                 */

                if (
                    $files['size'][$i] >
                    10 * 1024 * 1024
                ) {
                    throw new Exception(
                        'Le fichier '
                        . $nomOriginal
                        . ' dépasse 10 Mo.'
                    );
                }

                /*
                 * |--------------------------------------------------------------------------
                 * | Type document
                 * |--------------------------------------------------------------------------
                 */

                $typeDocument =
                    trim(
                        $newTypes[$i] ?? ''
                    );

                if ($typeDocument === '') {
                    throw new Exception(
                        'Le type du document '
                        . $nomOriginal
                        . ' est obligatoire.'
                    );
                }

                /*
                 * |--------------------------------------------------------------------------
                 * | Nom fichier
                 * |--------------------------------------------------------------------------
                 */

                $nomFichier =
                    'MARCHE_'
                    . $idMarche
                    . '_'
                    . bin2hex(
                        random_bytes(8)
                    )
                    . '.'
                    . $extension;

                $destination =
                    $uploadDir
                    . $nomFichier;

                /*
                 * |--------------------------------------------------------------------------
                 * | Déplacement fichier
                 * |--------------------------------------------------------------------------
                 */

                if (
                    !move_uploaded_file(
                        $files['tmp_name'][$i],
                        $destination
                    )
                ) {
                    throw new Exception(
                        "Impossible d'enregistrer le fichier "
                        . $nomOriginal
                    );
                }

                $chemin =
                    'uploads/marches/'
                    . $nomFichier;

                /*
                 * |--------------------------------------------------------------------------
                 * | Enregistrement document
                 * |--------------------------------------------------------------------------
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
                    (
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        'en_attente',
                        ?
                    )
                ";

                $stmtDoc =
                    mysqli_prepare(
                        $connexion,
                        $sqlDocument
                    );

                if (!$stmtDoc) {
                    throw new Exception(
                        'Erreur préparation enregistrement document : '
                        . mysqli_error($connexion)
                    );
                }

                $taille =
                    (int) $files['size'][$i];

                mysqli_stmt_bind_param(
                    $stmtDoc,
                    'isssssis',
                    $idMarche,
                    $nomOriginal,
                    $nomFichier,
                    $chemin,
                    $typeDocument,
                    $extension,
                    $taille,
                    $userId
                );

                if (
                    !mysqli_stmt_execute(
                        $stmtDoc
                    )
                ) {
                    throw new Exception(
                        "Erreur lors de l'enregistrement du document : "
                        . mysqli_stmt_error($stmtDoc)
                    );
                }
            }
        }

        /*
         * |--------------------------------------------------------------------------
         * | Historique de modification
         * |--------------------------------------------------------------------------
         */

        $sqlHistorique = '
            INSERT INTO sigm_marche_historique
            (
                marche_id,
                ancien_statut,
                nouveau_statut,
                commentaire,
                user_id
            )
            VALUES (?, ?, ?, ?, ?)
        ';

        $commentaire =
            'Modification du dossier de marché '
            . '(référence : '
            . $reference
            . ')';

        /*
         * | IMPORTANT :
         * | bind_param() ne peut pas recevoir directement
         * | 'en_attente'. Il faut une variable.
         */

        $nouveauStatut = 'en_attente';

        /*
         * | L'ancien statut doit également être dans une variable.
         */

        $ancienStatut =
            $marcheActuel['statut'];

        $stmtHist =
            mysqli_prepare(
                $connexion,
                $sqlHistorique
            );

        if (!$stmtHist) {
            throw new Exception(
                'Erreur préparation historique : '
                . mysqli_error($connexion)
            );
        }

        mysqli_stmt_bind_param(
            $stmtHist,
            'isssi',
            $idMarche,
            $ancienStatut,
            $nouveauStatut,
            $commentaire,
            $userId
        );

        if (
            !mysqli_stmt_execute(
                $stmtHist
            )
        ) {
            throw new Exception(
                "Erreur lors de l'enregistrement de l'historique : "
                . mysqli_stmt_error($stmtHist)
            );
        }

        /*
         * |--------------------------------------------------------------------------
         * | Message modification
         * |--------------------------------------------------------------------------
         */

        $messageSuccess =
            'Le marché '
            . htmlspecialchars($reference)
            . ' a été modifié avec succès. '
            . 'Il reste en attente de validation.';

        $redirectUrl =
            'add_drp.php?id='
            . $idMarche;
    } else {
        /*
         * |--------------------------------------------------------------------------
         * | MODE CRÉATION
         * |--------------------------------------------------------------------------
         */

        $files =
            $_FILES['files'];

        $nombreFichiers =
            count($files['name']);

        /*
         * |--------------------------------------------------------------------------
         * | Création du marché
         * |--------------------------------------------------------------------------
         */

        $sql = "
            INSERT INTO sigm_marches
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
            (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                'en_attente',
                ?
            )
        ";

        $stmt =
            mysqli_prepare(
                $connexion,
                $sql
            );

        if (!$stmt) {
            throw new Exception(
                "Impossible de préparer l'enregistrement du marché : "
                . mysqli_error($connexion)
            );
        }

        mysqli_stmt_bind_param(
            $stmt,
            'sidssii',
            $reference,
            $annee,
            $montant,
            $typeMarche,
            $objet,
            $idFourn,
            $userId
        );

        if (
            !mysqli_stmt_execute(
                $stmt
            )
        ) {
            throw new Exception(
                "Impossible d'enregistrer le marché : "
                . mysqli_stmt_error($stmt)
            );
        }

        /*
         * |--------------------------------------------------------------------------
         * | ID du marché
         * |--------------------------------------------------------------------------
         */

        $marcheId =
            mysqli_insert_id(
                $connexion
            );

        /*
         * |--------------------------------------------------------------------------
         * | Historique initial
         * |--------------------------------------------------------------------------
         */

        $sqlHistorique = "
            INSERT INTO sigm_marche_historique
            (
                marche_id,
                ancien_statut,
                nouveau_statut,
                commentaire,
                user_id
            )
            VALUES
            (
                ?,
                NULL,
                'en_attente',
                ?,
                ?
            )
        ";

        $commentaire =
            'Création du dossier de marché '
            . 'pour le fournisseur : '
            . $fournisseur['nom'];

        $stmtHist =
            mysqli_prepare(
                $connexion,
                $sqlHistorique
            );

        if (!$stmtHist) {
            throw new Exception(
                'Erreur préparation historique initial : '
                . mysqli_error($connexion)
            );
        }

        mysqli_stmt_bind_param(
            $stmtHist,
            'isi',
            $marcheId,
            $commentaire,
            $userId
        );

        if (
            !mysqli_stmt_execute(
                $stmtHist
            )
        ) {
            throw new Exception(
                "Erreur lors de l'enregistrement de l'historique initial : "
                . mysqli_stmt_error($stmtHist)
            );
        }

        /*
         * |--------------------------------------------------------------------------
         * | Extensions autorisées
         * |--------------------------------------------------------------------------
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

        /*
         * |--------------------------------------------------------------------------
         * | Enregistrement des documents
         * |--------------------------------------------------------------------------
         */

        for (
            $i = 0;
            $i < $nombreFichiers;
            $i++
        ) {
            /*
             * |--------------------------------------------------------------------------
             * | Vérification upload
             * |--------------------------------------------------------------------------
             */

            if (
                $files['error'][$i] !==
                UPLOAD_ERR_OK
            ) {
                throw new Exception(
                    "Erreur lors de l'envoi du fichier : "
                    . $files['name'][$i]
                );
            }

            /*
             * |--------------------------------------------------------------------------
             * | Informations fichier
             * |--------------------------------------------------------------------------
             */

            $nomOriginal =
                $files['name'][$i];

            $extension =
                strtolower(
                    pathinfo(
                        $nomOriginal,
                        PATHINFO_EXTENSION
                    )
                );

            /*
             * |--------------------------------------------------------------------------
             * | Extension
             * |--------------------------------------------------------------------------
             */

            if (
                !in_array(
                    $extension,
                    $extensionsAutorisees,
                    true
                )
            ) {
                throw new Exception(
                    'Extension non autorisée : '
                    . $nomOriginal
                );
            }

            /*
             * |--------------------------------------------------------------------------
             * | Taille
             * |--------------------------------------------------------------------------
             */

            if (
                $files['size'][$i] >
                10 * 1024 * 1024
            ) {
                throw new Exception(
                    'Le fichier '
                    . $nomOriginal
                    . ' dépasse 10 Mo.'
                );
            }

            /*
             * |--------------------------------------------------------------------------
             * | Type document
             * |--------------------------------------------------------------------------
             */

            $typeDocument =
                trim(
                    $typesDocuments[$i] ?? ''
                );

            if ($typeDocument === '') {
                throw new Exception(
                    'Le type du document '
                    . $nomOriginal
                    . ' est obligatoire.'
                );
            }

            /*
             * |--------------------------------------------------------------------------
             * | Génération nom fichier
             * |--------------------------------------------------------------------------
             */

            $nomFichier =
                'MARCHE_'
                . $marcheId
                . '_'
                . bin2hex(
                    random_bytes(8)
                )
                . '.'
                . $extension;

            $destination =
                $uploadDir
                . $nomFichier;

            /*
             * |--------------------------------------------------------------------------
             * | Upload
             * |--------------------------------------------------------------------------
             */

            if (
                !move_uploaded_file(
                    $files['tmp_name'][$i],
                    $destination
                )
            ) {
                throw new Exception(
                    "Impossible d'enregistrer le fichier "
                    . $nomOriginal
                );
            }

            /*
             * |--------------------------------------------------------------------------
             * | Chemin enregistré en DB
             * |--------------------------------------------------------------------------
             */

            $chemin =
                'uploads/marches/'
                . $nomFichier;

            /*
             * |--------------------------------------------------------------------------
             * | INSERT document
             * |--------------------------------------------------------------------------
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
                (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    'en_attente',
                    ?
                )
            ";

            $stmtDoc =
                mysqli_prepare(
                    $connexion,
                    $sqlDocument
                );

            if (!$stmtDoc) {
                throw new Exception(
                    'Erreur préparation document : '
                    . mysqli_error($connexion)
                );
            }

            $taille =
                (int) $files['size'][$i];

            mysqli_stmt_bind_param(
                $stmtDoc,
                'isssssis',
                $marcheId,
                $nomOriginal,
                $nomFichier,
                $chemin,
                $typeDocument,
                $extension,
                $taille,
                $userId
            );

            if (
                !mysqli_stmt_execute(
                    $stmtDoc
                )
            ) {
                throw new Exception(
                    "Erreur lors de l'enregistrement du document : "
                    . mysqli_stmt_error($stmtDoc)
                );
            }
        }

        /*
         * |--------------------------------------------------------------------------
         * | Message succès
         * |--------------------------------------------------------------------------
         */

        $messageSuccess =
            'Le dossier de marché '
            . htmlspecialchars($reference)
            . ' pour le fournisseur '
            . htmlspecialchars($fournisseur['nom'])
            . ' a été enregistré avec succès. '
            . 'Il est maintenant en attente de validation.';

        /*
         * |--------------------------------------------------------------------------
         * | Redirection
         * |--------------------------------------------------------------------------
         */

        $redirectUrl =
            'traitement_marche.php?id='
            . $marcheId;
    }

    /*
     * |--------------------------------------------------------------------------
     * | Validation transaction
     * |--------------------------------------------------------------------------
     */

    if (!mysqli_commit($connexion)) {
        throw new Exception(
            'Impossible de valider la transaction.'
        );
    }

    /*
     * |--------------------------------------------------------------------------
     * | Message succès
     * |--------------------------------------------------------------------------
     */

    $_SESSION['success'] =
        $messageSuccess;

    /*
     * |--------------------------------------------------------------------------
     * | Redirection
     * |--------------------------------------------------------------------------
     */

    header(
        'Location: '
        . $redirectUrl
    );

    exit();
} catch (Throwable $e) {
    /*
     * |--------------------------------------------------------------------------
     * | Annuler transaction
     * |--------------------------------------------------------------------------
     */

    mysqli_rollback($connexion);

    /*
     * |--------------------------------------------------------------------------
     * | Message erreur
     * |--------------------------------------------------------------------------
     */

    $_SESSION['error'] =
        'Erreur lors du traitement : '
        . $e->getMessage();

    /*
     * |--------------------------------------------------------------------------
     * | Redirection
     * |--------------------------------------------------------------------------
     */

    if (
        $isEdit &&
        $idMarche > 0
    ) {
        header(
            'Location: add_drp.php?id='
            . $idMarche
        );
    } else {
        header(
            'Location: add_drp.php'
        );
    }

    exit();
}
?>