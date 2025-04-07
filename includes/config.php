<?php
// Paramètres de connexion à la base de données
$host = "localhost";  // Adresse du serveur MySQL (ou IP)
$dbname = "budget_db";  // Nom de la base de données
$username = "root";  // Nom d'utilisateur MySQL
$password = "";  // Mot de passe (laisser vide en local)

// Connexion à la base de données avec MySQLi
$connexion = new mysqli($host, $username, $password, $dbname);

// Vérifier la connexion
if ($connexion->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Définir l'encodage des caractères (UTF-8)
$connexion->set_charset("utf8");

?>
