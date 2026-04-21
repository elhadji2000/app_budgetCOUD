<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
ini_set('display_errors', 0);

session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index');
    exit();
}

require '../../vendor/autoload.php';
include '../../includes/fonctions.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 *  Conversion date simple et robuste
 */
function formatDateExcel($date)
{
    if (empty($date)) return false;

    // Excel numeric date
    if (is_numeric($date)) {
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d');
    }

    // format US: 1/15/2025
    if (strpos($date, '/') !== false) {
        $p = explode('/', $date);
        if (count($p) == 3) {
            return $p[2] . '-' . str_pad($p[0], 2, '0', STR_PAD_LEFT) . '-' . str_pad($p[1], 2, '0', STR_PAD_LEFT);
        }
    }

    // fallback
    $t = strtotime($date);
    return $t ? date('Y-m-d', $t) : false;
}

$success = 0;
$errors = [];

if (isset($_FILES['excel_file'])) {

    $fileTmp = $_FILES['excel_file']['tmp_name'];

    try {

        $spreadsheet = IOFactory::load($fileTmp);
        $rows = $spreadsheet->getActiveSheet()->toArray();

        foreach ($rows as $index => $row) {

            if ($index == 0) continue;

            $numCompte = trim($row[0] ?? '');
            $dateBrut  = trim($row[1] ?? '');
            $montant   = trim($row[2] ?? '');

            $type = "initiale";

            // conversion date
            $date = formatDateExcel($dateBrut);

            // validation simple
            if (!$numCompte || !$date || !is_numeric($montant)) {
                $errors[] = "Ligne " . ($index + 1) . " : données invalides ($dateBrut)";
                continue;
            }

            $idCompte = getIdCompteByNum($numCompte);

            if (!$idCompte) {
                $errors[] = "Ligne " . ($index + 1) . " : compte introuvable ($numCompte)";
                continue;
            }

            //  IMPORTANT : on NE vérifie plus ici
            // la vérification est déjà dans enregistrerDotation()

            $result = enregistrerDotation($idCompte, $date, $montant, $type);

            if ($result === true) {
                $success++;
            } else {
                $errors[] = "Ligne " . ($index + 1) . " : " . $result;
            }
        }

        $_SESSION['import_success'] = $success;
        $_SESSION['import_errors'] = $errors;

        header("Location: add_ini_dot");
        exit();

    } catch (Exception $e) {
        header("Location: add_ini_dot?import_errors=" . urlencode($e->getMessage()));
        exit();
    }
}