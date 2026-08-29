<?php
session_start();

require_once '../includes/fonctions.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit();
}

global $connexion;

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: liste_marches.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Récupération du marché avec le fournisseur
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT 
        m.*,
        f.nom AS nom_fournisseur,
        f.numFourn AS num_fournisseur,
        f.contact AS contact_fournisseur,
        f.ninea AS ninea_fournisseur,
        COUNT(d.id) AS nombre_documents,
        SUM(CASE WHEN d.statut = 'valide' THEN 1 ELSE 0 END) AS documents_valides,
        SUM(CASE WHEN d.statut = 'rejete' THEN 1 ELSE 0 END) AS documents_rejetes,
        SUM(CASE WHEN d.statut = 'en_attente' THEN 1 ELSE 0 END) AS documents_attente
    FROM sigm_marches m
    LEFT JOIN bud_fournisseur f ON f.idFourn = m.id_fournisseur
    LEFT JOIN sigm_marche_documents d ON d.marche_id = m.id
    WHERE m.id = ?
    GROUP BY m.id
";

$stmt = mysqli_prepare($connexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$marche = mysqli_fetch_assoc($result);

if (!$marche) {
    $_SESSION['error'] = "Dossier de marché introuvable.";
    header("Location: liste_marches.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Récupération des documents
|--------------------------------------------------------------------------
*/

$sqlDocs = "
    SELECT *
    FROM sigm_marche_documents
    WHERE marche_id = ?
    ORDER BY date_upload ASC
";

$stmtDocs = mysqli_prepare($connexion, $sqlDocs);
mysqli_stmt_bind_param($stmtDocs, "i", $id);
mysqli_stmt_execute($stmtDocs);
$documents = mysqli_stmt_get_result($stmtDocs);

include '../includes/header.php';
?>

<style>
    /* ========== STYLES COMMUNS AVEC LES AUTRES PAGES ========== */
    .card-simple {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
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

    .info-label {
        color: #6c757d;
        font-size: 11px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .info-value {
        font-size: 14px;
        font-weight: 500;
        color: #2c3e50;
        margin-top: 2px;
    }

    .statut-badge {
        font-size: 12px;
        padding: 4px 14px;
        border-radius: 20px;
        font-weight: 500;
        display: inline-block;
    }

    .statut-badge.en_attente {
        background: #fff3cd;
        color: #856404;
    }

    .statut-badge.valide {
        background: #d4edda;
        color: #155724;
    }

    .statut-badge.annule {
        background: #f8d7da;
        color: #721c24;
    }

    .statut-badge.rejete {
        background: #f8d7da;
        color: #721c24;
    }

    .document-item {
        border-bottom: 1px solid #f0f0f0;
        padding: 10px 0;
    }

    .document-item:last-child {
        border-bottom: none;
    }

    .document-name {
        font-size: 13px;
        font-weight: 500;
    }

    .document-meta {
        font-size: 11px;
        color: #6c757d;
    }

    .document-status {
        font-size: 10px;
        padding: 2px 10px;
        border-radius: 12px;
        font-weight: 500;
    }

    .document-status.valide {
        background: #d4edda;
        color: #155724;
    }

    .document-status.en_attente {
        background: #fff3cd;
        color: #856404;
    }

    .document-status.rejete {
        background: #f8d7da;
        color: #721c24;
    }

    .btn-action {
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 6px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-action.btn-validate {
        background: #28a745;
        color: #fff;
    }

    .btn-action.btn-validate:hover {
        background: #218838;
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-action.btn-cancel {
        background: #dc3545;
        color: #fff;
    }

    .btn-action.btn-cancel:hover {
        background: #c82333;
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-action.btn-edit {
        background: #4655a4;
        color: #fff;
    }

    .btn-action.btn-edit:hover {
        background: #35438a;
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-action.btn-back {
        background: #6c757d;
        color: #fff;
    }

    .btn-action.btn-back:hover {
        background: #5a6268;
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-action.btn-danger {
        background: #dc3545;
        color: #fff;
    }

    .btn-action.btn-danger:hover {
        background: #c82333;
        color: #fff;
        transform: translateY(-1px);
    }

    .fournisseur-info .nom {
        font-weight: 600;
        font-size: 14px;
    }

    .fournisseur-info .num {
        font-size: 12px;
        color: #6c757d;
    }

    .doc-count {
        font-size: 13px;
    }

    .doc-count .total {
        font-weight: 600;
    }

    .doc-count .valide {
        color: #28a745;
    }

    .doc-count .attente {
        color: #ffc107;
    }

    .doc-count .rejete {
        color: #dc3545;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .info-grid .full-width {
        grid-column: 1 / -1;
    }

    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .info-grid .full-width {
            grid-column: 1;
        }

        .info-value {
            font-size: 13px;
        }

        .document-item {
            padding: 8px 0;
        }
    }
</style>

<main class="container-fluid mt-3">

    <!-- =====================================================
     EN-TÊTE
    ====================================================== -->

    <div class="card card-simple mb-3">
        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold text-dark mb-1">
                        <i class="bi bi-folder2-open me-2"></i>
                        Détail du marché
                    </h5>
                    <small class="text-muted">
                        <?= htmlspecialchars($marche['reference']) ?>
                        <?php if (!empty($marche['annee'])): ?>
                            · <?= htmlspecialchars($marche['annee']) ?>
                        <?php endif; ?>
                        · Fournisseur : <?= htmlspecialchars($marche['nom_fournisseur'] ?? 'Non défini') ?>
                    </small>
                </div>
                <div>
                    <span class="statut-badge <?= $marche['statut'] ?>">
                        <?php
                        $statuts = [
                            'en_attente' => 'En attente',
                            'valide' => 'Validé',
                            'annule' => 'Annulé',
                            'rejete' => 'Rejeté'
                        ];
                        echo $statuts[$marche['statut']] ?? $marche['statut'];
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- =====================================================
     CONTENU
    ====================================================== -->

    <div class="row g-3">

        <!-- =================================================
         INFORMATIONS
        ================================================== -->

        <div class="col-lg-5">
            <div class="card card-simple">
                <div class="card-body">

                    <div class="section-title">
                        <i class="bi bi-info-circle"></i>
                        Informations du marché
                    </div>

                    <div class="info-grid">

                        <!-- Référence -->
                        <div>
                            <div class="info-label">Référence</div>
                            <div class="info-value"><?= htmlspecialchars($marche['reference']) ?></div>
                        </div>

                        <!-- Année -->
                        <div>
                            <div class="info-label">Année</div>
                            <div class="info-value"><?= htmlspecialchars($marche['annee']) ?></div>
                        </div>

                        <!-- Type -->
                        <div>
                            <div class="info-label">Type de marché</div>
                            <div class="info-value"><?= htmlspecialchars($marche['type_marche']) ?></div>
                        </div>

                        <!-- Montant -->
                        <div>
                            <div class="info-label">Montant</div>
                            <div class="info-value">
                                <?= number_format($marche['montant'], 0, ',', ' ') ?> FCFA
                            </div>
                        </div>

                        <!-- Date -->
                        <div>
                            <div class="info-label">Date de création</div>
                            <div class="info-value">
                                <?= date('d/m/Y à H:i', strtotime($marche['date_creation'])) ?>
                            </div>
                        </div>

                        <!-- Documents -->
                        <div>
                            <div class="info-label">Documents</div>
                            <div class="info-value doc-count">
                                <span class="total"><?= (int) $marche['nombre_documents'] ?></span>
                                <?php if ($marche['documents_valides'] > 0): ?>
                                    <span class="valide" title="Validés">✓ <?= (int) $marche['documents_valides'] ?></span>
                                <?php endif; ?>
                                <?php if ($marche['documents_attente'] > 0): ?>
                                    <span class="attente" title="En attente">⏰ <?= (int) $marche['documents_attente'] ?></span>
                                <?php endif; ?>
                                <?php if ($marche['documents_rejetes'] > 0): ?>
                                    <span class="rejete" title="Rejetés">✗ <?= (int) $marche['documents_rejetes'] ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Objet -->
                        <div class="full-width">
                            <div class="info-label">Objet</div>
                            <div class="info-value">
                                <?= nl2br(htmlspecialchars($marche['objet'])) ?>
                            </div>
                        </div>
                         <!-- motif -->

                         <?php if ($marche['statut'] === 'annule'): ?>
                            
                        <div class="full-width">
                            <div class="info-label">Motif d'annulation</div>
                            <div class="info-value">
                                <?= nl2br(htmlspecialchars($marche['motif_annulation'])) ?>
                            </div>
                        </div>
                        <?php endif; ?>

                    </div>

                    <!-- =====================================
                     FOURNISSEUR
                    ====================================== -->

                    <?php if ($marche['nom_fournisseur']): ?>
                        <div class="section-title mt-3">
                            <i class="bi bi-building"></i>
                            Fournisseur
                        </div>

                        <div class="fournisseur-info">
                            <div class="nom"><?= htmlspecialchars($marche['nom_fournisseur']) ?></div>
                            <div class="num">
                                <?php if ($marche['num_fournisseur']): ?>
                                    Numéro : <?= htmlspecialchars($marche['num_fournisseur']) ?>
                                <?php endif; ?>
                                <?php if ($marche['contact_fournisseur']): ?>
                                    · Contact : <?= htmlspecialchars($marche['contact_fournisseur']) ?>
                                <?php endif; ?>
                                <?php if ($marche['ninea_fournisseur']): ?>
                                    · NINEA : <?= htmlspecialchars($marche['ninea_fournisseur']) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <!-- =================================================
         DOCUMENTS
        ================================================== -->

        <div class="col-lg-7">
            <div class="card card-simple">
                <div class="card-body">

                    <div class="section-title">
                        <i class="bi bi-files"></i>
                        Documents joints
                        <span class="badge bg-secondary ms-2"><?= (int) $marche['nombre_documents'] ?></span>
                    </div>

                    <?php if (mysqli_num_rows($documents) > 0): ?>
                        <?php while ($doc = mysqli_fetch_assoc($documents)): ?>
                            <div class="document-item">
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <div class="d-flex align-items-center gap-2 flex-grow-1" style="min-width:0;">
                                        <i class="bi bi-file-earmark-text text-secondary"></i>
                                        <div class="flex-grow-1" style="min-width:0;">
                                            <div class="document-name text-truncate">
                                                <?= htmlspecialchars($doc['nom_original']) ?>
                                            </div>
                                            <div class="document-meta">
                                                <?= htmlspecialchars($doc['type_document']) ?>
                                                <?php if (!empty($doc['taille'])): ?>
                                                    · <?= number_format($doc['taille'] / 1024 / 1024, 2) ?> Mo
                                                <?php endif; ?>
                                                · <span class="document-status <?= $doc['statut'] ?>">
                                                    <?= $doc['statut'] === 'en_attente' ? 'En attente' : ($doc['statut'] === 'valide' ? 'Validé' : 'Rejeté') ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="../<?= htmlspecialchars($doc['chemin_fichier']) ?>" target="_blank"
                                        class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-eye"></i> Voir
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-folder-x fs-3 d-block mb-2"></i>
                            <small>Aucun document associé à ce marché.</small>
                        </div>
                    <?php endif; ?>

                    <!-- =====================================
                     ACTIONS
                    ====================================== -->

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <div class="d-flex gap-2">
                            <!-- Retour -->
                            <a href="liste_all" class="btn-action btn-back">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>
                        </div>

                        <div class="d-flex gap-2">
                            <?php if ($marche['statut'] === 'en_attente'): ?>
                                <!-- Valider -->
                                <form method="POST" action="traiter_marche.php" class="m-0">
                                    <input type="hidden" name="marche_id" value="<?= $id ?>">
                                    <input type="hidden" name="action" value="valider">
                                    <button type="submit" class="btn-action btn-validate"
                                        onclick="return confirm('Voulez-vous valider ce dossier de marché ?')">
                                        <i class="bi bi-check-circle"></i> Valider
                                    </button>
                                </form>

                                <!-- Annuler -->
                                <button type="button" class="btn-action btn-cancel" data-bs-toggle="modal"
                                    data-bs-target="#annulationModal">
                                    <i class="bi bi-x-circle"></i> Annuler
                                </button>

                            <?php elseif ($marche['statut'] === 'annule'): ?>

                                <!-- Réactiver / Modifier après annulation -->
                                <a href="add_drp.php?id=<?= $id ?>&reactiver=1" class="btn-action btn-edit">
                                    <i class="bi bi-arrow-counterclockwise"></i> Réactiver
                                </a>

                            <?php endif; ?>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>

</main>

<!-- =========================================================
     MODAL ANNULATION
========================================================= -->

<div class="modal fade" id="annulationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="traiter_marche.php" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-danger me-1"></i>
                    Annulation du marché
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="bi bi-info-circle me-1"></i>
                    Vous êtes sur le point d'annuler le marché <strong><?= htmlspecialchars($marche['reference']) ?></strong>.
                </div>
                <p class="text-muted small">
                    Veuillez indiquer le motif de l'annulation. Cette action est irréversible.
                </p>
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Motif de l'annulation
                        <span class="text-danger">*</span>
                    </label>
                    <textarea name="motif" class="form-control" rows="4" required
                        placeholder="Indiquez clairement le motif de l'annulation..."></textarea>
                    <div class="invalid-feedback">
                        Veuillez indiquer un motif.
                    </div>
                </div>
                <input type="hidden" name="marche_id" value="<?= $id ?>">
                <input type="hidden" name="action" value="annuler">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Fermer
                </button>
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="bi bi-check-circle me-1"></i> Confirmer l'annulation
                </button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>