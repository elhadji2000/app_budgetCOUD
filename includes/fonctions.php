<?php 
/********************************************************************************** 
Connectez-vous à votre base de données MySQL 
 **********************************************************************************/

function connexionBD(){ 
    $connexion = mysqli_connect('localhost','root','', 'bdd') or die ('Serveur inaccessible. Merci de reessayer plus tard.');
    return $connexion;
}
$connexion = connexionBD();

/********************************************************************************** 
Fonction de connexion dans l'espace utilisateur
 ********************************************************************************* */
function login($username, $password)
{
    global $connexion;
    $users = "SELECT * FROM `bud_user` where `log`='$username' and `mdp` =  '".SHA1($password)."' ";
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
    $query = "SELECT * FROM `bud_fournisseur`"; 
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
    $query = "SELECT numc FROM `bud_compte` ORDER BY numc ASC"; 
    $result = $connexion->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); 
    } else {
        return []; 
    }
}

/********************************************************************************** 
Fonction pour recuperer tous les comptes
 ********************************************************************************* */
function getAllCompte()
{
    global $connexion;
    $query = "SELECT * FROM `bud_compte` ORDER BY numc ASC"; 
    $result = $connexion->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); 
    } else {
        return []; 
    }
}
/********************************************************************************** 
Fonction pour recuperer tous les dotations
 ********************************************************************************* */
function getAllDotations()
{
    global $connexion;
    $query = "SELECT * FROM `bud_dotation` ORDER BY numc ASC"; 
    $result = $connexion->query($query);

    if ($result->num_rows > 0) {
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
    $query = "SELECT * FROM `bud_user`"; 
    $result = $connexion->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); 
    } else {
        return []; 
    }
}

?>