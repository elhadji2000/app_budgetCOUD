<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}

include '../../includes/fonctions.php';

$idFourn = $_GET['idFourn'] ?? null;
$fournisseur = null;

if ($idFourn) {
    $fournisseur = getFournisseurById($idFourn);
}

include '../../includes/header.php';
?>

<style>
.card-simple {
    border: 1px solid #e0e5ec;
    border-radius: 10px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    background: #ffffff;
}

.card-simple .card-body {
    padding: 1.5rem 1.8rem;
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
    font-size: 14px;
    border: 1.5px solid #ced4da;
    border-radius: 6px;
    padding: 8px 12px;
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

.form-control.is-valid,
.form-select.is-valid {
    border-color: #28a745;
}

.form-control.is-invalid,
.form-select.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    font-size: 12px;
    margin-top: 4px;
}

.btn {
    font-size: 14px;
    font-weight: 500;
    padding: 8px 24px;
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

.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #212529;
}

.btn-warning:hover {
    background-color: #e0a800;
    border-color: #d39e00;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
}

.btn-outline-secondary {
    border-color: #6c757d;
    color: #6c757d;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    color: #ffffff;
    transform: translateY(-1px);
}

.alert {
    border-radius: 8px;
    font-size: 14px;
}

textarea.form-control {
    resize: vertical;
    min-height: 80px;
}

/* Style pour les champs obligatoires */
.required-star {
    color: #dc3545;
    font-weight: 700;
    margin-left: 2px;
}

/* Animation du modal */
.modal.fade .modal-dialog {
    transform: scale(0.95);
    transition: transform 0.25s ease;
}

.modal.show .modal-dialog {
    transform: scale(1);
}

.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
}

.modal-header {
    border-bottom: 1px solid #e9ecef;
    padding: 1rem 1.5rem;
}

.modal-header .modal-title {
    font-size: 16px;
    font-weight: 600;
}

.modal-body {
    padding: 1.5rem;
    font-size: 14px;
}

.modal-footer {
    border-top: 1px solid #e9ecef;
    padding: 0.75rem 1.5rem;
}
</style>

<main class="container mt-3 mb-4">

    <!-- =====================================================
     TITRE
    ====================================================== -->

    <div class="card card-simple mb-3">
        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-person-vcard me-2"></i>
                        <?= $fournisseur ? 'Modifier le fournisseur' : 'Nouveau fournisseur' ?>
                    </h5>
                    <small class="text-muted">
                        <?= $fournisseur
                            ? 'Modification des informations du fournisseur'
                            : 'Enregistrement d\'un nouveau fournisseur'
                        ?>
                    </small>
                </div>
                <?php if ($fournisseur): ?>
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                    <i class="bi bi-hash me-1"></i>
                    #<?= htmlspecialchars($fournisseur['numFourn'] ?? '') ?>
                </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- =====================================================
     MESSAGE ERREUR
    ====================================================== -->

    <?php if (!empty($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show py-2">
        <i class="bi bi-exclamation-circle me-1"></i>
        <?= htmlspecialchars($_GET['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- =====================================================
     FORMULAIRE
    ====================================================== -->

    <div class="card card-simple">
        <div class="card-body">

            <form action="traitement_fourn.php" method="POST" class="needs-validation" novalidate>

                <input type="hidden" name="idFourn" value="<?= htmlspecialchars($fournisseur['idFourn'] ?? '') ?>">

                <!-- =================================================
                 IDENTIFICATION
                ================================================== -->

                <div class="section-title">
                    <i class="bi bi-building me-1"></i>
                    Identification du fournisseur
                </div>

                <div class="row g-3">

                    <!-- NUMERO FOURNISSEUR -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Numéro fournisseur
                            <span class="required-star">*</span>
                        </label>
                        <input type="text" name="numFourn" class="form-control" required
                            value="<?= htmlspecialchars($fournisseur['numFourn'] ?? '') ?>" placeholder="Ex : F001">
                        <div class="invalid-feedback">
                            Veuillez renseigner le numéro fournisseur.
                        </div>
                    </div>

                    <!-- NOM -->
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">
                            Nom / Raison sociale
                            <span class="required-star">*</span>
                        </label>
                        <input type="text" name="nom" class="form-control" required
                            value="<?= htmlspecialchars($fournisseur['nom'] ?? '') ?>"
                            placeholder="Nom ou raison sociale">
                        <div class="invalid-feedback">
                            Veuillez renseigner le nom ou la raison sociale.
                        </div>
                    </div>

                    <!-- NATURE -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Nature
                            <span class="required-star">*</span>
                        </label>
                        <select name="nature" class="form-select" required>
                            <option value="">Sélectionner</option>
                            <option value="Entreprise"
                                <?= (($fournisseur['nature'] ?? '') === 'Entreprise') ? 'selected' : '' ?>>
                                Entreprise
                            </option>
                            <option value="Personne physique"
                                <?= (($fournisseur['nature'] ?? '') === 'Personne physique') ? 'selected' : '' ?>>
                                Personne physique
                            </option>
                            <option value="Association"
                                <?= (($fournisseur['nature'] ?? '') === 'Association') ? 'selected' : '' ?>>
                                Association
                            </option>
                            <option value="Autre" <?= (($fournisseur['nature'] ?? '') === 'Autre') ? 'selected' : '' ?>>
                                Autre
                            </option>
                        </select>
                        <div class="invalid-feedback">
                            Veuillez sélectionner la nature.
                        </div>
                    </div>

                </div>

                <!-- =================================================
                 INFORMATIONS LEGALES
                ================================================== -->

                <div class="section-title mt-4">
                    <i class="bi bi-file-earmark-text me-1"></i>
                    Informations légales
                </div>

                <div class="row g-3">

                    <!-- NINEA -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            NINEA
                            <span class="required-star">*</span>
                        </label>
                        <input type="text" name="ninea" class="form-control" required
                            value="<?= htmlspecialchars($fournisseur['ninea'] ?? '') ?>"
                            placeholder="Numéro NINEA (ex: 1234567890123)">
                        <div class="invalid-feedback">
                            Veuillez renseigner le NINEA.
                        </div>
                    </div>

                    <!-- RCCM -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            RCCM
                            <span class="required-star">*</span>
                        </label>
                        <input type="text" name="rccm" class="form-control" required
                            value="<?= htmlspecialchars($fournisseur['rccm'] ?? '') ?>"
                            placeholder="Numéro du registre de commerce (ex: SN-DKR-2025-12345)">
                        <div class="invalid-feedback">
                            Veuillez renseigner le RCCM.
                        </div>
                    </div>

                </div>

                <!-- =================================================
                 COORDONNEES
                ================================================== -->

                <div class="section-title mt-4">
                    <i class="bi bi-geo-alt me-1"></i>
                    Coordonnées
                </div>

                <div class="row g-3">

                    <!-- ADRESSE -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Adresse
                            <span class="required-star">*</span>
                        </label>
                        <input type="text" name="adresse" class="form-control" required
                            value="<?= htmlspecialchars($fournisseur['adresse'] ?? '') ?>"
                            placeholder="Adresse complète du fournisseur">
                        <div class="invalid-feedback">
                            Veuillez renseigner l'adresse.
                        </div>
                    </div>

                    <!-- CONTACT -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            Contact
                            <span class="required-star">*</span>
                        </label>
                        <input type="text" name="contact" class="form-control" required
                            value="<?= htmlspecialchars($fournisseur['contact'] ?? '') ?>" placeholder="Téléphone">
                        <div class="invalid-feedback">
                            Veuillez renseigner le contact.
                        </div>
                    </div>

                    <!-- EMAIL -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            Email
                        </label>
                        <input type="email" name="email" class="form-control"
                            value="<?= htmlspecialchars($fournisseur['email'] ?? '') ?>"
                            placeholder="exemple@email.com">
                        <div class="invalid-feedback">
                            Veuillez renseigner un email valide.
                        </div>
                    </div>

                </div>

                <!-- =================================================
                 INFORMATIONS SUPPLEMENTAIRES
                ================================================== -->

                <div class="section-title mt-4">
                    <i class="bi bi-info-circle me-1"></i>
                    Informations supplémentaires
                </div>

                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">
                            Observations
                        </label>
                        <textarea name="observations" class="form-control" rows="3"
                            placeholder="Informations ou remarques complémentaires..."><?= htmlspecialchars($fournisseur['observations'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- =================================================
                 BOUTONS
                ================================================== -->

                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="javascript:history.back()" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Annuler
                    </a>
                    <button type="submit" class="btn <?= $fournisseur ? 'btn-warning' : 'btn-success' ?>">
                        <i class="bi <?= $fournisseur ? 'bi-pencil' : 'bi-check-lg' ?> me-1"></i>
                        <?= $fournisseur ? 'Modifier le fournisseur' : 'Enregistrer le fournisseur' ?>
                    </button>
                </div>

            </form>

        </div>
    </div>

</main>

<!-- =========================================================
     VALIDATION BOOTSTRAP
========================================================= -->

<script>
(() => {
    'use strict';

    const forms = document.querySelectorAll('.needs-validation');

    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

})();
</script>

<!-- =========================================================
     MODAL SUCCÈS
========================================================= -->

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>

<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                    Opération réussie
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="bi bi-check-circle-fill text-success" style="font-size:3rem;"></i>
                <p class="mt-3 mb-0 fw-medium">
                    Le fournisseur a été enregistré avec succès.
                </p>
                <small class="text-muted">
                    Vous pouvez maintenant continuer vos opérations.
                </small>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success btn-sm px-4" data-bs-dismiss="modal">
                    <i class="bi bi-check me-1"></i>
                    OK, compris
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalElement = document.getElementById('successModal');
    if (modalElement) {
        const modal = new bootstrap.Modal(modalElement, {
            backdrop: 'static',
            keyboard: false
        });
        modal.show();
    }
});
</script>

<?php endif; ?>

<?php include '../../includes/footer.php'; ?>