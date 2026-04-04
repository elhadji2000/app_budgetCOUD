<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}

include '../../includes/fonctions.php';
require_once __DIR__ . '/../../vendor/autoload.php';  // mPDF autoload

$id = $_GET['id'];
$rec = getRecettesById($id);

$dateOr = $rec['dateOr'] ?? date('Y-m-d');
$anneeGestion = date('Y', strtotime($dateOr));
$moisGestion = date('m', strtotime($dateOr));

// --- Contenu HTML ---
$html = '
<div style="width:100%; font-family: Arial, sans-serif; font-size:8px;">
    <table width="100%">
        <tr>
            <!-- Bloc gauche : texte et logo centré -->
            <td width="50%" style="text-align:center;font-size:8px;">
                <strong>REPUBLIQUE DU SENEGAL</strong><br>
                UN PEUPLE - UN BUT - UNE FOI<br>
                <img src="' . $_SERVER['DOCUMENT_ROOT'] . '/BUDGET/assets/images/senegal.png" width="60" height="25"><br>
                MINISTERE DE L\'ENSEIGNEMENT SUPERIEUR DE LA RECHERCHE ET DE L\'INNOVATION<br>
                CENTRE DES OEUVRES UNIVERSITAIRES DE DAKAR<br>
                <strong>DEPARTEMENT DU BUDGET</strong>
            </td>

            <!-- Bloc droit : gestion année/mois -->
            <td width="50%" style="text-align:left;font-size:12px;vertical-align:top;padding-left:25%;">
                <strong>GESTION : ' . $anneeGestion . '</strong><br><br>
                <strong>Mois : ' . $moisGestion . '</strong>
            </td>
        </tr>
    </table>
    <br><br>
    <p style="text-align:left;margin:0px;font-size:12px;">SERVICE:  COUD</p>
    <h1 style="text-align:center; margin:0;">ORDRE DE RECETTE</h1>
    <p style="text-align:center;font-size:14px;margin-top:3px;">N° ' . formatNumOr($rec['idOr']) . '</p>
     <table width="100%">
        <tr>
            <!-- Bloc gauche : texte et logo centré -->
            <td width="55%" style="text-align:left;font-size:12px;">
            Numero Bordereau:
            </td>
            <td width="45%" style="text-align:left;font-size:12px;vertical-align:top;">
            Numero Compte: ' . $rec['numCompte'] . '
            </td>
        </tr>
    </table>
     <table width="100%">
        <tr>
            <!-- Bloc gauche : texte et logo centré -->
            <td width="55%" style="text-align:left;font-size:12px;">
            Budget: FONCTIONNEMENT
            </td>
            <td width="45%" style="text-align:left;font-size:12px;vertical-align:top;">
            Libellé Compte: ' . $rec['libelle'] . '
            </td>
        </tr>
    </table>
    <br>
    <!-- Tableau des informations -->
    <table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse;font-size:14px;">
        <tr style="text-align:center;">
            <th>N° d\'ordre</th>
            <th>Identification du débiteur(Nom,qualité, ou profession)</th>
            <th>Objet de la recette</th>
            <th>Somme à payer </th>
            <th>Nombre et détail des Pièces annexées</th>
        </tr>
        <tr>
            <td style="text-align:center;">' . ($rec['idOr'] ?? '') . '</td>
            <td>' . (strtoupper($rec['nom']) ?? '') . '</td>
            <td>' . (strtoupper($rec['objet_recette']) ?? '') . '</td>
            <td style="text-align:left;">' . number_format($rec['montant'], 0, ' ', ' ') . '</td>
            <td>' . (strtoupper($rec['pieces_annexees']) ?? '') . '</td>
        </tr>
        <tr>
            <td style="text-align:center; border:none;"></td>
            <td style="border:none;"></td>
            <td style="text-align:right; font-weight:bold;">TOTAL</td>
            <td style="text-align:right; font-weight:bold;">' . number_format($rec['montant'], 0, ' ', ' ') . '</td>
            <td style="border:none;"></td>
        </tr>
    </table>

    <p style="text-align:left;margin-top:30px;font-size:12px;">
    Vu, certifié et arrêté le present état à la somme de : ' . nombreEnLettres($rec['montant']) . ' par le (la) Directeur qui, en sa qualité d\'ordonnateur, invite l\'agent comptable à recevoir ladite somme qui sera passée en recette dans ses ecritures et pour motifs enoncés ci-dessus
    </p>

     <table width="100%" style="margin-top:100vh;">
        <tr>
            <!-- Bloc gauche : texte et logo centré -->
            <td width="50%" style="text-align:left;font-size:12px;text-decoration:underline;">
                <strong>Directeur</strong>
            </td>

            <!-- Bloc droit : gestion année/mois -->
            <td width="50%" style="text-align:left;font-size:12px;vertical-align:top;padding-left:25%;">
                <strong>Dakar le, ' . date('j/m/Y', strtotime($dateOr)) . '</strong>
            </td>
        </tr>
    </table>
</div>
';

// --- Génération PDF ---
$mpdf = new \Mpdf\Mpdf([
    'orientation' => 'L',  // paysage
    'format' => 'A4',
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 15,
    'margin_bottom' => 15
]);

$mpdf->WriteHTML($html);

// Afficher directement le PDF dans le navigateur
$mpdf->Output('Ordre_de_Recette_' . $rec['idOr'] . '.pdf', 'I');