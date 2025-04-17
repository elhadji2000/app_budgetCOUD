<?php 
/********************************************************************************** 
Connectez-vous à votre base de données MySQL 
 **********************************************************************************/

function connexionBD(){ 
    $connexion = mysqli_connect('localhost','root','', 'budget_db') or die ('Serveur inaccessible. Merci de reessayer plus tard.');
    return $connexion;
}
$connexion = connexionBD();

/********************************************************************************** 
Fonction de connexion dans l'espace utilisateur
 ********************************************************************************* */
function login($username, $password)
{
    global $connexion;
    $users = "SELECT * FROM `users` where `log`='$username' and `mdp` =  '".SHA1($password)."' ";
	//$users = "SELECT * FROM `codif_user` where `username_user`='$username' and `password_user` =  '$password' ";
	                                                                           
    $info = $connexion->query($users);
    return $info->fetch_assoc();
}

/********************************************************************************** 
Fonction pour recuperer tous les ordres de recettes
 ********************************************************************************* */
function getAllRecettes()
{
    global $connexion;
    $query = "SELECT * FROM `bud_or`"; // Assurez-vous que la table `bud_recettes` existe
    $result = $connexion->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); // Retourne toutes les recettes sous forme de tableau associatif
    } else {
        return []; // Retourne un tableau vide si aucune recette n'est trouvée
    }
}

/********************************************************************************** 
Fonction pour recuperer tous les Fournisseurs
 ********************************************************************************* */
function getAllFournisseurs()
{
    global $connexion;
    $query = "SELECT * FROM `fournisseur`"; 
    $result = $connexion->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); 
    } else {
        return []; 
    }
}
/********************************************************************************** 
Fonction pour recuperer les numeros de Compte
 ********************************************************************************* */
function getNumCompte()
{
    global $connexion;
    $query = "SELECT numCompte FROM `compte` ORDER BY numCompte ASC"; 
    $result = $connexion->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); 
    } else {
        return []; 
    }
}
function getCompteByNum($numCompte)
{
    global $connexion;
    $query = "SELECT numCompte, libelle, code FROM `compte` WHERE numCompte='$numCompte'"; 
    $result = $connexion->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc(); // Retourne une seule ligne sous forme de tableau associatif
    } else {
        return null; // Si aucune ligne trouvée, retourner null
    }
}

/********************************************************************************** 
Fonction pour recuperer tous les comptes
 ********************************************************************************* */
function getAllCompte()
{
    global $connexion;
    $query = "SELECT c.numCompte,c.code, c.libelle, cp.numCp, cp.nature FROM compte As c JOIN compteP As cp ON c.idCp=cp.idCp ORDER BY cp.numCp ASC"; 
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
    $anneeEnCours = date("Y"); // Récupère l'année en cours

    // Requête SQL pour récupérer les comptes déjà dotés et dont l'année de dotation est égale à l'année en cours
    $query = "
        SELECT c.numCompte, c.code, c.libelle, cp.numCp, cp.nature
        FROM compte AS c
        JOIN compteP AS cp ON c.idCp = cp.idCp
        JOIN dotations AS d ON c.idCompte = d.idCompte
        WHERE YEAR(d.date) = '$anneeEnCours'  -- Filtrer sur l'année de la dotation
        ORDER BY cp.numCp ASC";
    
    $result = $connexion->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); // Retourne les résultats sous forme de tableau associatif
    } else {
        return []; // Si aucune ligne trouvée
    }
}

/********************************************************************************** 
Fonction pour recuperer tous les dotations
 ********************************************************************************* */
function getAllDotations()
{
    global $connexion;

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
        FROM dotations d
        INNER JOIN users u ON d.idUser = u.idUser
        INNER JOIN compte c ON d.idCompte = c.idCompte
        ORDER BY d.dateSys DESC
    ";

    $result = $connexion->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

/********************************************************************************** 
Fonction pour recuperer tous les Utilisateurs
 ********************************************************************************* */
function getAllUsers()
{
    global $connexion;
    $query = "SELECT * FROM `users`"; 
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
    $query = "SELECT * FROM `fournisseur`"; 
    $result = $connexion->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); 
    } else {
        return []; 
    }
}

function ajouterFournisseur($numFourn, $nom, $adresse, $contact, $nature)
{
    global $connexion;

    $stmt = $connexion->prepare("INSERT INTO fournisseur (numFourn, nom, adresse, contact, nature) VALUES (?, ?, ?, ?, ?)");

    if (!$stmt) {
        return "Erreur de préparation : " . $connexion->error;
    }

    $stmt->bind_param("sssss", $numFourn, $nom, $adresse, $contact, $nature);

    if ($stmt->execute()) {
        return true;
    } else {
        return "Erreur lors de l'insertion : " . $stmt->error;
    }
}

function ajouterEngagement($dateEng, $service, $montant, $libelle, $bc, $idCompte, $idFourn)
{
    global $connexion;

    // Sécurisation simple contre injection SQL
    $dateEng = mysqli_real_escape_string($connexion, $dateEng);
    $service = mysqli_real_escape_string($connexion, $service);
    $montant = mysqli_real_escape_string($connexion, $montant);
    $libelle = mysqli_real_escape_string($connexion, $libelle);
    $bc = mysqli_real_escape_string($connexion, $bc);
    $idCompte = (int)$idCompte;
    $idFourn = (int)$idFourn;

    $query = "INSERT INTO engagements (dateEng, service, montant, libelle, bc, idCompte, idFourn)
              VALUES ('$dateEng', '$service', '$montant', '$libelle', '$bc', $idCompte, $idFourn)";

    if ($connexion->query($query) === TRUE) {
        return true;
    } else {
        return "Erreur lors de l'insertion : " . $connexion->error;
    }
}

function getIdCompteByNum($numCompte)
{
    global $connexion;
    $query = "SELECT idCompte FROM compte WHERE numCompte = '$numCompte' LIMIT 1";
    $result = $connexion->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['idCompte'];
    } else {
        return null; // ou false selon ton besoin
    }
}



/********************************************************************************** 
Fonction pour Enregistrer une dotation
 ********************************************************************************* */
function enregistrerDotation($numCompte, $dateDotation, $montantDotation, $type) {
    global $connexion;

    // Sécurité
    $numCompte = mysqli_real_escape_string($connexion, $numCompte);
    $dateDotation = mysqli_real_escape_string($connexion, $dateDotation);
    $type = mysqli_real_escape_string($connexion, $type);
    $montantDotation = (int) $montantDotation;

    // Étape 1 : Récupérer l'ID du compte depuis numCompte
    $query = "SELECT idCompte FROM compte WHERE numCompte = '$numCompte' LIMIT 1";
    $result = mysqli_query($connexion, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return "Numéro de compte introuvable.";
    }

    $row = mysqli_fetch_assoc($result);
    $compte_id = $row['idCompte'];

    // Suppose qu'on récupère l'utilisateur connecté, ou temporairement défini à 1
    $user = (int) $_SESSION['idUser'];

    // Récupérer l'année depuis la session
    $an = isset($_SESSION['an']) ? (int) $_SESSION['an'] : date("Y");

    // Ici type est NULL si tu ne veux rien mettre
    $sql = "INSERT INTO dotations (an, volume, `date`, `type`, idCompte, idUser) 
        VALUES ($an, $montantDotation, '$dateDotation', '$type', $compte_id, $user)";

    if (mysqli_query($connexion, $sql)) {
        return true;
    } else {
        return "Erreur SQL : " . mysqli_error($connexion);
    }
}

function getDetailsCompte($numCompte) {
    global $connexion;
    
   // Récupérer les dotations (initiales et remaniées) pour un compte donné
   $queryDotations = "
   SELECT 
       SUM(CASE WHEN d.type = 'initiale' THEN d.volume ELSE 0 END) AS dotationInitiale,
       SUM(CASE WHEN d.type = 'remanier' THEN d.volume ELSE 0 END) AS dotationRemaniee
   FROM dotations d
   JOIN compte c ON c.idCompte = d.idCompte
   WHERE c.numCompte = '$numCompte' AND YEAR(d.date) = YEAR(CURRENT_DATE)
";
$resultDotations = $connexion->query($queryDotations);

if ($resultDotations->num_rows > 0) {
   $dotations = $resultDotations->fetch_assoc();
   $dotationInitiale = $dotations['dotationInitiale'] ?: 0; // Si null, mettre 0
   $dotationRemaniee = $dotations['dotationRemaniee'] ?: 0; // Si null, mettre 0
} else {
   return "Aucune dotation trouvée pour ce compte.";
}

// Récupérer les engagements associés à ce compte
$queryEngagements = "
   SELECT SUM(e.montant) AS totalEngagement
   FROM engagements e
   JOIN compte c ON c.idCompte = e.idCompte
   WHERE c.numCompte = '$numCompte' AND YEAR(e.dateEng) = YEAR(CURRENT_DATE)
";
$resultEngagements = $connexion->query($queryEngagements);
$engagement = $resultEngagements->fetch_assoc();
$totalEngagement = $engagement['totalEngagement'] ?: 0; // Si null, mettre 0
    // Calcul de l'écart
    $ecart = ($dotationInitiale + $dotationRemaniee) - ($totalEngagement);

    // Préparer les résultats
    return [
        'dotationInitiale' => $dotationInitiale,
        'dotationRemaniee' => $dotationRemaniee,
        'totalEngagement' => $totalEngagement,
        'ecart' => $ecart,
    ];
}


?>
<?php 
/********************************************************************************** 
Connectez-vous à votre base de données MySQL 
 **********************************************************************************/

function connexionBD(){ 
    $connexion = mysqli_connect('localhost','root','', 'budget_db') or die ('Serveur inaccessible. Merci de reessayer plus tard.');
    return $connexion;
}
$connexion = connexionBD();

/********************************************************************************** 
Fonction de connexion dans l'espace utilisateur
 ********************************************************************************* */
function login($username, $password)
{
    global $connexion;
    $users = "SELECT * FROM `users` where `log`='$username' and `mdp` =  '".SHA1($password)."' ";
	//$users = "SELECT * FROM `codif_user` where `username_user`='$username' and `password_user` =  '$password' ";
	                                                                           
    $info = $connexion->query($users);
    return $info->fetch_assoc();
}

/********************************************************************************** 
Fonction pour recuperer tous les ordres de recettes
 ********************************************************************************* */
function getAllRecettes()
{
    global $connexion;
    $query = "SELECT * FROM `bud_or`"; // Assurez-vous que la table `bud_recettes` existe
    $result = $connexion->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); // Retourne toutes les recettes sous forme de tableau associatif
    } else {
        return []; // Retourne un tableau vide si aucune recette n'est trouvée
    }
}

/********************************************************************************** 
Fonction pour recuperer tous les Fournisseurs
 ********************************************************************************* */
function getAllFournisseurs()
{
    global $connexion;
    $query = "SELECT * FROM `fournisseur`"; 
    $result = $connexion->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); 
    } else {
        return []; 
    }
}
/********************************************************************************** 
Fonction pour recuperer les numeros de Compte
 ********************************************************************************* */
function getNumCompte()
{
    global $connexion;
    $query = "SELECT idCompte, numCompte, libelle FROM `compte` ORDER BY numCompte ASC"; 
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
        SELECT c.numCompte, c.idCompte 
        FROM compte c
        LEFT JOIN dotations d ON c.idCompte = d.idCompte AND d.type = 'initiale'
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
    $query = "SELECT numCompte, libelle, code FROM `compte` WHERE numCompte='$numCompte'"; 
    $result = $connexion->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc(); // Retourne une seule ligne sous forme de tableau associatif
    } else {
        return null; // Si aucune ligne trouvée, retourner null
    }
}


/********************************************************************************** 
Fonction pour recuperer tous les comptes
 ********************************************************************************* */
function getAllCompte()
{
    global $connexion;
    $query = "SELECT c.numCompte,c.code, c.libelle, cp.numCp, cp.nature FROM compte As c JOIN compteP As cp ON c.idCp=cp.idCp ORDER BY cp.numCp ASC"; 
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
    $anneeEnCours = date("Y"); // Récupère l'année en cours

    // Requête SQL pour récupérer les comptes déjà dotés et dont l'année de dotation est égale à l'année en cours
    $query = "
        SELECT c.numCompte, c.code, c.libelle, cp.numCp, cp.nature
        FROM compte AS c
        JOIN compteP AS cp ON c.idCp = cp.idCp
        JOIN dotations AS d ON c.idCompte = d.idCompte
        WHERE YEAR(d.date) = '$anneeEnCours'  -- Filtrer sur l'année de la dotation
        ORDER BY cp.numCp ASC";
    
    $result = $connexion->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); // Retourne les résultats sous forme de tableau associatif
    } else {
        return []; // Si aucune ligne trouvée
    }
}

/********************************************************************************** 
Fonction pour recuperer tous les dotations
 ********************************************************************************* */
function getAllDotations()
{
    global $connexion;

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
        FROM dotations d
        INNER JOIN users u ON d.idUser = u.idUser
        INNER JOIN compte c ON d.idCompte = c.idCompte
        ORDER BY d.dateSys DESC
    ";

    $result = $connexion->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

/********************************************************************************** 
Fonction pour recuperer tous les Utilisateurs
 ********************************************************************************* */
function getAllUsers()
{
    global $connexion;
    $query = "SELECT * FROM `users`"; 
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
    $query = "SELECT * FROM `fournisseur`"; 
    $result = $connexion->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); 
    } else {
        return []; 
    }
}


/********************************************************************************** 
Fonction pour recuperer les numero de compte pour enregistrer une ordre de paiement O.P
 ********************************************************************************* */
function getNumCompteForOp(){
    global $connexion;
    $query = "SELECT DISTINCT compte.idCompte, compte.numCompte
        FROM compte
        JOIN comptep ON compte.idCp = comptep.idCp
        JOIN engagements ON compte.idCompte = engagements.idCompte
        WHERE (comptep.nature = 'charge' OR comptep.nature = 'emploi')
        AND engagements.idEng NOT IN (
            SELECT DISTINCT idEng FROM operations WHERE idEng IS NOT NULL
        )
        ORDER BY compte.numCompte ASC
    ";
    
    $result = $connexion->query($query);
    if($result->num_rows > 0){
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    else {
        return [];
    }
}
/********************************************************************************** 
Fonction pour recuperer les numero de compte pour enregistrer une ordre de recette O.R
 ********************************************************************************* */
function getNumCompteForOr() {
    global $connexion;

    $query = "SELECT DISTINCT compte.idCompte, compte.numCompte
        FROM compte
        JOIN comptep ON compte.idCp = comptep.idCp
        JOIN engagements ON compte.idCompte = engagements.idCompte
        WHERE (comptep.nature = 'ressource' OR comptep.nature = 'produit')
        AND engagements.idEng NOT IN (
            SELECT DISTINCT idEng FROM operations WHERE idEng IS NOT NULL
        )
        ORDER BY compte.numCompte ASC
    ";

    $result = $connexion->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

/********************************************************************************** 
Fonction pour recuperer les engagements
 ********************************************************************************* */
function getEngs(){
    global $connexion;
   // $anneeEnCours = date("Y"); // Récupère l'année en cours
    $anneeEnCours = $_SESSION['an'];

    $query = "SELECT c.numCompte, eng.idEng, eng.dateEng, eng.service, eng.libelle, eng.bc, eng.montant, f.numFourn, d.an  
            FROM engagements as eng
            JOIN compte as c ON c.idCompte = eng.idCompte 
            JOIN comptep as cp ON c.idCp=cp.idCp 
            JOIN dotations as d ON c.idCompte= d.idCompte 
            JOIN fournisseur as f ON f.idFourn= eng.idFourn 
            WHERE d.an='$anneeEnCours';";
    
    $result = $connexion->query($query);
    if($result->num_rows > 0){
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    else {
        return [];
    }
}
/********************************************************************************** 
Fonction pour recuperer les engagements a partir d'un numero de compte
 ********************************************************************************* */
function getEngsByNumCompte($numCompte){
    global $connexion;
    $query = "SELECT * FROM `engagements`
            JOIN compte ON compte.idCompte = engagements.idCompte 
            JOIN comptep ON compte.idCp=comptep.idCp 
            JOIN dotations ON compte.idCompte= dotations.idCompte 
            WHERE compte.numCompte='$numCompte';";
    
    $result = $connexion->query($query);
    if($result->num_rows > 0){
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    else {
        return [];
    }
}
function getEngsByNumCompteNonOperation($numCompte){
    global $connexion;
    $query = "
        SELECT engagements.* 
        FROM engagements
        JOIN compte ON compte.idCompte = engagements.idCompte 
        JOIN comptep ON compte.idCp = comptep.idCp 
        JOIN dotations ON compte.idCompte = dotations.idCompte 
        WHERE compte.numCompte = '$numCompte'
        AND engagements.idEng NOT IN (
            SELECT idEng FROM operations
        );
    ";
    
    $result = $connexion->query($query);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}
function isEngagementUsed($idEng){
    global $connexion;
    $query = "SELECT 1 FROM operations WHERE idEng = ?";
    $stmt = $connexion->prepare($query);
    $stmt->bind_param("i", $idEng);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}
function isFournisseurUsed($idFournisseur){
    global $connexion;
    $query = "SELECT 1 FROM engagements WHERE idFourn = ?";
    $stmt = $connexion->prepare($query);
    $stmt->bind_param("i", $idFournisseur);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}

function isDotationUsed($idDotation) {
    global $connexion;
    $query = "
        SELECT 1
        FROM dotations d
        INNER JOIN engagements e ON d.idCompte = e.idCompte
        WHERE d.idDot = ?
        LIMIT 1
    ";
    $stmt = $connexion->prepare($query);
    $stmt->bind_param("i", $idDotation);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}




/********************************************************************************** 
Fonction pour recuperer les engagements a partir d'un numero de compte
 ********************************************************************************* */
function getOperationsByType($typeOp) {
    global $connexion;
    $query = "
        SELECT 
            o.idOp,
            o.dateOp,
            o.numFact,
            e.montant,
            e.idEng,
            d.an,
            e.dateEng,
            e.libelle,
            e.service,
            f.numFourn,
            c.numCompte
        FROM operations o
        INNER JOIN engagements e ON o.idEng = e.idEng
        INNER JOIN compte c ON e.idCompte = c.idCompte
        INNER JOIN dotations d ON d.idCompte = c.idCompte
        INNER JOIN fournisseur f ON e.idFourn = f.idFourn
        WHERE o.typeOp = ?
        ORDER BY o.idOp DESC
    ";

    $stmt = $connexion->prepare($query);
    $stmt->bind_param("s", $typeOp);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


function enregistrerDotation($idCompte, $date, $volume, $type) {
    // Démarre la session si ce n'est pas déjà fait
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Connexion MySQLi
    global $connexion;

    if (!$connexion) {
        return "Connexion échouée : " . mysqli_connect_error();
    }

    // Vérifie que l'utilisateur est connecté
    if (!isset($_SESSION['idUser'])) {
        return "Utilisateur non connecté.";
    }

    $idUser = (int) $_SESSION['idUser'];
    $an = (int) $_SESSION['an'];

    // Validation : montant non négatif
    if ($volume < 0) {
        return "Le volume ne peut pas être négatif.";
    }

    // Échappement des données
    $idCompte = mysqli_real_escape_string($connexion, $idCompte);
    $date = mysqli_real_escape_string($connexion, $date);
    $volume = (int) $volume;
    $type = mysqli_real_escape_string($connexion, $type);

    // Requête d'insertion
    $sql = "INSERT INTO dotations (idCompte, date, an, volume, type, idUser) 
            VALUES ('$idCompte', '$date', '$an', $volume, '$type', $idUser)";

    // Exécution
    if (mysqli_query($connexion, $sql)) {
        return true;
    } else {
        return "Erreur lors de l'enregistrement : " . mysqli_error($connexion);
    }
}

function getIdCompteByNum($numCompte) {
    global $connexion;

    if (!$connexion) {
        return "Connexion échouée : " . mysqli_connect_error();
    }

    // Échappement du numéro de compte
    $numCompte = mysqli_real_escape_string($connexion, $numCompte);

    // Requête
    $sql = "SELECT idCompte FROM compte WHERE numCompte = '$numCompte' LIMIT 1";
    $result = mysqli_query($connexion, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['idCompte']; // Retourne l'identifiant
    } else {
        return null; // Aucun compte trouvé
    }
}

function getDetailsCompte($numCompte) {
    global $connexion;

    // Sécuriser le numéro de compte
    $numCompte = mysqli_real_escape_string($connexion, $numCompte);

    // Requête pour récupérer l'ID du compte à partir du numéro
    $sqlId = "SELECT idCompte FROM compte WHERE numCompte = '$numCompte' LIMIT 1";
    $resultId = mysqli_query($connexion, $sqlId);
    if (!$resultId || mysqli_num_rows($resultId) == 0) {
        return "Compte introuvable.";
    }
    $rowId = mysqli_fetch_assoc($resultId);
    $idCompte = $rowId['idCompte'];

    // DOTATION INITIALE
    $sqlInitiale = "SELECT SUM(volume) AS totalInitiale 
                    FROM dotations 
                    WHERE idCompte = $idCompte AND type = 'initiale'";
    $resInitiale = mysqli_query($connexion, $sqlInitiale);
    $initiale = ($resInitiale && $row = mysqli_fetch_assoc($resInitiale)) ? (int)$row['totalInitiale'] : 0;

    // DOTATION REMANIEE
    $sqlRemaniee = "SELECT SUM(volume) AS totalRemaniee 
                    FROM dotations 
                    WHERE idCompte = $idCompte AND type = 'remaniee'";
    $resRemaniee = mysqli_query($connexion, $sqlRemaniee);
    $remaniee = ($resRemaniee && $row = mysqli_fetch_assoc($resRemaniee)) ? (int)$row['totalRemaniee'] : 0;

    // TOTAL ENGAGEMENT
    $sqlEngagement = "SELECT SUM(montant) AS totalEngagement 
                      FROM engagements 
                      WHERE idCompte = $idCompte";
    $resEngagement = mysqli_query($connexion, $sqlEngagement);
    $engagement = ($resEngagement && $row = mysqli_fetch_assoc($resEngagement)) ? (int)$row['totalEngagement'] : 0;

   // Dépenses : Total des O.P (operations liées au compte via les engagements)
$sql_dep = "
SELECT SUM(e.montant) AS total 
FROM operations o
INNER JOIN engagements e ON o.idEng = e.idEng
WHERE e.idCompte = $idCompte
";

$res_dep = mysqli_query($connexion, $sql_dep);
$depenses = ($res_dep && $row = mysqli_fetch_assoc($res_dep)) ? (int)$row['total'] : 0;


    // ECART = DOTATION TOTALE - ENGAGEMENT
    $ecart = ($initiale + $remaniee) - $engagement;

    // Résultat final
    return [
        'dotationInitiale' => $initiale,
        'dotationRemaniee' => $remaniee,
        'totalEngagement' => $engagement,
        'O.P' => $depenses,
        'ecart' => $ecart
    ];
}

function ajouterFournisseur($numFourn, $nom, $adresse, $contact, $nature) {
    global $connexion;

    if (!$connexion) {
        return "Erreur de connexion : " . mysqli_connect_error();
    }

    // Échappement des données pour éviter les injections
    $numFourn = mysqli_real_escape_string($connexion, $numFourn);
    $nom = mysqli_real_escape_string($connexion, $nom);
    $adresse = mysqli_real_escape_string($connexion, $adresse);
    $contact = mysqli_real_escape_string($connexion, $contact);
    $nature = mysqli_real_escape_string($connexion, $nature);

    // Vérifie si le numéro fournisseur existe déjà
    $verif_sql = "SELECT idFourn FROM fournisseur WHERE numFourn = '$numFourn'";
    $verif_res = mysqli_query($connexion, $verif_sql);

    if (mysqli_num_rows($verif_res) > 0) {
        return "Ce numéro de fournisseur existe déjà.";
    }

    // Insertion
    $sql = "INSERT INTO fournisseur (numFourn, nom, adresse, contact, nature) 
            VALUES ('$numFourn', '$nom', '$adresse', '$contact', '$nature')";

    if (mysqli_query($connexion, $sql)) {
        return true;
    } else {
        return "Erreur lors de l'ajout : " . mysqli_error($connexion);
    }
}

function ajouterEngagement($dateEng, $service, $montant, $libelle, $bc, $idCompte, $idFourn) {
    global $connexion;
    session_start(); // si ce n’est pas déjà démarré

    if (!$connexion) {
        return "Erreur de connexion : " . mysqli_connect_error();
    }

    // Sécurité de base
    if ($montant < 0) {
        return "Le montant ne peut pas être négatif.";
    }

    // Récupération de l'idUser depuis la session
    if (!isset($_SESSION['idUser'])) {
        return "Utilisateur non identifié.";
    }
    $idUser = $_SESSION['idUser'];

    // Échappement des données
    $dateEng = mysqli_real_escape_string($connexion, $dateEng);
    $service = mysqli_real_escape_string($connexion, $service);
    $libelle = mysqli_real_escape_string($connexion, $libelle);
    $bc = mysqli_real_escape_string($connexion, $bc);
    $idCompte = (int) $idCompte;
    $idFourn = (int) $idFourn;
    $montant = (float) $montant;

    // Requête d'insertion
    $sql = "INSERT INTO engagements (dateEng, service, montant, libelle, bc, idCompte, idFourn) 
            VALUES ('$dateEng', '$service', $montant, '$libelle', '$bc', $idCompte, $idFourn)";

    if (mysqli_query($connexion, $sql)) {
        return true;
    } else {
        return "Erreur lors de l'ajout : " . mysqli_error($connexion);
    }
}

function ajouterOp($dateOp, $idEng, $numFact, $typeOp) {
    global $connexion;

    // Sécurisation des données
    $dateOp = mysqli_real_escape_string($connexion, $dateOp);
    $idEng = (int)$idEng;
    $numFact = mysqli_real_escape_string($connexion, $numFact);
    $typeOp = mysqli_real_escape_string($connexion, $typeOp);

    // Requête d'insertion
    $query = "
        INSERT INTO operations (dateOp, idEng, numFact, typeOp)
        VALUES ('$dateOp', $idEng, '$numFact', '$typeOp')
    ";

    // Exécution
    if ($connexion->query($query)) {
        return true;
    } else {
        return "Erreur lors de l'ajout de l'opération : " . $connexion->error;
    }
}



?>