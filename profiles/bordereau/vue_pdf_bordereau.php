<?php

session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}

include '../../includes/fonctions.php';
require_once __DIR__ . '/../../vendor/autoload.php';  // mPDF autoload

$idBordereau = $_GET['id'];

$bord = getBordereauById($idBordereau);
$operations = getOperationsByBordereau($idBordereau);

$total = 0;
$lignes = '';

foreach ($operations as $op) {
    $total += $op['montant'];

    $lignes .= '
            <tr>
                <td style="font-size:9px;">' . formatNumOP($op['idOp']) . '</td>

                <td style="font-size:9px;">' . strtoupper($op['objet']) . '</td>

                <td style="font-size:9px;">' . strtoupper($op['nom']) . '</td>

                <td style="font-size:9px; text-align:right;">
                    ' . number_format($op['montant'], 0, ' ', ' ') . '
                </td>
            </tr>';
}
// --- Contenu HTML ---
$html = '
<div style="font-family:Arial; font-size:11px;">

<table width="100%">
    <tr>
        <td width="25%" style="text-align:center;font-size:7px;">
            <strong>REPUBLIQUE DU SENEGAL</strong><br>
            UN PEUPLE - UN BUT - UNE FOI<br>

            <img src="' . $_SERVER['DOCUMENT_ROOT'] . '/BUDGET/assets/images/senegal.png"
                 width="60" height="25"><br>

            MINISTERE DE L\'ENSEIGNEMENT SUPERIEUR
            DE LA RECHERCHE ET DE L\'INNOVATION<br>

            CENTRE DES OEUVRES UNIVERSITAIRES DE DAKAR<br>

            <strong>DEPARTEMENT DU BUDGET</strong>
        </td>

        <td width="50%" style="text-align:center; vertical-align:top;">

            <div style="font-size:18px; font-weight:bold;">
                BORDEREAU DES MANDATS
            </div>

            <br>

            <div style="font-size:13px;">
                N° : <strong>' . $bord['numeroBordereau'] . '</strong>
            </div>

        </td>

        <td width="25%"
            style="text-align:right; font-size:11px; vertical-align:top;">

            <strong>Dakar, le</strong><br>
            ' . date('d/m/Y', strtotime($bord['dateSys'])) . '

        </td>
    </tr>
</table>

<br><br>

<table width="70%"
       align="center"
       border="1"
       cellspacing="0"
       cellpadding="6"
       style="border-collapse:collapse; font-size:10px;">

    <tr>
        <td style="text-align:center; font-weight:bold;">

            BORDEREAU D\'EMISSION DE MANDATS
            DU ' . date('d/m/Y', strtotime($bord['dateSys'])) . '

        </td>
    </tr>

</table>

<br><br>

<table width="100%"
       border="1"
       cellspacing="0"
       cellpadding="3"
       style="border-collapse:collapse; font-size:9px;">

    <thead>

        <tr style="
            font-weight:bold;
            text-align:center;
            background-color:#EDEDED;
        ">
            <th width="18%">Numéro mandat</th>

            <th width="42%">Libellé</th>

            <th width="25%">Bénéficiaire</th>

            <th width="15%">Montant</th>
        </tr>

    </thead>

    <tbody>

        ' . $lignes . '

    </tbody>

</table>

<br>

<table width="100%" style="font-size:10px;">

    <tr>

        <td>
            <strong>Nombre de lignes :</strong>
            ' . count($operations) . '
        </td>

        <td align="right">

            <strong>TOTAL :</strong>

            ' . number_format($total, 0, ' ', ' ') . ' F CFA

        </td>

    </tr>

</table>

<hr>

<table width="100%" style="font-size:10px;">

    <tr>

        <td width="50%" style="text-align:center;">

            <br><br><br>

            <strong></strong>

        </td>

        <td width="50%" style="text-align:center;">

            <br><br><br>

            <strong>Le Directeur</strong>

        </td>

    </tr>

</table>

</div>';

// --- Génération PDF ---

$mpdf = new \Mpdf\Mpdf([
    'orientation' => 'P',
    'format' => 'A4',
    'margin_left' => 12,
    'margin_right' => 12,
    'margin_top' => 12,
    'margin_bottom' => 12,
]);
$mpdf->WriteHTML($html);
$mpdf->SetTitle('Bordereau ' . $bord['numeroBordereau']);
$mpdf->SetDisplayMode('fullpage');

// Afficher directement le PDF dans le navigateur
$mpdf->Output('bord_' . $idBordereau . '.pdf', 'I');
