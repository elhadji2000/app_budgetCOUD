<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}

include '../../includes/fonctions.php';

header('Content-Type: application/json');

// Récupérer l'ID du compte depuis la requête GET
$idCompte = isset($_GET['idCompte']) ? intval($_GET['idCompte']) : 0;

if ($idCompte <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID compte invalide']);
    exit();
}

// Récupérer le numéro de compte
$sqlNum = "SELECT numCompte FROM bud_compte WHERE idCompte = $idCompte";
$resultNum = mysqli_query($connexion, $sqlNum);

if (!$resultNum || mysqli_num_rows($resultNum) == 0) {
    echo json_encode(['success' => false, 'message' => 'Compte non trouvé']);
    exit();
}

$rowNum = mysqli_fetch_assoc($resultNum);
$numCompte = $rowNum['numCompte'];

// Utiliser la fonction existante getDetailsCompte()
$details = getDetailsCompte($numCompte);

// Vérifier si les détails sont valides
if (!is_array($details)) {
    $errorMsg = is_string($details) ? $details : 'Erreur lors de la récupération des données';
    echo json_encode(['success' => false, 'message' => $errorMsg]);
    exit();
}

// Extraire les données
$dotationTotale = floatval($details['dotationTotale'] ?? 0);
$engagement = floatval($details['totalEngagement'] ?? 0);
$mandat = floatval($details['totalDepense'] ?? 0);
$credit = $dotationTotale - $engagement;

// Retourner les données au format JSON
echo json_encode([
    'success' => true,
    'data' => [
        'dotationTotale' => $dotationTotale,
        'engagement' => $engagement,
        'mandat' => $mandat,
        'credit' => $credit,
        'type' => $details['type'] ?? 'inconnu',
        'nature' => $details['nature'] ?? 'inconnu',
        'solde' => floatval($details['solde'] ?? 0),
        'dotationInitiale' => floatval($details['dotationInitiale'] ?? 0),
        'dotationRemaniee' => floatval($details['dotationRemaniee'] ?? 0)
    ]
]);
?>