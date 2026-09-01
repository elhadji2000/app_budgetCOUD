<?php

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}

include '../../includes/fonctions.php';

global $connexion;

/*
 * |--------------------------------------------------------------------------
 * | ENREGISTREMENT D'UN ENGAGEMENT TEMPORAIRE
 * |--------------------------------------------------------------------------
 * |
 * | Maintenant :
 * |
 * | - Le marché fournit : objet, montant, type et fournisseur
 * | - L'utilisateur choisit : compte + date
 * |
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /*
     * |--------------------------------------------------------------------------
     * | Vérification des données reçues
     * |--------------------------------------------------------------------------
     */

    $marche_id = isset($_POST['marche_id'])
        ? (int) $_POST['marche_id']
        : 0;

    $idCompte = isset($_POST['idCompte'])
        ? (int) $_POST['idCompte']
        : 0;

    $dateEng = $_POST['dateEng'] ?? '';

    if ($marche_id <= 0) {
        header(
            'Location: ajouter.php?error='
            . urlencode('Veuillez sélectionner un marché.')
        );

        exit();
    }

    if ($idCompte <= 0) {
        header(
            'Location: ajouter.php?error='
            . urlencode('Veuillez sélectionner un numéro de compte.')
        );

        exit();
    }

    if (empty($dateEng)) {
        header(
            'Location: ajouter.php?error='
            . urlencode("Veuillez renseigner la date de l'engagement.")
        );

        exit();
    }

    /*
     * |--------------------------------------------------------------------------
     * | Récupérer le marché
     * |--------------------------------------------------------------------------
     */

    $sqlMarche = '
        SELECT
            id,
            reference,
            objet,
            montant,
            type_marche,
            id_fournisseur AS idFourn,
            statut
        FROM sigm_marches
        WHERE id = ?
        LIMIT 1
    ';

    $stmt = mysqli_prepare($connexion, $sqlMarche);

    if (!$stmt) {
        header(
            'Location: ajouter.php?error='
            . urlencode('Erreur lors de la préparation de la requête.')
        );

        exit();
    }

    mysqli_stmt_bind_param(
        $stmt,
        'i',
        $marche_id
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $marche = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    /*
     * |--------------------------------------------------------------------------
     * | Vérifier que le marché existe
     * |--------------------------------------------------------------------------
     */

    if (!$marche) {
        header(
            'Location: ajouter.php?error='
            . urlencode('Marché introuvable.')
        );

        exit();
    }

    /*
     * |--------------------------------------------------------------------------
     * | Vérifier que le marché est bien validé
     * |--------------------------------------------------------------------------
     */

    if ($marche['statut'] !== 'valide') {
        header(
            'Location: ajouter.php?error='
            . urlencode(
                "Ce marché ne peut pas être engagé car il n'est pas validé."
            )
        );

        exit();
    }

    /*
     * |--------------------------------------------------------------------------
     * | Récupération des informations du marché
     * |--------------------------------------------------------------------------
     */

    $objet = $marche['objet'];

    $montant = (float) $marche['montant'];

    /*
     * Le marché utilise type_marche.
     * L'engagement utilise type_eng.
     */
    $type_eng = $marche['type_marche'];

    /*
     * Fournisseur provenant du marché
     */
    $idFourn = !empty($marche['idFourn'])
        ? (int) $marche['idFourn']
        : null;

    /*
     * |--------------------------------------------------------------------------
     * | Vérifier le montant
     * |--------------------------------------------------------------------------
     */

    if ($montant <= 0) {
        header(
            'Location: ajouter.php?error='
            . urlencode(
                'Le montant du marché est invalide.'
            )
        );

        exit();
    }

    /*
     * |--------------------------------------------------------------------------
     * | Vérifier que le compte existe
     * |--------------------------------------------------------------------------
     */

    $sqlCompte = '
        SELECT idCompte
        FROM bud_compte
        WHERE idCompte = ?
        LIMIT 1
    ';

    $stmtCompte = mysqli_prepare(
        $connexion,
        $sqlCompte
    );

    if (!$stmtCompte) {
        header(
            'Location: ajouter.php?error='
            . urlencode('Erreur lors de la vérification du compte.')
        );

        exit();
    }

    mysqli_stmt_bind_param(
        $stmtCompte,
        'i',
        $idCompte
    );

    mysqli_stmt_execute($stmtCompte);

    $resultCompte = mysqli_stmt_get_result($stmtCompte);

    $compteExiste = mysqli_fetch_assoc($resultCompte);

    mysqli_stmt_close($stmtCompte);

    if (!$compteExiste) {
        header(
            'Location: ajouter.php?error='
            . urlencode('Le compte budgétaire sélectionné est introuvable.')
        );

        exit();
    }

    /*
     * |--------------------------------------------------------------------------
     * | Vérifier si le marché possède déjà un engagement
     * |--------------------------------------------------------------------------
     */

    $sqlExiste = '
        SELECT idEng
        FROM bud_engagements
        WHERE idMarche = ?
        LIMIT 1
    ';

    $stmtExiste = mysqli_prepare(
        $connexion,
        $sqlExiste
    );

    if ($stmtExiste) {
        mysqli_stmt_bind_param(
            $stmtExiste,
            'i',
            $marche_id
        );

        mysqli_stmt_execute($stmtExiste);

        $resultExiste = mysqli_stmt_get_result($stmtExiste);

        if (mysqli_fetch_assoc($resultExiste)) {
            mysqli_stmt_close($stmtExiste);

            header(
                'Location: ajouter.php?error='
                . urlencode(
                    'Ce marché possède déjà un engagement.'
                )
            );

            exit();
        }

        mysqli_stmt_close($stmtExiste);
    }

    /*
     * |--------------------------------------------------------------------------
     * | Vérifier également les engagements temporaires
     * |--------------------------------------------------------------------------
     */

    $sqlTempExiste = '
        SELECT idEng
        FROM bud_engagements_temp
        WHERE idMarche = ?
        LIMIT 1
    ';

    $stmtTempExiste = mysqli_prepare(
        $connexion,
        $sqlTempExiste
    );

    if ($stmtTempExiste) {
        mysqli_stmt_bind_param(
            $stmtTempExiste,
            'i',
            $marche_id
        );

        mysqli_stmt_execute($stmtTempExiste);

        $resultTemp = mysqli_stmt_get_result(
            $stmtTempExiste
        );

        if (mysqli_fetch_assoc($resultTemp)) {
            mysqli_stmt_close($stmtTempExiste);

            header(
                'Location: ajouter.php?error='
                . urlencode(
                    'Ce marché est déjà en cours de traitement.'
                )
            );

            exit();
        }

        mysqli_stmt_close($stmtTempExiste);
    }

    /*
     * |--------------------------------------------------------------------------
     * | Enregistrement temporaire
     * |--------------------------------------------------------------------------
     */

    /*
     * IMPORTANT :
     * La fonction ajouterEngagement_temp()
     * doit maintenant accepter idMarche.
     */
    /* var_dump($dateEng);
    var_dump($montant);
    var_dump($type_eng);
    var_dump($objet);
    exit; */

    $resultat = ajouterEngagement_temp($dateEng,$type_eng,$montant,$objet,$idCompte,$idFourn,$marche_id);

    /*
     * |--------------------------------------------------------------------------
     * | Résultat
     * |--------------------------------------------------------------------------
     */

    if ($resultat === true) {
        header(
            'Location: liste_engs.php?success=3'
        );

        exit();
    } else {
        header(
            'Location: ajouter.php?error='
            . urlencode($resultat)
        );

        exit();
    }
}

/*
 * |--------------------------------------------------------------------------
 * | VALIDATION D'UN ENGAGEMENT TEMPORAIRE
 * |--------------------------------------------------------------------------
 * |
 * | Après validation :
 * |
 * | bud_engagements_temp
 * |          ↓
 * | bud_engagements
 * |
 * | et le marché :
 * |
 * | valide → engager
 * |
 */

if (isset($_GET['valider_id'])) {
    $idTemp = (int) $_GET['valider_id'];

    if ($idTemp <= 0) {
        header(
            'Location: liste_engs.php?error='
            . urlencode('Engagement temporaire invalide.')
        );

        exit();
    }

    /*
     * |--------------------------------------------------------------------------
     * | Récupérer l'engagement temporaire
     * |--------------------------------------------------------------------------
     */

    $sql = '
        SELECT *
        FROM bud_engagements_temp
        WHERE idEng = ?
        LIMIT 1
    ';

    $stmt = mysqli_prepare(
        $connexion,
        $sql
    );

    if (!$stmt) {
        header(
            'Location: liste_engs.php?error='
            . urlencode("Erreur lors de la récupération de l'engagement.")
        );

        exit();
    }

    mysqli_stmt_bind_param(
        $stmt,
        'i',
        $idTemp
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if (!$row) {
        header(
            'Location: liste_engs.php?error='
            . urlencode('Enregistrement temporaire introuvable.')
        );

        exit();
    }

    /*
     * |--------------------------------------------------------------------------
     * | Vérifier le marché
     * |--------------------------------------------------------------------------
     */

    $idMarche = isset($row['idMarche'])
        ? (int) $row['idMarche']
        : 0;

    if ($idMarche <= 0) {
        header(
            'Location: liste_engs.php?error='
            . urlencode(
                "Cet engagement n'est associé à aucun marché."
            )
        );

        exit();
    }

    /*
     * |--------------------------------------------------------------------------
     * | Vérifier que le marché est toujours validé
     * |--------------------------------------------------------------------------
     */

    $sqlMarche = '
        SELECT id, statut
        FROM sigm_marches
        WHERE id = ?
        LIMIT 1
    ';

    $stmtMarche = mysqli_prepare(
        $connexion,
        $sqlMarche
    );

    mysqli_stmt_bind_param(
        $stmtMarche,
        'i',
        $idMarche
    );

    mysqli_stmt_execute($stmtMarche);

    $resultMarche = mysqli_stmt_get_result(
        $stmtMarche
    );

    $marche = mysqli_fetch_assoc(
        $resultMarche
    );

    mysqli_stmt_close($stmtMarche);

    if (!$marche) {
        header(
            'Location: liste_engs.php?error='
            . urlencode('Marché associé introuvable.')
        );

        exit();
    }

    if ($marche['statut'] !== 'valide') {
        header(
            'Location: liste_engs.php?error='
            . urlencode(
                "Ce marché n'est plus disponible pour validation."
            )
        );

        exit();
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
         * | Insérer dans bud_engagements
         * |--------------------------------------------------------------------------
         */

        $sqlInsert = '
            INSERT INTO bud_engagements
            (
                dateEng,
                type_eng,
                montant,
                objet,
                idCompte,
                idFourn,
                idMarche
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ';

        $stmtInsert = mysqli_prepare($connexion, $sqlInsert);

        if (!$stmtInsert) {
            throw new Exception(
                "Erreur lors de la préparation de l'engagement."
            );
        }
        mysqli_stmt_bind_param(
            $stmtInsert,
            'ssdsiii',
            $row['dateEng'],
            $row['type_eng'],
            $row['montant'],
            $row['objet'],
            $row['idCompte'],
            $row['idFourn'],
            $idMarche
        );
        if (!mysqli_stmt_execute($stmtInsert)) {
            throw new Exception(
                "Erreur lors de l'enregistrement de l'engagement : "
                . mysqli_stmt_error($stmtInsert)
            );
        }
        mysqli_stmt_close($stmtInsert);

        /*
         * |--------------------------------------------------------------------------
         * | Refaire l'insertion avec le bon bind_param
         * |--------------------------------------------------------------------------
         */

        $stmtInsert = mysqli_prepare(
            $connexion,
            $sqlInsert
        );

        mysqli_stmt_bind_param(
            $stmtInsert,
            'ssdsiii',
            $row['dateEng'],
            $row['type_eng'],
            $row['montant'],
            $row['objet'],
            $row['idCompte'],
            $row['idFourn'],
            $idMarche
        );

        /*
         * |--------------------------------------------------------------------------
         * | Mise à jour du marché
         * |--------------------------------------------------------------------------
         */

        $sqlUpdate = "
            UPDATE sigm_marches
            SET statut = 'engager'
            WHERE id = ?
            AND statut = 'valide'
        ";

        $stmtUpdate = mysqli_prepare(
            $connexion,
            $sqlUpdate
        );

        if (!$stmtUpdate) {
            throw new Exception(
                'Erreur lors de la mise à jour du marché.'
            );
        }

        mysqli_stmt_bind_param(
            $stmtUpdate,
            'i',
            $idMarche
        );

        if (!mysqli_stmt_execute($stmtUpdate)) {
            throw new Exception(
                'Impossible de mettre à jour le statut du marché.'
            );
        }

        mysqli_stmt_close($stmtUpdate);

        /*
         * |--------------------------------------------------------------------------
         * | Supprimer le temporaire
         * |--------------------------------------------------------------------------
         */

        $sqlDelete = '
            DELETE FROM bud_engagements_temp
            WHERE idEng = ?
        ';

        $stmtDelete = mysqli_prepare(
            $connexion,
            $sqlDelete
        );

        if (!$stmtDelete) {
            throw new Exception(
                'Erreur lors de la suppression du temporaire.'
            );
        }

        mysqli_stmt_bind_param(
            $stmtDelete,
            'i',
            $idTemp
        );

        if (!mysqli_stmt_execute($stmtDelete)) {
            throw new Exception(
                "Impossible de supprimer l'engagement temporaire."
            );
        }

        mysqli_stmt_close($stmtDelete);

        /*
         * |--------------------------------------------------------------------------
         * | Valider la transaction
         * |--------------------------------------------------------------------------
         */

        mysqli_commit($connexion);

        header(
            'Location: liste_engs.php?success=2'
        );

        exit();
    } catch (Exception $e) {
        /*
         * |--------------------------------------------------------------------------
         * | Annulation de la transaction
         * |--------------------------------------------------------------------------
         */

        mysqli_rollback($connexion);

        header(
            'Location: liste_engs.php?error='
            . urlencode($e->getMessage())
        );

        exit();
    }
}

/*
 * |--------------------------------------------------------------------------
 * | SUPPRESSION D'UN ENGAGEMENT TEMPORAIRE
 * |--------------------------------------------------------------------------
 */

if (isset($_GET['supprTemp'])) {
    $suppr = (int) $_GET['supprTemp'];

    if ($suppr <= 0) {
        header(
            'Location: liste_engs.php?error='
            . urlencode('Identifiant invalide.')
        );

        exit();
    }

    $resultat = supprEngagementTemp($suppr);

    if ($resultat === true) {
        header(
            'Location: liste_engs.php?success=1'
        );
    } else {
        header(
            'Location: liste_engs.php?error='
            . urlencode($resultat)
        );
    }

    exit();
}

/*
 * |--------------------------------------------------------------------------
 * | SUPPRESSION D'UN ENGAGEMENT
 * |--------------------------------------------------------------------------
 */

if (isset($_GET['suppr'])) {
    $suppr = (int) $_GET['suppr'];

    if ($suppr <= 0) {
        header(
            'Location: liste_engs.php?error='
            . urlencode('Identifiant invalide.')
        );

        exit();
    }

    $resultat = supprEngagement($suppr);

    if ($resultat === true) {
        header(
            'Location: liste_engs.php?success=1'
        );
    } else {
        header(
            'Location: liste_engs.php?error='
            . urlencode($resultat)
        );
    }

    exit();
}

/*
 * |--------------------------------------------------------------------------
 * | Aucune action
 * |--------------------------------------------------------------------------
 */

header(
    'Location: liste_engs.php'
);

exit();

?>