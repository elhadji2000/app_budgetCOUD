<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit();
}

include '../includes/fonctions.php';

global $connexion;

// ============================================================
// RÉCUPÉRATION DES DONNÉES POUR MODIFICATION
// ============================================================

$idMarche = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = $idMarche > 0;
$marcheData = null;
$documentsExistants = [];

if ($isEdit) {
    // Récupérer les données du marché
    $stmt = mysqli_prepare($connexion, "
        SELECT m.*, f.nom as fournisseur_nom, f.numFourn
        FROM sigm_marches m
        LEFT JOIN bud_fournisseur f ON m.id_fournisseur = f.idFourn
        WHERE m.id = ?
    ");
    
    mysqli_stmt_bind_param($stmt, "i", $idMarche);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $marcheData = mysqli_fetch_assoc($result);
    
    if (!$marcheData) {
        $_SESSION['error'] = "Le marché n'existe pas.";
        header("Location: traitement_marche.php");
        exit();
    }
    
    // Vérifier si le marché peut être modifié
    if ($marcheData['statut'] != 'rejete') {
        $_SESSION['error'] = "Ce marché ne peut plus être modifié.";
        header("Location: traitement_marche.php?id=" . $idMarche);
        exit();
    }
    
    // Récupérer les documents existants
    $stmtDocs = mysqli_prepare($connexion, "
        SELECT * FROM sigm_marche_documents 
        WHERE marche_id = ? 
        ORDER BY id
    ");
    
    mysqli_stmt_bind_param($stmtDocs, "i", $idMarche);
    mysqli_stmt_execute($stmtDocs);
    $resultDocs = mysqli_stmt_get_result($stmtDocs);
    $documentsExistants = mysqli_fetch_all($resultDocs, MYSQLI_ASSOC);
}

$fournisseurs = getAllFournisseurs();

// ============================================================
// INCLUSION DU HEADER (APRÈS TOUTES LES REDIRECTIONS)
// ============================================================
include '../includes/header.php';
?>

<!-- Le reste du code HTML ici -->

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'Modification' : 'Enregistrement' ?> du marché</title>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    /* ========== STYLES COMMUNS ========== */
    .card-simple {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .section-title {
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50;
        border-bottom: 2px solid #4655a4;
        padding-bottom: 8px;
        margin-bottom: 18px;
        letter-spacing: 0.3px;
    }

    .section-title i {
        color: #4655a4;
        margin-right: 6px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 4px;
        color: #2c3e50;
    }

    .form-control,
    .form-select {
        font-size: 13px;
        border: 1.5px solid #ced4da;
        border-radius: 6px;
        padding: 6px 12px;
        transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        background-color: #fafbfc;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #4655a4;
        box-shadow: 0 0 0 3px rgba(70, 85, 164, 0.15);
        background-color: #ffffff;
    }

    .form-control:hover,
    .form-select:hover {
        border-color: #6c7ac0;
    }

    .btn {
        font-size: 13px;
        font-weight: 500;
        padding: 6px 18px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: #ffffff;
        transform: translateY(-1px);
    }

    .btn-outline-danger:hover {
        transform: translateY(-1px);
    }

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #000;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #d39e00;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
    }

    /* ========== ZONE UPLOAD ========== */
    .upload-zone {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 12px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background-color: #f8f9fa;
    }

    .upload-zone:hover {
        border-color: #4655a4;
        background-color: #f0f4ff;
    }

    .upload-zone.dragover {
        border-color: #4655a4;
        background-color: #e8edff;
    }

    .upload-zone i {
        font-size: 28px;
        color: #6c757d;
        display: block;
    }

    .upload-zone h6 {
        font-size: 13px;
        margin-top: 4px;
        margin-bottom: 2px;
        font-weight: 600;
    }

    .upload-zone p {
        font-size: 11px;
        margin-bottom: 2px;
    }

    .upload-zone .small-text {
        font-size: 10px;
        color: #6c757d;
    }

    /* ========== LISTE DES FICHIERS ========== */
    .file-list {
        margin-top: 10px;
        max-height: 300px;
        overflow-y: auto;
    }

    .file-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 4px 10px;
        background-color: #f8f9fa;
        border-radius: 4px;
        margin-bottom: 3px;
        border-left: 3px solid #4655a4;
    }

    .file-item .file-name {
        font-size: 12px;
        font-weight: 500;
    }

    .file-item .file-size {
        font-size: 10px;
        color: #6c757d;
    }

    .file-item .btn-remove {
        color: #dc3545;
        cursor: pointer;
        background: none;
        border: none;
        font-size: 16px;
        padding: 0 4px;
    }

    .file-item .btn-remove:hover {
        color: #a71d2a;
    }

    .file-preview {
        width: 28px;
        height: 28px;
        border-radius: 4px;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 8px;
        flex-shrink: 0;
    }

    .file-preview i {
        font-size: 14px;
        color: #4655a4;
    }

    .file-preview.pdf i {
        color: #dc3545;
    }

    .file-preview.word i {
        color: #0d6efd;
    }

    .file-preview.excel i {
        color: #198754;
    }

    .file-preview.image i {
        color: #fd7e14;
    }

    .file-item .type-input {
        width: 120px;
        font-size: 11px;
        padding: 2px 8px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        margin: 0 8px;
    }

    .file-item .type-input:focus {
        border-color: #4655a4;
        outline: none;
        box-shadow: 0 0 0 2px rgba(70, 85, 164, 0.15);
    }

    /* ========== PROGRESS BAR ========== */
    .upload-progress {
        height: 3px;
        background-color: #e9ecef;
        border-radius: 2px;
        margin-top: 6px;
        overflow: hidden;
    }

    .upload-progress .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #4655a4, #6c7ac9);
        border-radius: 2px;
        width: 0%;
        transition: width 0.3s ease;
        font-size: 0;
    }

    /* ========== FILE INPUT HIDDEN ========== */
    #fileInput {
        display: none;
    }

    /* ========== FORM ROW ========== */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .form-row .full-width {
        grid-column: 1 / -1;
    }

    /* ========== EXISTING FILES ========== */
    .existing-file {
        border-left-color: #28a745 !important;
        background-color: #f0fff4 !important;
    }

    .existing-file .badge-existing {
        font-size: 8px;
        background-color: #28a745;
        color: white;
        padding: 1px 6px;
        border-radius: 10px;
        margin-left: 5px;
    }

    .doc-type-badge {
        font-size: 9px;
        background-color: #6c757d;
        color: white;
        padding: 1px 8px;
        border-radius: 10px;
        margin-left: 5px;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .form-row .full-width {
            grid-column: 1;
        }

        .file-item .type-input {
            width: 80px;
            font-size: 10px;
        }

        .upload-zone {
            padding: 10px 15px;
        }

        .upload-zone i {
            font-size: 22px;
        }
    }
    </style>
</head>

<body>

    <main class="container mt-3 mb-4">

        <!-- =====================================================
     AFFICHAGE DES MESSAGES DE SESSION
====================================================== -->

        <?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success']) && !empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['warning']) && !empty($_SESSION['warning'])): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= $_SESSION['warning'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['warning']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['info']) && !empty($_SESSION['info'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>
            <?= $_SESSION['info'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['info']); ?>
        <?php endif; ?>

        <!-- =====================================================
     TITRE
    ====================================================== -->

        <div class="card card-simple mb-3">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1">
                            <i class="bi <?= $isEdit ? 'bi-pencil-square' : 'bi-file-earmark-text' ?> me-2"></i>
                            <?= $isEdit ? 'Modification' : 'Enregistrement' ?> des documents du marché
                        </h5>
                        <small class="text-muted">
                            <?= $isEdit ? 'Modifier les informations et documents du marché' : 'Joindre tous les documents requis pour le marché' ?>
                        </small>
                    </div>
                    <span
                        class="badge <?= $isEdit ? 'bg-warning text-dark' : 'bg-primary bg-opacity-10 text-primary' ?> px-3 py-2">
                        <i class="bi <?= $isEdit ? 'bi-pencil' : 'bi-file-earmark' ?> me-1"></i>
                        <?= $isEdit ? 'Modification' : 'Nouveau' ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- =====================================================
     FORMULAIRE
    ====================================================== -->

        <div class="card card-simple">
            <div class="card-body">

                <form id="marcheForm" method="POST" action="enregistrer_marche" enctype="multipart/form-data">

                    <?php if ($isEdit): ?>
                    <input type="hidden" name="idMarche" value="<?= $idMarche ?>">
                    <?php endif; ?>

                    <!-- ===== INFOS MARCHÉ ===== -->
                    <div class="section-title">
                        <i class="bi bi-info-circle me-1"></i>
                        Informations du marché
                    </div>

                    <div class="form-row">

                        <!-- Fournisseur -->
                        <div class="full-width mb-2">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-building me-1"></i> Fournisseur
                                <span class="text-danger">*</span>
                            </label>
                            <select name="idFourn" id="idFourn" class="form-select" required>
                                <option value="">Sélectionner un fournisseur</option>
                                <?php if (!empty($fournisseurs)):
                                foreach ($fournisseurs as $f): ?>
                                <option value="<?= $f['idFourn'] ?>"
                                    <?= ($isEdit && $f['idFourn'] == $marcheData['id_fournisseur']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($f['numFourn'] . ' - ' . $f['nom']) ?>
                                </option>
                                <?php endforeach;
                            endif; ?>
                            </select>
                            <div class="invalid-feedback">
                                Veuillez sélectionner un fournisseur.
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-plus-circle me-1"></i>
                                <a href="../profiles/fournisseurs/add_fourn.php" target="_blank">Ajouter un nouveau
                                    fournisseur</a>
                            </small>
                        </div>

                        <!-- Montant -->
                        <div class="mb-2">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-coin me-1"></i> Montant
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light" style="font-size:12px;">FCFA</span>
                                <input type="number" name="montant" id="montant" class="form-control"
                                    placeholder="Saisir le montant" step="1000" min="0"
                                    value="<?= $isEdit ? htmlspecialchars($marcheData['montant']) : '' ?>" required>
                            </div>
                            <div class="invalid-feedback">
                                Veuillez saisir un montant valide.
                            </div>
                        </div>

                        <!-- Type -->
                        <div class="mb-2">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-tags me-1"></i> Type de marché
                                <span class="text-danger">*</span>
                            </label>
                            <select name="type" id="type" class="form-select" required>
                                <option value="">Sélectionner</option>
                                <option value="Fourniture de biens"
                                    <?= ($isEdit && $marcheData['type_marche'] == 'Fourniture de biens') ? 'selected' : '' ?>>
                                    Fourniture de biens</option>
                                <option value="Services"
                                    <?= ($isEdit && $marcheData['type_marche'] == 'Services') ? 'selected' : '' ?>>
                                    Services</option>
                                <option value="Travaux"
                                    <?= ($isEdit && $marcheData['type_marche'] == 'Travaux') ? 'selected' : '' ?>>
                                    Travaux</option>
                                <option value="Prestation intellectuelle"
                                    <?= ($isEdit && $marcheData['type_marche'] == 'Prestation intellectuelle') ? 'selected' : '' ?>>
                                    Prestation intellectuelle</option>
                            </select>
                            <div class="invalid-feedback">
                                Veuillez sélectionner un type.
                            </div>
                        </div>

                        <!-- Référence -->
                        <div class="mb-2">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-hash me-1"></i> Référence
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="reference" id="reference" class="form-control"
                                placeholder="Ex: MKT-2026-001"
                                value="<?= $isEdit ? htmlspecialchars($marcheData['reference']) : '' ?>" required>
                            <div class="invalid-feedback">
                                Veuillez saisir une référence.
                            </div>
                        </div>

                        <!-- Objet (pleine largeur) -->
                        <div class="full-width mb-2">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-file-text me-1"></i> Objet du marché
                                <span class="text-danger">*</span>
                            </label>
                            <textarea name="objet" id="objet" rows="2" class="form-control"
                                placeholder="Décrire l'objet du marché..."
                                required><?= $isEdit ? htmlspecialchars($marcheData['objet']) : '' ?></textarea>
                            <div class="invalid-feedback">
                                Veuillez décrire l'objet du marché.
                            </div>
                        </div>

                    </div>

                    <!-- ===== GESTION DES DOCUMENTS ===== -->
                    <div class="section-title mt-3">
                        <i class="bi bi-files me-1"></i>
                        Documents
                        <span class="badge bg-warning text-dark ms-2" style="font-size:10px;">2 à 10 fichiers</span>
                    </div>

                    <?php if ($isEdit && !empty($documentsExistants)): ?>
                    <!-- ===== DOCUMENTS EXISTANTS ===== -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-check-circle-fill text-success me-1"></i>
                            Documents existants (<?= count($documentsExistants) ?>)
                        </label>
                        <div class="file-list" id="existingFileList">
                            <?php foreach ($documentsExistants as $doc): ?>
                            <div class="file-item existing-file" data-doc-id="<?= $doc['id'] ?>">
                                <div class="d-flex align-items-center flex-grow-1" style="overflow:hidden;">
                                    <div class="file-preview <?= $doc['extension'] ?>">
                                        <i class="bi bi-file-earmark"></i>
                                    </div>
                                    <div class="flex-grow-1" style="min-width:0;">
                                        <div class="file-name text-truncate">
                                            <?= htmlspecialchars($doc['nom_original']) ?>
                                            <span class="badge-existing">existant</span>
                                            <span
                                                class="doc-type-badge"><?= htmlspecialchars($doc['type_document']) ?></span>
                                        </div>
                                        <div class="file-size">
                                            <?= formatSize($doc['taille']) ?>
                                        </div>
                                    </div>
                                    <input type="text" class="type-input" placeholder="Modifier type"
                                        data-doc-id="<?= $doc['id'] ?>"
                                        value="<?= htmlspecialchars($doc['type_document']) ?>">
                                    <input type="hidden" name="existing_doc_ids[]" value="<?= $doc['id'] ?>">
                                </div>
                                <button type="button" class="btn-remove remove-existing" data-doc-id="<?= $doc['id'] ?>"
                                    data-filename="<?= htmlspecialchars($doc['nom_original']) ?>"
                                    title="Supprimer ce document">
                                    <i class="bi bi-x-circle-fill"></i>
                                </button>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- ===== UPLOAD NOUVEAUX DOCUMENTS ===== -->
                    <div class="mb-2">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-upload me-1"></i>
                            <?= $isEdit ? 'Ajouter de nouveaux documents' : 'Documents joints' ?>
                            <?php if ($isEdit): ?>
                            <span class="badge bg-info text-dark ms-2" style="font-size:10px;">Optionnel</span>
                            <?php endif; ?>
                        </label>

                        <!-- Zone de dépôt -->
                        <div class="upload-zone" id="uploadZone">
                            <i class="bi bi-cloud-upload"></i>
                            <h6>Glissez-déposez ou <strong>cliquez</strong></h6>
                            <p>PDF, DOC, DOCX, XLS, XLSX, JPG, PNG</p>
                            <span class="small-text">Taille max : 10 MB par fichier</span>
                        </div>

                        <!-- Champ de fichier caché -->
                        <input type="file" id="fileInput" name="<?= $isEdit ? 'new_files[]' : 'files[]' ?>" multiple
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">

                        <!-- Barre de progression -->
                        <div class="upload-progress">
                            <div class="progress-bar" id="progressBar"></div>
                        </div>

                        <!-- Liste des fichiers -->
                        <div class="file-list" id="fileList">
                            <p class="text-muted text-center my-2" style="font-size:12px;">
                                <?= $isEdit ? 'Aucun nouveau fichier ajouté' : 'Aucun fichier ajouté' ?>
                            </p>
                        </div>
                    </div>

                    <!-- ===== BOUTONS ===== -->
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                        <button type="button" class="btn btn-outline-danger btn-sm" id="clearAllBtn">
                            <i class="bi bi-trash me-1"></i> Tout effacer
                        </button>
                        <div>
                            <button type="button" class="btn btn-outline-secondary btn-sm me-2"
                                onclick="history.back()">
                                <i class="bi bi-x-circle me-1"></i> Annuler
                            </button>
                            <button type="submit" class="btn <?= $isEdit ? 'btn-warning' : 'btn-success' ?> btn-sm"
                                id="submitBtn">
                                <i class="bi <?= $isEdit ? 'bi-pencil' : 'bi-check-circle' ?> me-1"></i>
                                <?= $isEdit ? 'Mettre à jour' : 'Enregistrer' ?>
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>

    </main>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script>
    $(document).ready(function() {

        // ========== CONFIGURATION ==========
        const MAX_FILES = 10;
        const MIN_FILES = 2;
        const IS_EDIT = <?= $isEdit ? 'true' : 'false' ?>;
        let existingCount = <?= $isEdit ? count($documentsExistants) : 0 ?>;
        let uploadedFiles = [];
        let deletedDocIds = [];

        // ========== RÉFÉRENCES DOM ==========
        const $uploadZone = $('#uploadZone');
        const $fileInput = $('#fileInput');
        const $fileList = $('#fileList');
        const $progressBar = $('#progressBar');
        const $clearAllBtn = $('#clearAllBtn');
        const $submitBtn = $('#submitBtn');
        const $marcheForm = $('#marcheForm');
        const $idFourn = $('#idFourn');
        const $montant = $('#montant');
        const $type = $('#type');
        const $objet = $('#objet');
        const $reference = $('#reference');

        // ========== FONCTIONS ==========

        function generateId() {
            return Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        }

        function getFileIcon(filename) {
            const ext = filename.split('.').pop().toLowerCase();
            const icons = {
                'pdf': 'bi-filetype-pdf',
                'doc': 'bi-filetype-doc',
                'docx': 'bi-filetype-docx',
                'xls': 'bi-filetype-xls',
                'xlsx': 'bi-filetype-xlsx',
                'jpg': 'bi-filetype-jpg',
                'jpeg': 'bi-filetype-jpg',
                'png': 'bi-filetype-png'
            };
            return icons[ext] || 'bi-file-earmark';
        }

        function getFileColor(filename) {
            const ext = filename.split('.').pop().toLowerCase();
            if (['pdf'].includes(ext)) return 'pdf';
            if (['doc', 'docx'].includes(ext)) return 'word';
            if (['xls', 'xlsx'].includes(ext)) return 'excel';
            if (['jpg', 'jpeg', 'png'].includes(ext)) return 'image';
            return '';
        }

        function formatSize(bytes) {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function renderFileList() {
            if (uploadedFiles.length === 0) {
                $fileList.html('<p class="text-muted text-center my-2" style="font-size:12px;">' +
                    (IS_EDIT ? 'Aucun nouveau fichier ajouté' : 'Aucun fichier ajouté') +
                    '</p>');
                return;
            }

            let html = '';
            uploadedFiles.forEach(function(file, index) {
                const icon = getFileIcon(file.name);
                const colorClass = getFileColor(file.name);
                html += `
                <div class="file-item" data-index="${index}">
                    <div class="d-flex align-items-center flex-grow-1" style="overflow:hidden;">
                        <div class="file-preview ${colorClass}">
                            <i class="bi ${icon}"></i>
                        </div>
                        <div class="flex-grow-1" style="min-width:0;">
                            <div class="file-name text-truncate">${file.name}</div>
                            <div class="file-size">${formatSize(file.size)}</div>
                        </div>
                        <input type="text" class="type-input" placeholder="Type" 
                               data-file-id="${file.id}" value="${file.typeId || ''}">
                    </div>
                    <button type="button" class="btn-remove" data-file-id="${file.id}" title="Supprimer">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </div>
            `;
            });
            $fileList.html(html);

            // Suppression
            $fileList.find('.btn-remove').on('click', function() {
                const fileId = $(this).data('file-id');
                removeFile(fileId);
            });

            // Type
            $fileList.find('.type-input').on('change keyup', function() {
                const fileId = $(this).data('file-id');
                const file = uploadedFiles.find(f => f.id === fileId);
                if (file) {
                    file.typeId = $(this).val().trim();
                }
            });
        }

        function addFiles(files) {
            const totalCurrent = existingCount + uploadedFiles.length;
            const available = MAX_FILES - totalCurrent;

            if (available <= 0) {
                alert('Vous avez déjà atteint le nombre maximum de fichiers (10).');
                return;
            }

            const toAdd = Array.from(files).slice(0, available);

            toAdd.forEach(function(file) {
                const validExts = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];
                const ext = file.name.split('.').pop().toLowerCase();
                if (!validExts.includes(ext)) {
                    alert(`Le fichier "${file.name}" n'est pas supporté.`);
                    return;
                }

                if (file.size > 10 * 1024 * 1024) {
                    alert(`Le fichier "${file.name}" dépasse 10 MB.`);
                    return;
                }

                uploadedFiles.push({
                    id: generateId(),
                    name: file.name,
                    size: file.size,
                    type: file.type,
                    file: file,
                    typeId: ''
                });
            });

            updateProgress();
            renderFileList();
            $fileInput.val('');
        }

        function removeFile(fileId) {
            uploadedFiles = uploadedFiles.filter(f => f.id !== fileId);
            updateProgress();
            renderFileList();
        }

        function updateProgress() {
            const total = existingCount + uploadedFiles.length;
            const percentage = (total / MAX_FILES) * 100;
            $progressBar.css('width', Math.min(percentage, 100) + '%');
        }

        function clearAllFiles() {
            if (uploadedFiles.length === 0) return;
            if (confirm('Voulez-vous vraiment effacer tous les fichiers ?')) {
                uploadedFiles = [];
                updateProgress();
                renderFileList();
            }
        }

        // ========== GESTION DES DOCUMENTS EXISTANTS (en modification) ==========

        if (IS_EDIT) {
            // Supprimer un document existant
            $(document).on('click', '.remove-existing', function() {
                const docId = $(this).data('doc-id');
                const filename = $(this).data('filename');

                if (confirm(
                        `Voulez-vous vraiment supprimer le document "${filename}" ? Cette action est irréversible.`
                    )) {
                    deletedDocIds.push(docId);
                    $(this).closest('.file-item').fadeOut(300, function() {
                        $(this).remove();
                        existingCount--;
                        updateProgress();

                        // Mettre à jour les champs cachés
                        $('.existing-file').each(function() {
                            if (!deletedDocIds.includes($(this).data('doc-id'))) {
                                if ($(this).find('input[name="existing_doc_ids[]"]')
                                    .length === 0) {
                                    $(this).append(
                                        `<input type="hidden" name="existing_doc_ids[]" value="${$(this).data('doc-id')}">`
                                    );
                                }
                            }
                        });
                    });
                }
            });

            // Mettre à jour les types des documents existants
            $(document).on('change keyup', '.existing-file .type-input', function() {
                const docId = $(this).data('doc-id');
                const newType = $(this).val().trim();

                let $hiddenInput = $(`input[name="existing_types[${docId}]"]`);
                if ($hiddenInput.length === 0) {
                    $hiddenInput = $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', `existing_types[${docId}]`);
                    $(this).closest('.file-item').append($hiddenInput);
                }
                $hiddenInput.val(newType);
            });
        }

        // ========== VALIDATION ==========

        function validateForm() {
            if (!$idFourn.val()) {
                alert('Veuillez sélectionner un fournisseur.');
                $idFourn.focus();
                return false;
            }

            if (!$montant.val() || parseFloat($montant.val()) <= 0) {
                alert('Veuillez saisir un montant valide.');
                $montant.focus();
                return false;
            }

            if (!$type.val()) {
                alert('Veuillez sélectionner un type de marché.');
                $type.focus();
                return false;
            }

            if (!$objet.val().trim()) {
                alert('Veuillez saisir l\'objet du marché.');
                $objet.focus();
                return false;
            }

            if (!$reference.val().trim()) {
                alert('Veuillez saisir la référence du marché.');
                $reference.focus();
                return false;
            }

            // Vérifier le nombre total de documents
            const totalDocs = existingCount + uploadedFiles.length;
            if (totalDocs < MIN_FILES) {
                alert(`Veuillez avoir au minimum ${MIN_FILES} documents. (${totalDocs}/${MIN_FILES})`);
                return false;
            }

            // Vérifier les types des nouveaux fichiers
            const untyped = uploadedFiles.some(f => !f.typeId || f.typeId.trim() === '');
            if (untyped) {
                alert('Veuillez saisir un type pour chaque nouveau fichier.');
                return false;
            }

            return true;
        }

        // ========== SOUMISSION ==========

        function addFilesToForm() {
            if (!IS_EDIT) {
                // Mode création
                $marcheForm.find('input[name="files[]"]').remove();
                $marcheForm.find('input[name="types[]"]').remove();

                uploadedFiles.forEach(function(f) {
                    const fileInput = document.createElement('input');
                    fileInput.type = 'file';
                    fileInput.name = 'files[]';
                    fileInput.style.display = 'none';

                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(f.file);
                    fileInput.files = dataTransfer.files;
                    $marcheForm.append(fileInput);

                    const typeInput = document.createElement('input');
                    typeInput.type = 'hidden';
                    typeInput.name = 'types[]';
                    typeInput.value = f.typeId;
                    $marcheForm.append(typeInput);
                });
            } else {
                // Mode modification
                $marcheForm.find('input[name="new_files[]"]').remove();
                $marcheForm.find('input[name="new_types[]"]').remove();

                uploadedFiles.forEach(function(f) {
                    const fileInput = document.createElement('input');
                    fileInput.type = 'file';
                    fileInput.name = 'new_files[]';
                    fileInput.style.display = 'none';

                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(f.file);
                    fileInput.files = dataTransfer.files;
                    $marcheForm.append(fileInput);

                    const typeInput = document.createElement('input');
                    typeInput.type = 'hidden';
                    typeInput.name = 'new_types[]';
                    typeInput.value = f.typeId;
                    $marcheForm.append(typeInput);
                });

                // Ajouter les IDs supprimés
                deletedDocIds.forEach(function(id) {
                    $marcheForm.append(`<input type="hidden" name="deleted_doc_ids[]" value="${id}">`);
                });
            }
        }

        $marcheForm.on('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                return false;
            }

            addFilesToForm();

            $submitBtn
                .prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm me-2"></span>' +
                    (IS_EDIT ? 'Mise à jour...' : 'Enregistrement...'));

            return true;
        });

        // ========== ÉVÉNEMENTS UPLOAD ==========

        $uploadZone.on('click', function() {
            $fileInput.click();
        });

        $uploadZone.on('dragover dragenter', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('dragover');
        });

        $uploadZone.on('dragleave dragend', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');
        });

        $uploadZone.on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');
            const files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                addFiles(files);
            }
        });

        $fileInput.on('change', function() {
            if (this.files.length > 0) {
                addFiles(this.files);
            }
        });

        $clearAllBtn.on('click', clearAllFiles);

        // ========== INITIALISATION ==========
        renderFileList();
        updateProgress();

    });
    </script>

    <?php include '../includes/footer.php';?>