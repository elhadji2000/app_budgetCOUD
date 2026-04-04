<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}

include '../../includes/fonctions.php';
require_once __DIR__ . '/../../vendor/autoload.php';  // mPDF autoload

$idOp = $_GET['id'];
$op = getOperationById($idOp); 

$dateOp = $op['dateOp'] ?? date('Y-m-d');
$anneeGestion = date('Y', strtotime($dateOp));
$moisGestion = date('m', strtotime($dateOp));

// --- Contenu HTML ---
$html = '
<div style="width:100%; font-family: Arial, sans-serif; font-size:8px;">
    <table width="100%">
        <tr>
            <!-- Bloc gauche : texte et logo centré -->
            <td width="25%" style="text-align:center;font-size:7px;">
                <strong>REPUBLIQUE DU SENEGAL</strong><br>
                UN PEUPLE - UN BUT - UNE FOI<br>
                <img src="' . $_SERVER['DOCUMENT_ROOT'] . '/BUDGET/assets/images/senegal.png" width="60" height="25"><br>
                MINISTERE DE L\'ENSEIGNEMENT SUPERIEUR DE LA RECHERCHE ET DE L\'INNOVATION<br>
                CENTRE DES OEUVRES UNIVERSITAIRES DE DAKAR<br>
                <strong>DEPARTEMENT DU BUDGET</strong>
            </td>

             <td width="50%" style="text-align:center;font-size:12px;vertical-align:top;">
                <h4>MANDAT DE PAIEMENT</h4><br>
                <strong>N° : ' . formatNumOP($op['idOp']) . '</strong>
            </td>

            <!-- Bloc droit : gestion année/mois -->
            <td width="25%" style="text-align:rigth;font-size:12px;vertical-align:top;">
                <strong>GESTION : ' . $anneeGestion . '</strong><br><br>
                <strong>Dakar le, ' . date('j/m/Y', strtotime($dateOp)) . '</strong>
            </td>
        </tr>
    </table>
    <br><br>
    <!-- Tableau des informations -->
    <table width="100%" border="1" cellspacing="0" cellpadding="8" 
       style="border-collapse: collapse; font-size:14px;">

    <tr width="100%">
        <td style="padding:15px;" width="50%">
            <p style="font-weight:bold;">OBJET DE LA DEPENSE :</p>
            <br>
            <p>' . strtoupper($op['objet']) . '</p>
            <br><br><br><br>
        </td>

        <td style="padding:15px;" width="50%">
            <p>Montant (en chiffres) : 
                <strong>' . number_format($op['montant_op'], 0, ' ', ' ') . '</strong>
            </p>
            <br>
            <p>En (lettres) : ' . nombreEnLettres($op['montant_op']) . '</p>
        </td>
    </tr>

    <tr>
        <!-- GAUCHE -->
        <td style="vertical-align:top; border-right:1px solid #000; padding:15px;">
            <strong>PIECES JUSTIFICATIVES</strong>
            <br><br>
            <p>FACTURE N° : ' . strtoupper($op['numFact'] ?? '') . '</p>
        </td>

        <!-- DROITE -->
        <td rowspan="2" style="vertical-align:top; padding:15px;">
            <strong>REGLEMENT</strong>
            <br><br>
            <p>Mode :</p><br>
            <p>Etablissement :</p><br>
            <p>Code Etablissement :</p><br>
            <p>Numéro Compte :</p><br>
            <p>Ville :</p><br>
            <p>Code Guichet :</p><br>
            <p>RIB :</p>
        </td>
    </tr>

    <tr>
        <td style="vertical-align:top; border-right:1px solid #000; padding:15px;">
            <strong>BENEFICIAIRE</strong>
            <br><br>
            <p>' . strtoupper($op['nom'] ?? '') . '</p>
        </td>
    </tr>

    <tr>
        <td style="vertical-align:top; padding:15px;">
            <strong>ENGAGEMENT</strong>
            <br><br>
            <p>NUMERO : ' . formatNumEng($op['idEng']) . '</p>
            <br>
            <p>DATE : ' . date('j/m/Y', strtotime($op['dateEng'])) . '</p>
            <br>
            <p>MONTANT : ' . number_format($op['montant_eng'], 0, ' ', ' ') . '</p>
            <br><br>
            <strong>SERVICE :</strong>
            <br><br>
            <p>COUD</p>
            <br><br>
        </td>

        <td style="vertical-align:top; text-align:center; padding:15px;">
            <strong>Cadre réservé à l\'Ordonnateur</strong>
        </td>
    </tr>

    <tr>
        <td style="vertical-align:top; padding:15px;">
            <strong>IMPUTATION BUDGETAIRE</strong>
            <br><br>
            <p>COMPTE PRINCIPAL : ' . $op['numCp'] . '</p>
            <br>
            <p>COMPTE D\'IMPUTATION : ' . $op['numCompte'] . '</p>
            <br>
            <p>LIBELLE : ' . $op['libelleCompte'] . '</p>
            <br><br><br>
        </td>

        <td style="vertical-align:top; text-align:center; padding:15px;">
            <strong>Cadre réservé au Comptable</strong>
        </td>
    </tr>

</table>
    <br>
</div>
';

// --- Génération PDF ---
$mpdf = new \Mpdf\Mpdf([
    'orientation' => 'P',  // paysage
    'format' => 'A4',
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 15,
    'margin_bottom' => 15
]);

$mpdf->WriteHTML($html);

// Afficher directement le PDF dans le navigateur
$mpdf->Output('mp_' . $op['idOp'] . '.pdf', 'I');