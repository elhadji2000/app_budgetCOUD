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