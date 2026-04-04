<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}

include '../../includes/fonctions.php';
require_once __DIR__ . '/../../vendor/autoload.php';  // mPDF autoload

$numEng = $_GET['id'];
$eng = getEngById($numEng);
$details = getDetailsCompte($eng['numCompte']);

$dateEng = $eng['dateEng'] ?? date('Y-m-d');
$anneeGestion = date('Y', strtotime($dateEng));
$moisGestion = date('m', strtotime($dateEng));

// --- Contenu HTML ---
$html = '
<div style="width:100%; font-family: Arial, sans-serif; font-size:8px;">
    <table width="100%">
        <tr>
            <!-- Bloc gauche : texte et logo centré -->
            <td width="33%" style="text-align:center;font-size:8px;">
                <strong>REPUBLIQUE DU SENEGAL</strong><br>
                UN PEUPLE - UN BUT - UNE FOI<br>
                <img src="' . $_SERVER['DOCUMENT_ROOT'] . '/BUDGET/assets/images/senegal.png" width="60" height="25"><br>
                MINISTERE DE L\'ENSEIGNEMENT SUPERIEUR DE LA RECHERCHE ET DE L\'INNOVATION<br>
                CENTRE DES OEUVRES UNIVERSITAIRES DE DAKAR<br>
                <strong>DEPARTEMENT DU BUDGET</strong>
            </td>

             <td width="34%" style="text-align:center;font-size:12px;vertical-align:top;">
                <h2>BON ENGAGEMENT</h2><br>
                <strong>N° : ' . formatNumEng($eng['idEng']) . '</strong>
            </td>

            <!-- Bloc droit : gestion année/mois -->
            <td width="33%" style="text-align:left;font-size:12px;vertical-align:top;padding-left:15%;">
                <strong>GESTION : ' . $anneeGestion . '</strong><br><br>
                <strong>Dakar le, ' . date('j/m/Y', strtotime($dateEng)) . '</strong>
            </td>
        </tr>
    </table>
    <br><br>
    <!-- Tableau des informations -->
    <table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse;font-size:14px;">
       
        <tr>
            <td width="33%" style="padding:10px;">
            <p style="padding:10px;font-weight:bold;"> DEPENSE:</p>
            <br><br>
            <div style="font-size:12px;" >
            <p> Type : ' . $eng['type_eng'] . '</p>
            <br><br>
            <p> Nature : Fonctionnement</p>
            <br><br>
            <p> Objet : ' . strtoupper($eng['objet']) . '</p>
            <br><br>
            <p> Montant  : ' . number_format($eng['montant'], 0, ' ', ' ') . '</p>
            <br><br>
            <br><br>
            </div>
            </td>
            <td width="33%" style="padding:10px;">
            <p style="padding:10px;font-weight:bold;"> IMPUTATION:</p>
            <br><br>
            <div style="font-size:12px;" >
            <p> Budget : Fonctionnement</p>
            <br><br>
            <p> Numéro compte principal : ' . $eng['numCp'] . "</p>
            <br><br>
            <p> Numéro compte d'imputation  : " . $eng['numCompte'] . '</p>
            <br><br>
            <p> Libelle  : ' . $eng['libelleCp'] . '</p>
            <br><br>
            <div >
            </td>
            <td width="34%" style="text-align:center;font-size:12px;vertical-align:top;">
            <p style="text-decoration: underline;margin:0;font-weight:bold;"> Visa du chef département du budget:</p>
            </td>
        </tr>
    </table>
    <br>
    <table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; font-size:14px;">
       
    <tr>
        <!-- TD 1 -->
        <td width="33%" style="padding:10px; border:1px solid #000;vertical-align:top;">
            <p style="padding:10px;font-weight:bold;"> BENEFICIAIRE:</p>
            <br><br>
            <div style="font-size:14px;">
                <p>' . strtoupper($eng['nom']) . '</p>
                <br><br><br>
                <p style="padding:10px;font-weight:bold;"> SERVICE:</p>
                <br><br><br>
                <p>COUD</p>
                <br><br>
            </div>
            <br><br>
        </td>

        <!-- TD 2 -->
        <td width="33%" style="padding:10px 15px; vertical-align:top; border:1px solid #000;">
    
            <p style="font-weight:bold;">SITUATION DES ENGAGEMENTS :</p>
            <br>
            <table width="100%" style="font-size:12px;" border="0">
                <tr>
                    <td>Crédits ouverts :</td>
                    <td style="text-align:right;">' . number_format($details['dotationTotale'], 0, ' ', ' ') . '</td>
                </tr>
                <tr>
                    <td colspan="3" style="height:15px; border:none;"></td>
                </tr>
                <tr>
                    <td>Modification de crédits :</td>
                    <td style="text-align:right;">0</td>
                </tr>
                <tr>
                    <td colspan="3" style="height:15px; border:none;"></td>
                </tr>
                <tr>
                    <td>Engagements antérieurs :</td>
                    <td style="text-align:right;">' . number_format(($details['totalEngagement'] - $eng['montant']), 0, ' ', ' ') . '</td>
                </tr>
                <tr>
                    <td colspan="3" style="height:15px; border:none;"></td>
                </tr>
                <tr>
                    <td>Annulation d\'engagement :</td>
                    <td style="text-align:right;">0</td>
                </tr>
                <tr>
                    <td colspan="3" style="height:15px; border:none;"></td>
                </tr>
                <tr>
                    <td>Disponible avant bon :</td>
                    <td style="text-align:right;">' . number_format(($details['dotationTotale'] - ($details['totalEngagement'] - $eng['montant'])), 0, ' ', ' ') . '</td>
                </tr>
                <tr>
                    <td colspan="3" style="height:15px; border:none;"></td>
                </tr>
                <tr>
                    <td>Nouveau disponible :</td>
                    <td style="text-align:right; font-weight:bold;">' . number_format(($details['dotationTotale'] - $details['totalEngagement']), 0, ' ', ' ') . '</td>
                </tr>
            </table>

        </td>

        <!-- TD 3 (CORRIGÉ) -->
        <td width="34%" style="text-align:center; font-size:12px; vertical-align:top; border:1px solid #000;">
            <p style="text-decoration: underline; margin:0; font-weight:bold;">
                Signature de l\'Ordonnateur:
            </p>
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
$mpdf->Output('be_' . $eng['idEng'] . '.pdf', 'I');