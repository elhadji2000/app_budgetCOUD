<?php

/*
 * Connectez-vous à votre base de données MySQL
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* function connexionBD()
{
    $connexion = mysqli_connect('localhost', 'root', '', 'budget_db_' . $_SESSION['an']) or
        die('Serveur inaccessible. Merci de reessayer plus tard.');
    return $connexion;
} */

function connexionBD()
{
    $connexion = mysqli_connect('localhost', 'root', '', 'budget_db_2024') or
        die('Serveur inaccessible. Merci de reessayer plus tard.');
    return $connexion;
}

$connexion = connexionBD();

/*
 * Fonction de connexion dans l'espace utilisateur
 * ********************************************************************************
 */
function login($username, $password)
{
    global $connexion;
    $users = "SELECT * FROM `bud_users` where `log`='$username' and `mdp` =  '" . SHA1($password) . "' ";

    $info = $connexion->query($users);
    return $info->fetch_assoc();
}

/*
 * Fonction pour recuperer tous les ordres de recettes
 * ********************************************************************************
 */

/**
 * Récupérer tous les fournisseurs
 */
function getAllFournisseurs()
{
    global $connexion;

    if (!$connexion) {
        return [];
    }

    $sql = 'SELECT * FROM bud_fournisseur ORDER BY nom ASC';
    $result = mysqli_query($connexion, $sql);

    if (!$result) {
        return [];
    }

    $fournisseurs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $fournisseurs[] = $row;
    }

    return $fournisseurs;
}

/* function getFournisseurById($idFourn)
{
    global $connexion;
    $anneeEnCours = $_SESSION['an'];
    $idFourn = (int) $idFourn;

    $query = "SELECT *
              FROM bud_fournisseur
              WHERE idFourn = '$idFourn'
              LIMIT 1";

    $result = $connexion->query($query);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();  // Retourne une seule ligne sous forme de tableau associatif
    } else {
        return null;  // Ou false, selon ta logique
    }
} */

/**
 * Ajouter un nouveau fournisseur
 */
function ajouterFournisseur($numFourn, $nom, $adresse, $contact, $nature, $ninea, $rccm, $email, $observations)
{
    global $connexion;

    if (!$connexion) {
        return 'Erreur de connexion : ' . mysqli_connect_error();
    }

    // Échappement des données pour éviter les injections
    $numFourn = mysqli_real_escape_string($connexion, $numFourn);
    $nom = mysqli_real_escape_string($connexion, $nom);
    $adresse = mysqli_real_escape_string($connexion, $adresse);
    $contact = mysqli_real_escape_string($connexion, $contact);
    $nature = mysqli_real_escape_string($connexion, $nature);
    $ninea = mysqli_real_escape_string($connexion, $ninea);
    $rccm = mysqli_real_escape_string($connexion, $rccm);
    $email = mysqli_real_escape_string($connexion, $email);
    $observations = mysqli_real_escape_string($connexion, $observations);

    // Vérifie si le numéro fournisseur existe déjà
    $verif_sql = "SELECT idFourn FROM bud_fournisseur WHERE numFourn = '$numFourn'";
    $verif_res = mysqli_query($connexion, $verif_sql);

    if (mysqli_num_rows($verif_res) > 0) {
        return 'Ce numéro de fournisseur existe déjà.';
    }

    // Insertion avec les nouveaux champs
    $sql = "INSERT INTO bud_fournisseur (
                numFourn, 
                nom, 
                adresse, 
                contact, 
                nature, 
                ninea, 
                rccm, 
                email, 
                observations
            ) VALUES (
                '$numFourn', 
                '$nom', 
                '$adresse', 
                '$contact', 
                '$nature', 
                '$ninea', 
                '$rccm', 
                '$email', 
                '$observations'
            )";

    if (mysqli_query($connexion, $sql)) {
        return true;
    } else {
        return "Erreur lors de l'ajout : " . mysqli_error($connexion);
    }
}

/**
 * Modifier un fournisseur existant
 */
function modifierFournisseur($idFourn, $numFourn, $nom, $adresse, $contact, $nature, $ninea, $rccm, $email, $observations)
{
    global $connexion;

    if (!$connexion) {
        return 'Erreur de connexion : ' . mysqli_connect_error();
    }

    // Vérifier si le numéro fournisseur existe déjà (pour un autre enregistrement)
    $verif_sql = "SELECT idFourn FROM bud_fournisseur WHERE numFourn = '$numFourn' AND idFourn != $idFourn";
    $verif_res = mysqli_query($connexion, $verif_sql);

    if (mysqli_num_rows($verif_res) > 0) {
        return 'Ce numéro de fournisseur existe déjà.';
    }

    // Utilisation de requête préparée pour la sécurité
    $sql = 'UPDATE bud_fournisseur 
            SET numFourn = ?, 
                nom = ?, 
                adresse = ?, 
                contact = ?, 
                nature = ?, 
                ninea = ?, 
                rccm = ?, 
                email = ?, 
                observations = ? 
            WHERE idFourn = ?';

    $stmt = $connexion->prepare($sql);

    if (!$stmt) {
        return 'Erreur prepare : ' . $connexion->error;
    }

    $stmt->bind_param(
        'sssssssssi',  // 9 strings + 1 int
        $numFourn,
        $nom,
        $adresse,
        $contact,
        $nature,
        $ninea,
        $rccm,
        $email,
        $observations,
        $idFourn
    );

    if ($stmt->execute()) {
        return true;
    } else {
        return 'Erreur execution : ' . $stmt->error;
    }
}

/**
 * Récupérer un fournisseur par son ID (avec les nouveaux champs)
 */
function getFournisseurById($idFourn)
{
    global $connexion;

    if (!$connexion) {
        return null;
    }

    $sql = 'SELECT * FROM bud_fournisseur WHERE idFourn = ?';
    $stmt = $connexion->prepare($sql);

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param('i', $idFourn);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}

/*
 * Fonction pour recuperer les numeros de Compte
 * ********************************************************************************
 */
function getNumCompte()
{
    global $connexion;
    $query = 'SELECT idCompte, numCompte, libelle FROM `bud_compte` ORDER BY numCompte ASC';
    $result = $connexion->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

function getNumCompteSansInitiale()
{
    global $connexion;

    $query = "
        SELECT c.numCompte, c.idCompte , c.libelle
        FROM bud_compte c
        LEFT JOIN bud_dotations d ON c.idCompte = d.idCompte AND d.type = 'initiale'
        WHERE d.idDot IS NULL
        ORDER BY c.numCompte ASC
    ";

    $result = $connexion->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

function getCompteByNum($numCompte)
{
    global $connexion;
    $query = "SELECT numCompte, libelle, code FROM `bud_compte` WHERE numCompte='$numCompte'";
    $result = $connexion->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();  // Retourne une seule ligne sous forme de tableau associatif
    } else {
        return null;  // Si aucune ligne trouvée, retourner null
    }
}

/*
 * Fonction pour recuperer tous les comptes
 * ********************************************************************************
 */
function getAllCompte()
{
    global $connexion;
    $query = 'SELECT c.numCompte,c.code, c.libelle, cp.numCp, cp.nature FROM bud_compte As c JOIN bud_compteP As cp ON c.idCp=cp.idCp ORDER BY cp.numCp ASC';
    $result = $connexion->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

function getComptesDotations()
{
    global $connexion;
    $anneeEnCours = $_SESSION['an'];  // Récupère l'année en cours

    // Requête SQL pour récupérer les comptes déjà dotés et dont l'année de dotation est égale à l'année en cours
    $query = "
        SELECT c.numCompte, c.code, c.libelle, cp.numCp, cp.nature
        FROM bud_compte AS c
        JOIN bud_compteP AS cp ON c.idCp = cp.idCp
        WHERE EXISTS (
            SELECT 1 FROM bud_dotations d 
            WHERE d.idCompte = c.idCompte AND d.an = '$anneeEnCours'
            )
        ORDER BY cp.numCp ASC";

    $result = $connexion->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);  // Retourne les résultats sous forme de tableau associatif
    } else {
        return [];  // Si aucune ligne trouvée
    }
}

function getComptesDotationsByEng()
{
    global $connexion;
    $anneeEnCours = $_SESSION['an'];  // Récupère l'année en cours

    // Requête SQL pour récupérer les comptes déjà dotés et dont l'année de dotation est égale à l'année en cours
    $query = "
        SELECT c.numCompte, c.code, c.libelle, cp.numCp, cp.nature
        FROM bud_compte AS c
        JOIN bud_compteP AS cp ON c.idCp = cp.idCp
        WHERE EXISTS (
            SELECT 1 FROM bud_dotations d 
            WHERE d.idCompte = c.idCompte AND d.an = '$anneeEnCours'
            )
        AND (cp.nature = 'charge' OR cp.nature = 'emploi')
        ORDER BY cp.numCp ASC";

    $result = $connexion->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);  // Retourne les résultats sous forme de tableau associatif
    } else {
        return [];  // Si aucune ligne trouvée
    }
}

/*
 * Fonction pour recuperer tous les dotations
 * ********************************************************************************
 */
function getAllDotations()
{
    global $connexion;
    $anneeEnCours = $_SESSION['an'];
    $query = "
        SELECT 
            d.idDot,
            d.date,
            d.volume,
            d.dateSys,
            d.type,
            u.idUser,
            u.nom AS nomUser,
            u.log,
            u.email,
            c.idCompte,
            c.numCompte,
            c.libelle AS libelleCompte
        FROM bud_dotations d
        INNER JOIN bud_users u ON d.idUser = u.idUser
        INNER JOIN bud_compte c ON d.idCompte = c.idCompte
        WHERE d.an = '$anneeEnCours'
        ORDER BY d.dateSys DESC
    ";

    $result = $connexion->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

/*
 * Fonction pour recuperer tous les Utilisateurs
 * ********************************************************************************
 */
function getAllUsers()
{
    global $connexion;
    $query = 'SELECT * FROM `bud_users` ORDER BY date_sys DESC';
    $result = $connexion->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

function getAllFourniseurs()
{
    global $connexion;
    $query = 'SELECT * FROM `bud_fournisseur`';
    $result = $connexion->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

/*
 * Fonction pour recuperer les numero de compte pour enregistrer une ordre de paiement O.P
 * ********************************************************************************
 */
function getNumCompteForOp()
{
    global $connexion;
    $query = "SELECT DISTINCT bud_compte.idCompte, bud_compte.numCompte,bud_compte.libelle
        FROM bud_compte
        JOIN bud_comptep ON bud_compte.idCp = bud_comptep.idCp
        JOIN bud_engagements ON bud_compte.idCompte = bud_engagements.idCompte
        WHERE (bud_comptep.nature = 'charge' OR bud_comptep.nature = 'emploi')
        AND bud_engagements.idEng NOT IN (
            SELECT DISTINCT idEng FROM bud_operations WHERE idEng IS NOT NULL
        )
        ORDER BY bud_compte.numCompte ASC
    ";

    $result = $connexion->query($query);
    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

/*
 * Fonction pour recuperer les numero de compte pour enregistrer une ordre de recette O.R
 * ********************************************************************************
 */
function getNumCompteForOr()
{
    global $connexion;
    $anneeEnCours = $_SESSION['an'];

    $query = "SELECT DISTINCT  bud_compte.idCompte,  bud_compte.numCompte,  bud_compte.libelle
        FROM bud_compte
        JOIN bud_comptep ON bud_compte.idCp = bud_comptep.idCp
        WHERE (bud_comptep.nature = 'ressource' OR bud_comptep.nature = 'produit')
        AND EXISTS (
            SELECT 1 FROM bud_dotations d 
            WHERE d.idCompte = bud_compte.idCompte AND d.an = '$anneeEnCours'
            )
        ORDER BY bud_compte.numCompte ASC
    ";

    $result = $connexion->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

/*
 * Fonction pour recuperer les bud_engagements
 * ********************************************************************************
 */
function getEngs()
{
    global $connexion;
    // $anneeEnCours = date("Y"); // Récupère l'année en cours
    $anneeEnCours = $_SESSION['an'];

    $query = "SELECT c.numCompte,cp.numCp, cp.libelle as libelleCp, c.libelle as libelleC, eng.idEng, eng.dateEng, eng.objet, eng.type_eng, eng.montant, f.nom, f.numFourn 
            FROM bud_engagements as eng
            JOIN bud_compte as c ON c.idCompte = eng.idCompte 
            JOIN bud_comptep as cp ON c.idCp=cp.idCp 
            JOIN bud_fournisseur as f ON f.idFourn= eng.idFourn 
            WHERE EXISTS (
            SELECT 1 FROM bud_dotations d 
            WHERE d.idCompte = c.idCompte AND d.an = '$anneeEnCours'
            )
            ORDER BY eng.idEng DESC
            ";

    $result = $connexion->query($query);
    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

function getOperationsSansBordereau()
{
    global $connexion;

    $anneeEnCours = $_SESSION['an'];

    $query = "
        SELECT
            op.idOp,
            op.idEng,
            op.montant AS montantOperation,

            c.numCompte,
            cp.numCp,
            cp.libelle AS libelleCp,
            c.libelle AS libelleC,

            eng.dateEng,
            eng.objet,
            eng.type_eng,
            eng.montant AS montantEngagement,

            f.nom,
            f.numFourn

        FROM bud_operations AS op

        INNER JOIN bud_engagements AS eng
            ON eng.idEng = op.idEng

        INNER JOIN bud_compte AS c
            ON c.idCompte = eng.idCompte

        INNER JOIN bud_comptep AS cp
            ON cp.idCp = c.idCp

        INNER JOIN bud_fournisseur AS f
            ON f.idFourn = eng.idFourn

        WHERE EXISTS (
            SELECT 1
            FROM bud_dotations d
            WHERE d.idCompte = c.idCompte
              AND d.an = '$anneeEnCours'
        )

        AND op.idBordereau IS NULL

        ORDER BY op.idOp DESC
    ";

    $result = $connexion->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    return [];
}

function getEngagementsTemp()
{
    global $connexion;
    // $anneeEnCours = date("Y"); // Récupère l'année en cours
    $anneeEnCours = $_SESSION['an'];

    $query = "SELECT c.numCompte,cp.numCp, cp.libelle as libelleCp, c.libelle as libelleC, eng.idEng, eng.dateEng, eng.type_eng, eng.objet, eng.montant, f.nom, f.numFourn 
            FROM bud_engagements_temp as eng
            JOIN bud_compte as c ON c.idCompte = eng.idCompte 
            JOIN bud_comptep as cp ON c.idCp=cp.idCp 
            JOIN bud_fournisseur as f ON f.idFourn= eng.idFourn 
            WHERE EXISTS (
            SELECT 1 FROM bud_dotations d 
            WHERE d.idCompte = c.idCompte AND d.an = '$anneeEnCours'
            )
            ORDER BY eng.idEng DESC
            ";

    $result = $connexion->query($query);
    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

function getEngsByCompte($numCompte)
{
    global $connexion;
    $anneeEnCours = $_SESSION['an'];

    $query = "
        SELECT 
            c.numCompte,
            cp.numCp,
            op.numFact,
            cp.libelle AS libelleCp,
            c.libelle AS libelleC,
            eng.idEng,
            eng.dateEng,
            eng.type_eng,
            eng.objet,
            eng.montant,
            f.numFourn,
            f.nom,
            '$anneeEnCours' AS an
        FROM bud_engagements AS eng
        JOIN bud_compte AS c ON c.idCompte = eng.idCompte 
        JOIN bud_comptep AS cp ON c.idCp = cp.idCp 
        JOIN bud_fournisseur AS f ON f.idFourn = eng.idFourn 
        LEFT JOIN bud_operations AS op ON op.idEng = eng.idEng
        WHERE c.numCompte = '$numCompte' 
        AND EXISTS (
            SELECT 1 FROM bud_dotations d 
            WHERE d.idCompte = c.idCompte AND d.an = '$anneeEnCours'
        )
    ";

    $result = $connexion->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

function getEngsByCompteAndDate($numCompte, $date)
{
    global $connexion;
    $anneeEnCours = $_SESSION['an'];

    $query = "
        SELECT 
            c.numCompte,
            cp.numCp,
            op.numFact,
            cp.libelle AS libelleCp,
            c.libelle AS libelleC,
            eng.idEng,
            eng.dateEng,
            eng.type_eng,
            eng.objet,
            eng.montant,
            f.numFourn,
            f.nom,
            '$anneeEnCours' AS an
        FROM bud_engagements AS eng
        JOIN bud_compte AS c ON c.idCompte = eng.idCompte 
        JOIN bud_comptep AS cp ON c.idCp = cp.idCp 
        JOIN bud_fournisseur AS f ON f.idFourn = eng.idFourn 
        LEFT JOIN bud_operations AS op ON op.idEng = eng.idEng
        WHERE c.numCompte = '$numCompte' 
          AND eng.dateEng = '$date'
          AND EXISTS (
              SELECT 1 FROM bud_dotations d 
              WHERE d.idCompte = c.idCompte AND d.an = '$anneeEnCours'
          )
    ";

    $result = $connexion->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

function getEngsByCompteAndDate2($numCompte, $date1, $date2)
{
    global $connexion;
    $anneeEnCours = $_SESSION['an'];

    $query = "
        SELECT 
            c.numCompte,
            cp.numCp,
            op.numFact,
            cp.libelle AS libelleCp,
            c.libelle AS libelleC,
            eng.idEng,
            eng.dateEng,
            eng.type_eng,
            eng.objet,
            eng.bc,
            eng.montant,
            f.numFourn,
            f.nom,
            '$anneeEnCours' AS an
        FROM bud_engagements AS eng
        JOIN bud_compte AS c ON c.idCompte = eng.idCompte 
        JOIN bud_comptep AS cp ON c.idCp = cp.idCp 
        JOIN bud_fournisseur AS f ON f.idFourn = eng.idFourn 
        LEFT JOIN bud_operations AS op ON op.idEng = eng.idEng
        WHERE c.numCompte = '$numCompte' 
          AND eng.dateEng BETWEEN '$date1' AND '$date2'
          AND EXISTS (
              SELECT 1 FROM bud_dotations d 
              WHERE d.idCompte = c.idCompte AND d.an = '$anneeEnCours'
          )
    ";

    $result = $connexion->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

function getCompteEngsByDate($date)
{
    global $connexion;
    $anneeEnCours = $_SESSION['an'];

    $query = "
        SELECT 
            c.numCompte,
            cp.numCp,
            op.numFact,
            eng.dateEng,
            '$anneeEnCours' AS an
        FROM bud_engagements AS eng
        JOIN bud_compte AS c ON c.idCompte = eng.idCompte 
        JOIN bud_comptep AS cp ON c.idCp = cp.idCp 
        JOIN bud_fournisseur AS f ON f.idFourn = eng.idFourn 
        LEFT JOIN bud_operations AS op ON op.idEng = eng.idEng
        WHERE eng.dateEng = '$date'
          AND EXISTS (
              SELECT 1 FROM bud_dotations d 
              WHERE d.idCompte = c.idCompte AND d.an = '$anneeEnCours'
          )
        GROUP BY 
            c.numCompte, 
            cp.numCp
    ";

    $result = $connexion->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

function getCompteEngsByDate2($date1, $date2)
{
    global $connexion;
    $anneeEnCours = $_SESSION['an'];

    $query = "
       SELECT 
        c.numCompte,
        cp.numCp,
        MIN(op.numFact) AS numFact, -- on prend le premier numFact (ou n'importe lequel)
        MIN(eng.dateEng) AS dateEng, -- date minimale d'engagement (ou n'importe laquelle selon ton besoin)
        '$anneeEnCours' AS an
        FROM bud_engagements AS eng
            JOIN bud_compte AS c ON c.idCompte = eng.idCompte 
            JOIN bud_comptep AS cp ON c.idCp = cp.idCp 
            JOIN bud_fournisseur AS f ON f.idFourn = eng.idFourn 
            LEFT JOIN bud_operations AS op ON op.idEng = eng.idEng
        WHERE eng.dateEng BETWEEN '$date1' AND '$date2'
            AND EXISTS (
                    SELECT 1 FROM bud_dotations d 
                        WHERE d.idCompte = c.idCompte AND d.an = '$anneeEnCours'
                    )
        GROUP BY 
        c.numCompte, 
        cp.numCp

    ";

    $result = $connexion->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

function getEngById($idEng)
{
    global $connexion;
    $anneeEnCours = $_SESSION['an'];

    $query = "SELECT c.numCompte,cp.numCp, cp.libelle as libelleCp, c.libelle as libelleC, eng.idEng, eng.dateEng, eng.type_eng, eng.objet, eng.montant, 
                     f.numFourn, f.nom, d.an, op.numFact, op.dateOp, op.idOp
              FROM bud_engagements AS eng
              JOIN bud_compte AS c ON c.idCompte = eng.idCompte 
              JOIN bud_comptep AS cp ON c.idCp = cp.idCp 
              JOIN bud_dotations AS d ON c.idCompte = d.idCompte 
              JOIN bud_fournisseur AS f ON f.idFourn = eng.idFourn 
              LEFT JOIN bud_operations AS op ON eng.idEng=op.idEng
              WHERE d.an = '$anneeEnCours' AND eng.idEng = '$idEng'";

    $result = $connexion->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();  // ici on retourne UN seul engagement
    } else {
        return null;
    }
}

function getTotalPayeByEng($idEng)
{
    global $connexion;

    $query = "
        SELECT COALESCE(SUM(montant), 0) AS total
        FROM bud_operations
        WHERE idEng = '$idEng'
    ";

    $result = $connexion->query($query);

    if ($result && $row = $result->fetch_assoc()) {
        return (float) $row['total'];
    }

    return 0;
}

/*
 * Fonction pour recuperer les engagements a partir d'un numero de compte
 * ********************************************************************************
 */
function getEngsByNumCompte($numCompte)
{
    global $connexion;
    $anneeEnCours = $_SESSION['an'];

    $query = "SELECT bud_engagements.*,  bud_compte.*, comptep.*
              FROM bud_engagements
              JOIN bud_compte ON  bud_compte.idCompte = bud_engagements.idCompte 
              JOIN bud_comptep ON  bud_compte.idCp = comptep.idCp 
              WHERE  bud_compte.numCompte = '$numCompte'
                AND EXISTS (
                    SELECT 1 FROM bud_dotations 
                    WHERE bud_dotations.idCompte =  bud_compte.idCompte 
                      AND bud_dotations.an = '$anneeEnCours'
                )";

    $result = $connexion->query($query);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

function getEngsAvecPaiement($numCompte)
{
    global $connexion;
    $anneeEnCours = $_SESSION['an'];

    $query = "
        SELECT 
            e.idEng,
            e.montant,
            e.type_eng,
            COUNT(o.idOp) AS nb_paiements,
            COALESCE(SUM(o.montant), 0) AS total_paye
        FROM bud_engagements e
        JOIN bud_compte c ON c.idCompte = e.idCompte
        LEFT JOIN bud_operations o ON o.idEng = e.idEng
        WHERE c.numCompte = '$numCompte'
        AND EXISTS (
            SELECT 1 FROM bud_dotations d
            WHERE d.idCompte = c.idCompte
            AND d.an = '$anneeEnCours'
        )
        GROUP BY e.idEng

        HAVING total_paye < e.montant
    ";

    $result = $connexion->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

/* function getEngsByNumCompteNonOperation($numCompte)
{
    global $connexion;
    $anneeEnCours = $_SESSION['an'];

    $query = "
        SELECT bud_engagements.*
        FROM bud_engagements
        JOIN bud_compte ON  bud_compte.idCompte = bud_engagements.idCompte
        JOIN bud_comptep ON  bud_compte.idCp =  bud_comptep.idCp
        WHERE  bud_compte.numCompte = '$numCompte'
        AND bud_engagements.idEng NOT IN (
            SELECT idEng FROM bud_operations
        )
        AND EXISTS (
            SELECT 1 FROM bud_dotations
            WHERE bud_dotations.idCompte =  bud_compte.idCompte
              AND bud_dotations.an = '$anneeEnCours'
        );
    ";

    $result = $connexion->query($query);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
} */

function isEngagementUsed($idEng)
{
    global $connexion;
    $query = 'SELECT 1 FROM bud_operations WHERE idEng = ?';
    $stmt = $connexion->prepare($query);
    $stmt->bind_param('i', $idEng);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}

function isFournisseurUsed($idFournisseur)
{
    global $connexion;
    $query = 'SELECT 1 FROM bud_engagements WHERE idFourn = ?';
    $stmt = $connexion->prepare($query);
    $stmt->bind_param('i', $idFournisseur);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}

function isDotationUsed($idDotation)
{
    global $connexion;
    $query = '
        SELECT 1
        FROM bud_dotations d
        INNER JOIN bud_engagements e ON d.idCompte = e.idCompte
        WHERE d.idDot = ?
        LIMIT 1
    ';
    $stmt = $connexion->prepare($query);
    $stmt->bind_param('i', $idDotation);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}

function getRecettesTemp()
{
    global $connexion;

    $sql = 'SELECT 
                ort.idOr,
                ort.dateOr,
                ort.objet_recette,
                ort.montant,
                ort.pieces_annexees,
                ort.dateSys,

                f.nom,
                f.contact,
                f.numFourn,

                c.numCompte,
                c.libelle,
                c.code,

                u.nom AS nomUser,
                u.log AS logUser

            FROM bud_ordre_recette_temp ort

            LEFT JOIN bud_fournisseur f ON ort.idFourn = f.idFourn
            LEFT JOIN bud_compte c ON ort.idCompte = c.idCompte
            LEFT JOIN bud_users u ON ort.idUser = u.idUser

            ORDER BY ort.idOr DESC';

    $result = mysqli_query($connexion, $sql);

    if (!$result) {
        return [];
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getRecettes()
{
    global $connexion;

    $sql = 'SELECT 
                ort.idOr,
                ort.dateOr,
                ort.objet_recette,
                ort.montant,
                ort.pieces_annexees,
                ort.dateSys,

                f.nom,
                f.contact,
                f.numFourn,

                c.numCompte,
                c.libelle,
                c.code,

                u.nom AS nomUser,
                u.log AS logUser

            FROM bud_ordre_recette ort

            LEFT JOIN bud_fournisseur f ON ort.idFourn = f.idFourn
            LEFT JOIN bud_compte c ON ort.idCompte = c.idCompte
            LEFT JOIN bud_users u ON ort.idUser = u.idUser

            ORDER BY ort.idOr DESC';

    $result = mysqli_query($connexion, $sql);

    if (!$result) {
        return [];
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getRecettesById($id)
{
    global $connexion;

    $sql = "SELECT 
                ort.idOr,
                ort.dateOr,
                ort.objet_recette,
                ort.montant,
                ort.pieces_annexees,
                ort.dateSys,

                f.nom,
                f.contact,
                f.numFourn,

                c.numCompte,
                c.libelle,
                c.code,

                u.nom AS nomUser,
                u.log AS logUser

            FROM bud_ordre_recette ort

            LEFT JOIN bud_fournisseur f ON ort.idFourn = f.idFourn
            LEFT JOIN bud_compte c ON ort.idCompte = c.idCompte
            LEFT JOIN bud_users u ON ort.idUser = u.idUser
            WHERE ort.idOr = $id
            LIMIT 1";  // sécurise la requête et limite à une seule ligne

    $result = mysqli_query($connexion, $sql);

    if (!$result || mysqli_num_rows($result) === 0) {
        return null;  // ou [] si tu préfères
    }

    return mysqli_fetch_assoc($result);
}

/*
 * Fonction pour recuperer les bud_engagements a partir d'un numero de compte
 * ********************************************************************************
 */
function getOperationsByType($typeOp)
{
    global $connexion;
    $anneeEnCours = $_SESSION['an'];

    $query = "
        SELECT 
            o.idOp,
            o.dateOp,
            o.numFact,
            o.montant AS montant_op,
            e.montant,
            e.idEng,
            e.dateEng,
            e.objet,
            e.type_eng,
            f.numFourn,
            f.nom,
            c.numCompte
        FROM bud_operations o
        JOIN bud_engagements e ON o.idEng = e.idEng
        JOIN bud_compte c ON e.idCompte = c.idCompte
        JOIN bud_fournisseur f ON e.idFourn = f.idFourn
        WHERE o.typeOp = ?
        AND EXISTS (
            SELECT 1 FROM bud_dotations d
            WHERE d.idCompte = c.idCompte 
              AND d.an = '$anneeEnCours'
        )
        ORDER BY o.idOp DESC;
    ";

    $stmt = $connexion->prepare($query);
    $stmt->bind_param('s', $typeOp);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getOperationById($idOp)
{
    global $connexion;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $anneeEnCours = $_SESSION['an'];

    $query = '
        SELECT 
            o.idOp,
            o.dateOp,
            o.numFact,
            o.montant AS montant_op,
            o.typeOp,

            e.idEng,
            e.dateEng,
            e.objet,
            e.montant AS montant_eng,
            e.type_eng,

            f.idFourn,
            f.numFourn,
            f.nom,

            c.idCompte,
            c.numCompte,
            c.libelle AS libelleCompte,

            cp.numCp

        FROM bud_operations o
        JOIN bud_engagements e ON o.idEng = e.idEng
        JOIN bud_compte c ON e.idCompte = c.idCompte
        JOIN bud_comptep cp ON c.idCp = cp.idCp
        JOIN bud_fournisseur f ON e.idFourn = f.idFourn

        WHERE o.idOp = ?
        AND EXISTS (
            SELECT 1 FROM bud_dotations d
            WHERE d.idCompte = c.idCompte 
            AND d.an = ?
        )
        LIMIT 1
    ';

    $stmt = $connexion->prepare($query);
    $stmt->bind_param('is', $idOp, $anneeEnCours);
    $stmt->execute();

    $result = $stmt->get_result();

    return ($result->num_rows > 0)
        ? $result->fetch_assoc()
        : null;
}

function getOperationsTemp($typeOp)
{
    global $connexion;
    $anneeEnCours = $_SESSION['an'];

    $query = "
        SELECT 
            o.idOp,
            o.dateOp,
            o.numFact,
            o.montant AS montant_op,
            e.montant,
            e.idEng,
            e.dateEng,
            e.objet,
            e.type_eng,
            f.numFourn,
            f.nom,
            c.numCompte
        FROM bud_operations_temp o
        JOIN bud_engagements e ON o.idEng = e.idEng
        JOIN bud_compte c ON e.idCompte = c.idCompte
        JOIN bud_fournisseur f ON e.idFourn = f.idFourn
        WHERE o.typeOp = ?
        AND EXISTS (
            SELECT 1 FROM bud_dotations d
            WHERE d.idCompte = c.idCompte 
              AND d.an = '$anneeEnCours'
        )
        ORDER BY o.idOp DESC;
    ";

    $stmt = $connexion->prepare($query);
    $stmt->bind_param('s', $typeOp);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

/*
 * #########    FONCTION POUR DOTATION ... ##############3
 * ********************************************************************************
 */

/*
 * function enregistrerDotation($idCompte, $date, $volume, $type)
 * {
 *     // Démarre la session si ce n'est pas déjà fait
 *     if (session_status() === PHP_SESSION_NONE) {
 *         session_start();
 *     }
 *     // Connexion MySQLi
 *     global $connexion;
 *
 *     if (!$connexion) {
 *         return 'Connexion échouée : ' . mysqli_connect_error();
 *     }
 *
 *     // Vérifie que l'utilisateur est connecté
 *     if (!isset($_SESSION['idUser'])) {
 *         return 'Utilisateur non connecté.';
 *     }
 *
 *     $idUser = (int) $_SESSION['idUser'];
 *     $an = (int) $_SESSION['an'];
 *
 *     // Validation : montant non négatif
 *     if ($type == 'initiale') {
 *         if ($volume < 0) {
 *             return 'Le volume ne peut pas être négatif.';
 *         }
 *     }
 *
 *     // Échappement des données
 *     $idCompte = mysqli_real_escape_string($connexion, $idCompte);
 *     $idCompte = (int) $idCompte;
 *     $date = mysqli_real_escape_string($connexion, $date);
 *     $volume = (int) $volume;
 *     $type = mysqli_real_escape_string($connexion, $type);
 *
 *     // Requête d'insertion
 *     $sql = "INSERT INTO bud_dotations (idCompte, date, an, volume, type, idUser)
 *             VALUES ($idCompte, '$date', '$an', $volume, '$type', $idUser)";
 *
 *     // Exécution
 *     if (mysqli_query($connexion, $sql)) {
 *         return true;
 *     } else {
 *         return "Erreur lors de l'enregistrement : " . mysqli_error($connexion);
 *     }
 * }
 */
function enregistrerDotation($idCompte, $date, $volume, $type)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    global $connexion;

    if (!$connexion) {
        return 'Connexion échouée : ' . mysqli_connect_error();
    }

    if (!isset($_SESSION['idUser'])) {
        return 'Utilisateur non connecté.';
    }

    $idUser = (int) $_SESSION['idUser'];
    $an = (int) $_SESSION['an'];

    $idCompte = (int) $idCompte;
    $volume = (int) $volume;
    $date = mysqli_real_escape_string($connexion, $date);
    $type = mysqli_real_escape_string($connexion, $type);

    //  Empêcher volume négatif
    if ($type == 'initiale' && $volume < 0) {
        return 'Le volume ne peut pas être négatif.';
    }

    // BLOQUER DOUBLE DOTATION INITIALE
    if ($type == 'initiale') {
        $checkSql = "SELECT idDot FROM bud_dotations 
                     WHERE idCompte = $idCompte 
                     AND an = $an 
                     AND type = 'initiale' 
                     LIMIT 1";

        $resultCheck = mysqli_query($connexion, $checkSql);

        if (mysqli_num_rows($resultCheck) > 0) {
            return "Une dotation initiale existe déjà pour ce compte pour l'année $an.";
        }
    }

    //  Insertion
    $sql = "INSERT INTO bud_dotations (idCompte, date, an, volume, type, idUser) 
            VALUES ($idCompte, '$date', '$an', $volume, '$type', $idUser)";

    if (mysqli_query($connexion, $sql)) {
        return true;
    } else {
        return "Erreur lors de l'enregistrement : " . mysqli_error($connexion);
    }
}

function supprDotation($suppr)
{
    // Connexion MySQLi
    global $connexion;

    if (!$connexion) {
        return 'Connexion échouée : ' . mysqli_connect_error();
    }
    $suppr = (int) $suppr;
    // Requête d'insertion
    $sql = " DELETE FROM bud_dotations WHERE idDot='$suppr';";

    // Exécution
    if (mysqli_query($connexion, $sql)) {
        return true;
    } else {
        return 'Erreur lors de la suppression : ' . mysqli_error($connexion);
    }
}

function supprEngagementTemp($suppr)
{
    // Connexion MySQLi
    global $connexion;

    if (!$connexion) {
        return 'Connexion échouée : ' . mysqli_connect_error();
    }
    $suppr = (int) $suppr;
    // Requête d'insertion
    $sql = " DELETE FROM bud_engagements_temp WHERE idEng='$suppr';";

    // Exécution
    if (mysqli_query($connexion, $sql)) {
        return true;
    } else {
        return 'Erreur lors de la suppression : ' . mysqli_error($connexion);
    }
}

function supprEngagement($suppr)
{
    // Connexion MySQLi
    global $connexion;

    if (!$connexion) {
        return 'Connexion échouée : ' . mysqli_connect_error();
    }
    $suppr = (int) $suppr;
    // Requête d'insertion
    $sql = " DELETE FROM bud_engagements WHERE idEng='$suppr';";

    // Exécution
    if (mysqli_query($connexion, $sql)) {
        return true;
    } else {
        return 'Erreur lors de la suppression : ' . mysqli_error($connexion);
    }
}

function supprOpTemp($suppr)
{
    // Connexion MySQLi
    global $connexion;

    if (!$connexion) {
        return 'Connexion échouée : ' . mysqli_connect_error();
    }
    $suppr = (int) $suppr;
    // Requête d'insertion
    $sql = " DELETE FROM bud_operations_temp WHERE idOp='$suppr';";

    // Exécution
    if (mysqli_query($connexion, $sql)) {
        return true;
    } else {
        return 'Erreur lors de la suppression : ' . mysqli_error($connexion);
    }
}

function supprOp($suppr)
{
    // Connexion MySQLi
    global $connexion;

    if (!$connexion) {
        return 'Connexion échouée : ' . mysqli_connect_error();
    }
    $suppr = (int) $suppr;
    // Requête d'insertion
    $sql = " DELETE FROM bud_operations WHERE idOp='$suppr';";

    // Exécution
    if (mysqli_query($connexion, $sql)) {
        return true;
    } else {
        return 'Erreur lors de la suppression : ' . mysqli_error($connexion);
    }
}

function getIdCompteByNum($numCompte)
{
    global $connexion;

    if (!$connexion) {
        return 'Connexion échouée : ' . mysqli_connect_error();
    }

    // Échappement du numéro de compte
    $numCompte = mysqli_real_escape_string($connexion, $numCompte);

    // Requête
    $sql = "SELECT idCompte FROM bud_compte WHERE numCompte = '$numCompte' LIMIT 1";
    $result = mysqli_query($connexion, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['idCompte'];  // Retourne l'identifiant
    } else {
        return null;  // Aucun compte trouvé
    }
}

function getDetailsCompte($numCompte)
{
    global $connexion;

    $numCompte = mysqli_real_escape_string($connexion, $numCompte);

    // ================= RECUP ID + NATURE =================
    $sqlInfo = "SELECT c.idCompte, cp.nature
                FROM bud_compte c
                LEFT JOIN bud_comptep cp ON c.numCompte = cp.numcp
                WHERE c.numCompte = '$numCompte'
                LIMIT 1";

    $resInfo = mysqli_query($connexion, $sqlInfo);

    if (!$resInfo || mysqli_num_rows($resInfo) == 0) {
        return 'Compte introuvable.';
    }

    $info = mysqli_fetch_assoc($resInfo);
    $idCompte = $info['idCompte'];
    $nature = strtolower($info['nature'] ?? '');

    // ================= bud_dotations =================
    $sqlDot = "SELECT 
                    SUM(CASE WHEN type = 'initiale' THEN volume ELSE 0 END) AS initiale,
                    SUM(CASE WHEN type = 'remanier' THEN volume ELSE 0 END) AS remaniee
               FROM bud_dotations
               WHERE idCompte = $idCompte";

    $resDot = mysqli_query($connexion, $sqlDot);
    $dot = mysqli_fetch_assoc($resDot);

    $initiale = (int) ($dot['initiale'] ?? 0);
    $remaniee = (int) ($dot['remaniee'] ?? 0);
    $dotationTotale = $initiale + $remaniee;

    // ================= bud_engagements =================
    $sqlEng = "SELECT SUM(montant) AS total 
               FROM bud_engagements 
               WHERE idCompte = $idCompte";

    $engagement = (int) (mysqli_fetch_assoc(mysqli_query($connexion, $sqlEng))['total'] ?? 0);

    // ================= DEPENSES =================
    $sqlDep = "SELECT SUM(o.montant) AS total 
               FROM bud_operations o
               INNER JOIN bud_engagements e ON o.idEng = e.idEng
               WHERE e.idCompte = $idCompte";

    $depenses = (int) (mysqli_fetch_assoc(mysqli_query($connexion, $sqlDep))['total'] ?? 0);

    // ================= RECETTES =================
    $sqlRec = "SELECT SUM(montant) AS total 
               FROM bud_ordre_recette 
               WHERE idCompte = $idCompte";

    $recettes = (int) (mysqli_fetch_assoc(mysqli_query($connexion, $sqlRec))['total'] ?? 0);

    // ================= LOGIQUE METIER =================

    // Déterminer le type du compte
    $type = 'inconnu';

    if (in_array($nature, ['charge', 'emploi'])) {
        $type = 'depense';
        $solde = $dotationTotale - $depenses;
    } elseif (in_array($nature, ['produit', 'ressource'])) {
        $type = 'recette';
        $solde = $recettes;
    } else {
        $solde = 0;
    }

    return [
        'nature' => $nature,
        'type' => $type,
        'dotationInitiale' => $initiale,
        'dotationRemaniee' => $remaniee,
        'dotationTotale' => $dotationTotale,
        'totalEngagement' => $engagement,
        'totalDepense' => $depenses,
        'totalRecette' => $recettes,
        'solde' => $solde
    ];
}

/* function ajouterFournisseur($numFourn, $nom, $adresse, $contact, $nature)
{
    global $connexion;

    if (!$connexion) {
        return 'Erreur de connexion : ' . mysqli_connect_error();
    }

    // Échappement des données pour éviter les injections
    $numFourn = mysqli_real_escape_string($connexion, $numFourn);
    $nom = mysqli_real_escape_string($connexion, $nom);
    $adresse = mysqli_real_escape_string($connexion, $adresse);
    $contact = mysqli_real_escape_string($connexion, $contact);
    $nature = mysqli_real_escape_string($connexion, $nature);

    // Vérifie si le numéro fournisseur existe déj�
    $verif_sql = "SELECT idFourn FROM bud_fournisseur WHERE numFourn = '$numFourn'";
    $verif_res = mysqli_query($connexion, $verif_sql);

    if (mysqli_num_rows($verif_res) > 0) {
        return 'Ce numéro de bud_fournisseur existe déjà.';
    }

    // Insertion
    $sql = "INSERT INTO bud_fournisseur (numFourn, nom, adresse, contact, nature)
            VALUES ('$numFourn', '$nom', '$adresse', '$contact', '$nature')";

    if (mysqli_query($connexion, $sql)) {
        return true;
    } else {
        return "Erreur lors de l'ajout : " . mysqli_error($connexion);
    }
} */

function ajouterEngagement_temp($dateEng, $type_eng, $montant, $objet, $idCompte, $idFourn)
{
    global $connexion;

    if (!$connexion) {
        return 'Erreur de connexion : ' . mysqli_connect_error();
    }

    // Sécurité de base
    if ($montant < 0) {
        return 'Le montant ne peut pas être négatif.';
    }

    // Récupération de l'idUser depuis la session
    if (!isset($_SESSION['idUser'])) {
        return 'Utilisateur non identifié.';
    }
    $idUser = $_SESSION['idUser'];

    // Échappement des données
    $dateEng = mysqli_real_escape_string($connexion, $dateEng);
    $type_eng = mysqli_real_escape_string($connexion, $type_eng);
    $objet = mysqli_real_escape_string($connexion, $objet);
    $idCompte = (int) $idCompte;
    $idFourn = (int) $idFourn;
    $montant = (float) $montant;

    // Requête d'insertion
    $sql = "INSERT INTO bud_engagements_temp (dateEng, type_eng, montant, objet, idCompte, idFourn, idUser) 
            VALUES ('$dateEng', '$type_eng', $montant, '$objet', $idCompte, $idFourn, $idUser)";

    if (mysqli_query($connexion, $sql)) {
        return true;
    } else {
        return "Erreur lors de l'ajout : " . mysqli_error($connexion);
    }
}

function ajouter_Ordre_Recette_temp($dateOr, $objet_recette, $montant, $pieces_annexees, $idCompte, $idFourn)
{
    global $connexion;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!$connexion) {
        return 'Erreur de connexion : ' . mysqli_connect_error();
    }
    // Sécurité de base
    if ($montant < 0) {
        return 'Le montant ne peut pas être négatif.';
    }
    // Récupération de l'idUser depuis la session
    if (!isset($_SESSION['idUser'])) {
        return 'Utilisateur non identifié.';
    }
    $idUser = (int) $_SESSION['idUser'];
    // Échappement des données
    $dateOr = mysqli_real_escape_string($connexion, $dateOr);
    $objet_recette = mysqli_real_escape_string($connexion, $objet_recette);
    $pieces_annexees = mysqli_real_escape_string($connexion, $pieces_annexees);
    $idCompte = (int) $idCompte;
    $idFourn = (int) $idFourn;
    $montant = (float) $montant;
    // Requête d'insertion
    $sql = "INSERT INTO bud_ordre_recette_temp(dateOr, objet_recette, montant, pieces_annexees, idCompte, idFourn, idUser)
            VALUES ('$dateOr', '$objet_recette', $montant, '$pieces_annexees', $idCompte, $idFourn, $idUser)";

    if (mysqli_query($connexion, $sql)) {
        return true;
    } else {
        return "Erreur lors de l'ajout : " . mysqli_error($connexion);
    }
}

function ajouterOp_temp($dateOp, $idEng, $numFact, $montant, $typeOp)
{
    global $connexion;

    // Sécurisation des données
    $dateOp = mysqli_real_escape_string($connexion, $dateOp);
    $idEng = (int) $idEng;
    $numFact = mysqli_real_escape_string($connexion, $numFact);
    $typeOp = mysqli_real_escape_string($connexion, $typeOp);
    $montant = (float) $montant;

    // Requête d'insertion
    $query = "
        INSERT INTO bud_operations_temp (dateOp, idEng, numFact, montant, typeOp)
        VALUES ('$dateOp', $idEng, '$numFact', '$montant', '$typeOp')
    ";

    // Exécution
    if ($connexion->query($query)) {
        return true;
    } else {
        return "Erreur lors de l'ajout de l'opération : " . $connexion->error;
    }
}

function getPasswordHashByUserId($userId)
{
    global $connexion;

    $userId = (int) $userId;  // Sécurisation de l'ID (casting entier)

    $sql = "SELECT mdp FROM bud_users WHERE idUser = $userId";
    $result = mysqli_query($connexion, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['mdp'];
    }

    return null;  // En cas d'échec ou d'utilisateur non trouvé
}

function updateUserPassword($id, $newHash)
{
    global $connexion;

    // Attention à bien échapper les variables pour éviter les injections SQL
    $id = (int) $id;
    $newHash = mysqli_real_escape_string($connexion, $newHash);
    $type_mdp = mysqli_real_escape_string($connexion, 'updated');

    $sql = "UPDATE bud_users SET mdp = '$newHash', type_mdp = '$type_mdp' WHERE idUser = $id";
    mysqli_query($connexion, $sql);
}

function getExecution_1()
{
    global $connexion;

    $anneeEnCours = $_SESSION['an'];

    $query = "SELECT 
        cp.idCp, 
        cp.numCp, 
        cp.libelle,
        cp.nature AS typeCp,

        COALESCE(d.totalDotations, 0) AS totalDotations,
        COALESCE(d.totalDotInitial, 0) AS totalDotInitial,
        COALESCE(d.totalDotRemanier, 0) AS totalDotRemanier,

        COALESCE(e.totalEngs, 0) AS totalEngs,
        COALESCE(r.totalRecettes, 0) AS totalRecettes,

        CASE 
            WHEN COALESCE(d.totalDotations, 0) = 0 THEN 0
            ELSE ROUND(
                COALESCE(e.totalEngs, 0) * 100.0 / COALESCE(d.totalDotations, 0),
                2
            )
        END AS tauxDepense,

        CASE 
            WHEN COALESCE(d.totalDotations, 0) = 0 THEN 0
            ELSE ROUND(
                COALESCE(r.totalRecettes, 0) * 100.0 / COALESCE(d.totalDotations, 0),
                2
            )
        END AS tauxRecette

    FROM bud_comptep cp

    /* DOTATIONS */
    JOIN (
        SELECT 
            c.idCp, 
            SUM(d.volume) AS totalDotations,
            SUM(CASE WHEN d.type = 'initiale' THEN d.volume ELSE 0 END) AS totalDotInitial,
            SUM(CASE WHEN d.type = 'remanier' THEN d.volume ELSE 0 END) AS totalDotRemanier
        FROM bud_compte c
        JOIN bud_dotations d ON d.idCompte = c.idCompte
        WHERE d.an = '$anneeEnCours'
        GROUP BY c.idCp
    ) d ON d.idCp = cp.idCp

    /* ENGAGEMENTS (DEPENSES) */
    LEFT JOIN (
        SELECT 
            c.idCp, 
            SUM(eng.montant) AS totalEngs
        FROM bud_compte c
        JOIN bud_engagements eng ON eng.idCompte = c.idCompte
        GROUP BY c.idCp
    ) e ON e.idCp = cp.idCp

    /* ORDRES DE RECETTE */
    LEFT JOIN (
        SELECT 
            c.idCp, 
            SUM(orx.montant) AS totalRecettes
        FROM bud_compte c
        JOIN bud_ordre_recette orx ON orx.idCompte = c.idCompte
        GROUP BY c.idCp
    ) r ON r.idCp = cp.idCp
    ";

    return mysqli_query($connexion, $query);
}

function getExecution($type = 'all')
{
    global $connexion;

    $anneeEnCours = $_SESSION['an'];

    //  filtre type compte
    $conditionType = '';

    if ($type === 'recette') {
        $conditionType = "WHERE cp.nature IN ('produit', 'ressource')";
    } elseif ($type === 'depense') {
        $conditionType = "WHERE cp.nature IN ('charge', 'emploi')";
    }

    $query = "SELECT 
        cp.idCp, 
        cp.numCp, 
        cp.libelle,
        cp.nature,

        COALESCE(d.totalDotations, 0) AS totalDotations,
        COALESCE(d.totalDotInitial, 0) AS totalDotInitial,
        COALESCE(d.totalDotRemanier, 0) AS totalDotRemanier,
        COALESCE(e.totalEngs, 0) AS totalEngs,

        CASE 
            WHEN COALESCE(d.totalDotations, 0) = 0 THEN 0
            ELSE ROUND(
                COALESCE(e.totalEngs, 0) * 100 / COALESCE(d.totalDotations, 0),
                2
            )
        END AS taux

    FROM bud_comptep cp

    LEFT JOIN (
        SELECT 
            c.idCp, 
            SUM(d.volume) AS totalDotations,
            SUM(CASE WHEN d.type = 'initiale' THEN d.volume ELSE 0 END) AS totalDotInitial,
            SUM(CASE WHEN d.type = 'remanier' THEN d.volume ELSE 0 END) AS totalDotRemanier
        FROM bud_compte c
        JOIN bud_dotations d ON d.idCompte = c.idCompte
        WHERE d.an = '$anneeEnCours'
        GROUP BY c.idCp
    ) d ON d.idCp = cp.idCp

    LEFT JOIN (
        SELECT 
            c.idCp, 
            SUM(eng.montant) AS totalEngs
        FROM bud_compte c
        JOIN bud_engagements eng ON eng.idCompte = c.idCompte
        GROUP BY c.idCp
    ) e ON e.idCp = cp.idCp

    $conditionType
    ORDER BY cp.numCp ASC";

    $result = $connexion->query($query);

    return ($result && $result->num_rows > 0)
        ? $result->fetch_all(MYSQLI_ASSOC)
        : [];
}

function getExecutionOp_1()
{
    global $connexion;
    // $anneeEnCours = date("Y"); // Récupère l'année en cours
    $anneeEnCours = $_SESSION['an'];

    $query = "SELECT 
    cp.idCp, 
    cp.numCp, 
    cp.libelle,
    COALESCE(d.totalDotations, 0) AS totalDotations,
    COALESCE(d.totalDotInitial, 0) AS totalDotInitial,
    COALESCE(d.totalDotRemanier, 0) AS totalDotRemanier,
    COALESCE(e.totalEngs, 0) AS totalEngs,
    COALESCE(f.totalOp, 0) AS totalOp,
    COALESCE(
    CASE 
        WHEN COALESCE(d.totalDotations, 0) = 0 THEN 0
        ELSE ROUND(
            COALESCE(e.totalEngs, 0) * 100.0 / COALESCE(d.totalDotations, 0),
            2
        )
    END,
0) AS taux
FROM bud_comptep cp
JOIN (
    SELECT 
        c.idCp, 
        SUM(d.volume) AS totalDotations,
        SUM(CASE WHEN d.type = 'initiale' THEN d.volume ELSE 0 END) AS totalDotInitial,
        SUM(CASE WHEN d.type = 'remanier' THEN d.volume ELSE 0 END) AS totalDotRemanier
    FROM bud_compte c
    JOIN bud_dotations d ON d.idCompte = c.idCompte
    WHERE d.an = '$anneeEnCours'
    GROUP BY c.idCp
) d ON d.idCp = cp.idCp
JOIN (
    SELECT 
        c.idCp, 
        SUM(eng.montant) AS totalEngs
    FROM bud_compte c
    JOIN bud_engagements eng ON eng.idCompte = c.idCompte
    GROUP BY c.idCp
) e ON e.idCp = cp.idCp
LEFT JOIN (
    SELECT 
        c.idCp,
        SUM(eng.montant) AS totalOp
    FROM bud_operations op
    JOIN bud_engagements eng ON eng.idEng = op.idEng
    JOIN bud_compte c ON c.idCompte = eng.idCompte
    GROUP BY c.idCp
) f ON f.idCp = cp.idCp
";

    $result = $connexion->query($query);
    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

function getExecution_2($idCp)
{
    global $connexion;
    $anneeEnCours = $_SESSION['an'];
    $idCp = (int) $idCp;

    $query = "
    SELECT 
        cp.idCp, 
        cp.numCp, 
        cp.libelle,

        c.idCompte,
        c.numCompte,
        c.libelle AS libelleC,
        cp.nature AS typeCompte,

        COALESCE(d.totalDotations, 0) AS totalDotations,
        COALESCE(d.totalDotInitial, 0) AS totalDotInitial,
        COALESCE(d.totalDotRemanier, 0) AS totalDotRemanier,

        COALESCE(e.totalEngs, 0) AS totalEngs,
        COALESCE(r.totalRecettes, 0) AS totalRecettes,
        COALESCE(o.totalOp, 0) AS totalOp

    FROM bud_comptep cp

    JOIN bud_compte c ON c.idCp = cp.idCp

    /* DOTATIONS */
    LEFT JOIN (
        SELECT 
            d.idCompte, 
            SUM(d.volume) AS totalDotations,
            SUM(CASE WHEN d.type = 'initiale' THEN d.volume ELSE 0 END) AS totalDotInitial,
            SUM(CASE WHEN d.type = 'remanier' THEN d.volume ELSE 0 END) AS totalDotRemanier
        FROM bud_dotations d
        WHERE d.an = '$anneeEnCours'
        GROUP BY d.idCompte
    ) d ON d.idCompte = c.idCompte

    /* bud_engagements */
    LEFT JOIN (
        SELECT 
            eng.idCompte, 
            SUM(eng.montant) AS totalEngs
        FROM bud_engagements eng
        GROUP BY eng.idCompte
    ) e ON e.idCompte = c.idCompte

    /* ORDRES DE RECETTES */
    LEFT JOIN (
        SELECT 
            idCompte,
            SUM(montant) AS totalRecettes
        FROM bud_ordre_recette
        GROUP BY idCompte
    ) r ON r.idCompte = c.idCompte

    /* OPERATIONS */
    LEFT JOIN (
        SELECT 
            eng.idCompte,
            SUM(op.montant) AS totalOp
        FROM bud_operations op
        JOIN bud_engagements eng ON eng.idEng = op.idEng
        GROUP BY eng.idCompte
    ) o ON o.idCompte = c.idCompte

    WHERE c.idCp = '$idCp'
    ORDER BY totalDotations DESC
    ";

    $result = $connexion->query($query);

    return ($result && $result->num_rows > 0)
        ? $result->fetch_all(MYSQLI_ASSOC)
        : [];
}

function get_produits_administratif()
{
    global $connexion;
    $anneeEnCours = $_SESSION['an'];

    $query = "
        SELECT 
            cp.idCp,
            cp.libelle,
            cp.numCp,
            cp.type,

           COALESCE(SUM(DISTINCT d.volume), 0) AS total_dot,
           COALESCE(SUM(DISTINCT r.montant), 0) AS total_paye,
           COALESCE(SUM(DISTINCT CASE 
                WHEN d.type = 'remanier' THEN d.volume 
            END), 0) AS totalDotRemanier,
           COALESCE(SUM(DISTINCT CASE 
                WHEN d.type = 'initiale' THEN d.volume 
            END), 0) AS totalDotInitiale

        FROM bud_compte c
        JOIN bud_comptep cp ON c.idCp = cp.idCp

        LEFT JOIN bud_dotations d 
            ON d.idCompte = c.idCompte 
            AND d.an = '$anneeEnCours'

        LEFT JOIN bud_ordre_recette r 
            ON r.idCompte = c.idCompte 

        WHERE cp.nature IN ('produit', 'ressource')

        GROUP BY cp.idCp, cp.libelle, cp.numCp
    ";

    $result = $connexion->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

function get_charges_administratif()
{
    global $connexion;
    $anneeEnCours = $_SESSION['an'];

    $query = "
        SELECT 
            cp.idCp,
            cp.libelle,
            cp.numCp,
            cp.type,

            COALESCE(SUM(DISTINCT d.volume), 0) AS total_dot,
            COALESCE(SUM(DISTINCT eng.montant), 0) AS total_eng,
            COALESCE(SUM(DISTINCT op.montant), 0) AS total_paye,
             COALESCE(SUM(DISTINCT CASE 
                WHEN d.type = 'remanier' THEN d.volume 
            END), 0) AS totalDotRemanier,
           COALESCE(SUM(DISTINCT CASE 
                WHEN d.type = 'initiale' THEN d.volume 
            END), 0) AS totalDotInitiale

        FROM bud_compte c
        JOIN bud_comptep cp ON c.idCp = cp.idCp

        LEFT JOIN bud_dotations d 
            ON d.idCompte = c.idCompte 
            AND d.an = '$anneeEnCours'
        LEFT JOIN bud_engagements eng 
            ON eng.idCompte = c.idCompte 

        LEFT JOIN bud_operations op 
            ON op.idEng = eng.idEng 

        WHERE cp.nature IN ('charge', 'emploi')

        GROUP BY cp.idCp, cp.libelle, cp.numCp
    ";

    $result = $connexion->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

function get_resultats()
{
    global $connexion;
    $anneeEnCours = $_SESSION['an'];

    $query = "
        SELECT 
            cp.libelle,
            cp.nature,
            cp.type,

            COALESCE(SUM(DISTINCT d.volume), 0) AS total_dot

        FROM bud_compte c
        JOIN bud_comptep cp ON c.idCp = cp.idCp

        LEFT JOIN bud_dotations d 
            ON d.idCompte = c.idCompte 
            AND d.an = '$anneeEnCours'

        WHERE cp.nature IN ('produit', 'ressource', 'charge', 'emploi')

        GROUP BY cp.idCp, cp.libelle, cp.nature
        ORDER BY cp.nature, cp.libelle
    ";

    $result = $connexion->query($query);

    $produits = [];
    $charges = [];

    if ($result && $result->num_rows > 0) {
        foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
            if (in_array($row['nature'], ['produit', 'ressource'])) {
                $produits[] = $row;
            } else {
                $charges[] = $row;
            }
        }
    }

    return [
        'produits' => $produits,
        'charges' => $charges
    ];
}

function getComptePById($idCp)
{
    global $connexion;
    $anneeEnCours = $_SESSION['an'];
    $idCp = (int) $idCp;

    $query = "SELECT cp.idCp, cp.numCp, cp.libelle 
              FROM bud_comptep cp 
              WHERE cp.idCp = '$idCp' 
              LIMIT 1";

    $result = $connexion->query($query);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();  // Retourne une seule ligne sous forme de tableau associatif
    } else {
        return null;  // Ou false, selon ta logique
    }
}

function sommeDot()
{
    global $connexion;

    $anneeEnCours = $_SESSION['an'];

    $sql = "SELECT COALESCE(SUM(d.volume),0) as totalDotations FROM bud_dotations d WHERE d.an='$anneeEnCours'";
    $result = mysqli_query($connexion, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['totalDotations'];
    }

    return null;  // En cas d'échec ou d'utilisateur non trouvé
}

function sommeDotByCompte($numCompte)
{
    global $connexion;

    $anneeEnCours = $_SESSION['an'];

    $sql = "SELECT COALESCE(SUM(d.volume),0) as totalDotations, c.numCompte 
            FROM bud_dotations d 
            JOIN bud_compte c ON d.idCompte=c.idCompte
            WHERE d.an='$anneeEnCours' AND c.numCompte='$numCompte'";
    $result = mysqli_query($connexion, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['totalDotations'];
    }

    return null;  // En cas d'échec ou d'utilisateur non trouvé
}

function sommeEngs()
{
    global $connexion;

    $anneeEnCours = $_SESSION['an'];

    $sql = "SELECT COALESCE(SUM(engs.montant),0) as totalEngs FROM bud_engagements engs 
            JOIN bud_compte c ON c.idCompte=engs.idCompte
            AND EXISTS (
            SELECT 1 FROM bud_dotations d
            WHERE d.idCompte = c.idCompte 
              AND d.an = '$anneeEnCours'
        )
            ";
    $result = mysqli_query($connexion, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['totalEngs'];
    }

    return null;  // En cas d'échec ou d'utilisateur non trouvé
}

function ajouterUtilisateur($nom, $login, $email, $privilege, $telephone, $sexe)
{
    global $connexion;

    // Échapper les entrées (à remplacer par des requêtes préparées pour plus de sécurité)
    $nom = $connexion->real_escape_string($nom);
    $login = $connexion->real_escape_string($login);
    $email = $connexion->real_escape_string($email);
    $privilege = $connexion->real_escape_string($privilege);
    $telephone = $connexion->real_escape_string($telephone);
    $sexe = $connexion->real_escape_string($sexe);
    $password = SHA1('coud2025');
    $type_mdp = 'default';

    // Vérifie si le login existe déjà
    $checkQuery = "SELECT idUser FROM bud_users WHERE log = '$login'";
    $checkResult = $connexion->query($checkQuery);

    if ($checkResult && $checkResult->num_rows > 0) {
        return [
            'success' => false,
            'message' => 'Ce login existe déjà.'
        ];
    }
    $an = $_SESSION['an'];
    // Insérer l'utilisateur
    $insertQuery = "INSERT INTO bud_users (nom, log, email, priv, mdp, type_mdp, telephone, sexe, an) 
                    VALUES ('$nom', '$login', '$email', '$privilege', '$password', '$type_mdp', '$telephone', '$sexe', '$an')";

    if ($connexion->query($insertQuery)) {
        return [
            'success' => true,
            'message' => 'Utilisateur ajouté avec succès.'
        ];
    } else {
        return [
            'success' => false,
            'message' => "Erreur lors de l'ajout de l'utilisateur."
        ];
    }
}

function formatNumEng($idEng)
{
    // Vérifie que la session est démarrée
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Vérifie que l'année est bien dans la session
    if (!isset($_SESSION['an'])) {
        return 'Erreur: année non définie.';
    }

    $an = $_SESSION['an'];
    $deuxDerniersChiffres = substr($an, -2);
    $idFormate = str_pad($idEng, 4, '0', STR_PAD_LEFT);

    return 'BE' . $deuxDerniersChiffres . '-' . $idFormate;
}

function formatNumOP($idOp)
{
    // Vérifie que la session est démarrée
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Vérifie que l'année est bien dans la session
    if (!isset($_SESSION['an'])) {
        return 'Erreur: année non définie.';
    }

    $an = $_SESSION['an'];
    $deuxDerniersChiffres = substr($an, -2);
    $idFormate = str_pad($idOp, 3, '0', STR_PAD_LEFT);

    return 'MD' . $deuxDerniersChiffres . '-' . $idFormate;
}

function formatNumOr($idOr)
{
    // Vérifie que la session est démarrée
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Vérifie que l'année est bien dans la session
    if (!isset($_SESSION['an'])) {
        return 'Erreur: année non définie.';
    }

    $an = $_SESSION['an'];
    $deuxDerniersChiffres = substr($an, -2);
    $idFormate = str_pad($idOr, 4, '0', STR_PAD_LEFT);

    return 'OR' . $deuxDerniersChiffres . '-' . $idFormate;
}

function nombreEnLettres($nombre)
{
    $fmt = new \NumberFormatter('fr_FR', \NumberFormatter::SPELLOUT);
    $lettres = $fmt->format($nombre);
    return ucfirst($lettres);
}

function supprimerLigne($table, $champId, $valeur)
{
    global $connexion;

    // Sécurisation basique
    $table = mysqli_real_escape_string($connexion, $table);
    $champId = mysqli_real_escape_string($connexion, $champId);
    $valeur = intval($valeur);

    $sql = "DELETE FROM `$table` WHERE `$champId` = $valeur";

    if (mysqli_query($connexion, $sql)) {
        return true;
    } else {
        return 'Erreur : ' . mysqli_error($connexion);
    }
}

function getBordereaux()
{
    global $connexion;

    $sql = "
        SELECT
            b.idBordereau,
            b.numeroBordereau,
            b.dateSys,
            GROUP_CONCAT(
                CONCAT('BE', RIGHT(YEAR(e.dateEng), 2), '-', LPAD(e.idEng, 4, '0'))
                ORDER BY e.idEng
                SEPARATOR ', '
            ) AS bud_engagements,
            SUM(e.montant) AS total
        FROM bud_bordereaux b
        INNER JOIN bud_engagements e
            ON e.idBordereau = b.idBordereau
        GROUP BY
            b.idBordereau,
            b.numeroBordereau,
            b.dateSys
        ORDER BY b.idBordereau DESC
    ";

    $result = $connexion->query($sql);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    return [];
}

function getBordereauxOperations()
{
    global $connexion;

    $sql = "
        SELECT
            b.idBordereau,
            b.numeroBordereau,
            b.dateSys,

            /* Liste des opérations */
            GROUP_CONCAT(
                DISTINCT CONCAT(
                    'MD',
                    RIGHT(YEAR(o.dateOp), 2),
                    '-',
                    LPAD(o.idOp, 4, '0')
                )
                ORDER BY o.idOp
                SEPARATOR ', '
            ) AS operations,

            /* Liste des engagements associés */
            GROUP_CONCAT(
                DISTINCT CONCAT(
                    'BE',
                    RIGHT(YEAR(e.dateEng), 2),
                    '-',
                    LPAD(e.idEng, 4, '0')
                )
                ORDER BY e.idEng
                SEPARATOR ', '
            ) AS engagements,

            /* Nombre d'opérations */
            COUNT(DISTINCT o.idOp) AS nbOperations,

            /* Total du bordereau */
            SUM(o.montant) AS total

        FROM bud_bordereaux b

        INNER JOIN bud_operations o
            ON o.idBordereau = b.idBordereau

        INNER JOIN bud_engagements e
            ON e.idEng = o.idEng

        GROUP BY
            b.idBordereau,
            b.numeroBordereau,
            b.dateSys

        ORDER BY b.idBordereau DESC
    ";

    $result = $connexion->query($sql);

    return ($result && $result->num_rows > 0)
        ? $result->fetch_all(MYSQLI_ASSOC)
        : [];
}

function getOperationsByBordereau($idBordereau)
{
    global $connexion;

    $sql = '
        SELECT
            o.idOp,
            e.objet,
            o.montant,
            f.nom
        FROM bud_operations o
        INNER JOIN bud_engagements e ON e.idEng = o.idEng
        INNER JOIN bud_fournisseur f ON f.idFourn = e.idFourn
        WHERE o.idBordereau = ?
        ORDER BY o.idOp
    ';

    $stmt = $connexion->prepare($sql);
    $stmt->bind_param('i', $idBordereau);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getBordereauById($idBordereau)
{
    global $connexion;

    $sql = '
        SELECT
            b.idBordereau,
            b.numeroBordereau,
            b.dateSys,
            b.idUser
        FROM bud_bordereaux b
        WHERE b.idBordereau = ?
        LIMIT 1
    ';

    $stmt = $connexion->prepare($sql);

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param('i', $idBordereau);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}

?>