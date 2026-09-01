<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}

include '../../includes/fonctions.php';

global $connexion;

/*
|--------------------------------------------------------------------------
| Récupérer les marchés validés
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT 
        m.id,
        m.reference,
        m.annee,
        m.montant,
        m.type_marche,
        m.objet,
        m.date_creation,
        f.nom AS nom_fournisseur
    FROM sigm_marches m
    LEFT JOIN bud_fournisseur f ON f.idFourn = m.id_fournisseur
    WHERE m.statut = 'valide'
    ORDER BY m.date_creation DESC
";

$resultMarches = mysqli_query($connexion, $sql);

if (!$resultMarches) {
    die("Erreur SQL : " . mysqli_error($connexion));
}

/*
|--------------------------------------------------------------------------
| Récupérer les comptes budgétaires
|--------------------------------------------------------------------------
*/

//$sqlComptes = "SELECT * FROM bud_compte ORDER BY numCompte ASC";
//$resultComptes = mysqli_query($connexion, $sqlComptes);
$comptes =  getComptesDotationsByEng();

if (!$comptes) {
    die("Erreur récupération comptes : " . mysqli_error($connexion));
}

include '../../includes/header.php';
?>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
.card-simple {
    border: 0;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
    height: 100%;
}

.card-simple .card-body {
    padding: 1.2rem 1.5rem;
}

.section-title {
    font-size: 14px;
    font-weight: 600;
    color: #2c3e50;
    border-bottom: 2px solid #4655a4;
    padding-bottom: 8px;
    margin-bottom: 15px;
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
    background-color: #fafbfc;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-control:focus,
.form-select:focus {
    border-color: #4655a4;
    box-shadow: 0 0 0 3px rgba(70, 85, 164, 0.15);
    background-color: #fff;
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
    color: #fff;
    transform: translateY(-1px);
}

.btn-primary {
    background-color: #4655a4;
    border-color: #4655a4;
}

.btn-primary:hover {
    background-color: #35438a;
    border-color: #35438a;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(70, 85, 164, 0.3);
}

.btn-outline-primary:hover {
    background-color: #4655a4;
    color: #fff;
    transform: translateY(-1px);
}

.btn-view {
    background: transparent;
    border: none;
    color: #17a2b8;
    font-size: 18px;
    padding: 2px 6px;
    transition: all 0.2s ease;
}

.btn-view:hover {
    color: #138496;
    transform: scale(1.1);
}

#tableMarches {
    font-size: 12px;
}

#tableMarches th {
    background-color: #4655a4;
    color: #fff;
    font-weight: 600;
    font-size: 11px !important;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    white-space: nowrap;
    padding: 8px 6px !important;
}

#tableMarches th:first-child {
    border-radius: 8px 0 0 0;
}

#tableMarches th:last-child {
    border-radius: 0 8px 0 0;
}

#tableMarches td {
    vertical-align: middle;
    padding: 6px 6px !important;
    font-size: 12px;
}

#tableMarches tbody tr:hover {
    background-color: #f8f9ff !important;
    cursor: pointer;
}

#tableMarches tbody tr.selected {
    background-color: #e8edff !important;
    border-left: 3px solid #4655a4;
}

.text-ellipsis {
    max-width: 180px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: inline-block;
}

.detail-label {
    display: block;
    color: #6c757d;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    font-weight: 500;
    margin-bottom: 2px;
}

.detail-value {
    font-size: 13px;
    font-weight: 600;
    color: #2c3e50;
}

.detail-value .montant {
    color: #4655a4;
}

.detail-value .fournisseur {
    font-weight: 500;
    font-size: 12px;
}

.radio-custom {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #4655a4;
}

.info-marche {
    background: #f8f9ff;
    border-radius: 8px;
    padding: 12px 15px;
    border: 1px solid #e8ebf5;
}

.info-marche .row {
    margin: 0;
}

.info-marche .col-6 {
    padding: 2px 8px;
}

/* Nouveau style pour les infos du compte */
.info-compte {
    background: #f0f4ff;
    border-radius: 8px;
    padding: 10px 15px;
    border: 1px solid #d6def5;
    margin-top: 8px;
    display: none;
}

.info-compte .row {
    margin: 0;
}

.info-compte .col-6 {
    padding: 2px 8px;
}

.info-compte .detail-label {
    color: #4a5b9e;
}

.info-compte .detail-value {
    font-size: 12px;
}

.info-compte .detail-value .montant-compte {
    color: #1f3a93;
    font-weight: 700;
}

.info-compte .detail-value .credit-dispo {
    color: #1e8449;
    font-weight: 700;
}

.info-compte .detail-value .credit-epuise {
    color: #c0392b;
    font-weight: 700;
}

.dataTables_wrapper .dataTables_filter input {
    border: 1.5px solid #ced4da;
    border-radius: 6px;
    padding: 4px 10px;
    font-size: 12px;
    background: #fafbfc;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #4655a4;
    outline: none;
}

.dataTables_wrapper .dataTables_length select {
    border: 1.5px solid #ced4da;
    border-radius: 6px;
    padding: 3px 6px;
    font-size: 12px;
    background: #fafbfc;
}

.dataTables_wrapper .dataTables_info {
    font-size: 12px;
    color: #6c757d;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 3px 10px;
    border-radius: 4px;
    border: 1px solid #e0e5ec;
    margin: 0 2px;
    font-size: 12px;
    background: #fff;
    color: #495057 !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #4655a4 !important;
    color: #fff !important;
    border-color: #4655a4;
}

@media (max-width: 992px) {

    .col-lg-7,
    .col-lg-5 {
        width: 100%;
        max-width: 100%;
    }

    #tableMarches {
        font-size: 11px;
    }

    .text-ellipsis {
        max-width: 100px;
    }
}

@media (max-width: 768px) {
    .card-simple .card-body {
        padding: 10px;
    }

    #tableMarches th,
    #tableMarches td {
        font-size: 10px !important;
        padding: 4px 3px !important;
    }

    .text-ellipsis {
        max-width: 60px;
    }

    .detail-value {
        font-size: 12px;
    }

    .info-marche {
        padding: 8px 10px;
    }

    .info-compte {
        padding: 8px 10px;
    }
}

/* Spinner de chargement */
.spinner-border-sm {
    width: 14px;
    height: 14px;
}
</style>

<main class="container-fluid mt-3">

    <?php if (!empty($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show py-2 mb-3" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <?= htmlspecialchars($_GET['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- =====================================================
     TITRE
    ====================================================== -->

    <div class="card card-simple mb-3">
        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        Enregistrer un engagement
                    </h5>
                    <small class="text-muted">
                        Sélectionnez un marché validé, puis choisissez le compte budgétaire
                    </small>
                </div>
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                    <i class="bi bi-check-circle me-1"></i>
                    Marchés validés
                </span>
            </div>
        </div>
    </div>

    <!-- =====================================================
     DEUX COLONNES
    ====================================================== -->

    <div class="row g-3">

        <!-- =================================================
         COLONNE GAUCHE - TABLEAU DES MARCHÉS
        ================================================== -->

        <div class="col-lg-7">
            <div class="card card-simple">
                <div class="card-body">

                    <div class="section-title">
                        <i class="bi bi-folder-check"></i>
                        Marchés validés
                        <span class="badge bg-secondary ms-2">
                            <?= mysqli_num_rows($resultMarches) ?>
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table id="tableMarches" class="table table-striped table-hover align-middle">

                            <thead>
                                <tr>
                                    <th style="width:40px;">Sél.</th>
                                    <th>Réf.</th>
                                    <th>Date</th>
                                    <th>Fournisseur</th>
                                    <th>Objet</th>
                                    <th>Montant</th>
                                    <th style="width:40px;">Voir</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php 
                                $hasMarches = false;
                                while ($marche = mysqli_fetch_assoc($resultMarches)): 
                                    $hasMarches = true;
                                ?>
                                <tr id="marche-<?= $marche['id'] ?>" data-id="<?= $marche['id'] ?>">
                                    <td>
                                        <input type="radio" name="marche_select" class="radio-custom radio-marche"
                                            value="<?= $marche['id'] ?>"
                                            data-reference="<?= htmlspecialchars($marche['reference'], ENT_QUOTES) ?>"
                                            data-objet="<?= htmlspecialchars($marche['objet'], ENT_QUOTES) ?>"
                                            data-type="<?= htmlspecialchars($marche['type_marche'], ENT_QUOTES) ?>"
                                            data-montant="<?= $marche['montant'] ?>"
                                            data-fournisseur="<?= htmlspecialchars($marche['nom_fournisseur'] ?? 'Non défini', ENT_QUOTES) ?>">
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($marche['reference']) ?></strong>
                                    </td>
                                    <td>
                                        <?= date('d/m/Y', strtotime($marche['date_creation'])) ?>
                                    </td>
                                    <td>
                                        <span class="text-truncate d-block" style="max-width:120px;"
                                            title="<?= htmlspecialchars($marche['nom_fournisseur'] ?? 'Non défini') ?>">
                                            <?= htmlspecialchars($marche['nom_fournisseur'] ?? 'Non défini') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-ellipsis" title="<?= htmlspecialchars($marche['objet']) ?>">
                                            <?= htmlspecialchars($marche['objet']) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <strong><?= number_format($marche['montant'], 0, ',', ' ') ?></strong>
                                    </td>
                                    <td>
                                        <a href="../../payement/traitement_marche.php?id=<?= $marche['id'] ?>" class="btn-view"
                                            title="Voir le marché" target="_blank">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>

                                <?php if (!$hasMarches): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-folder-x fs-3 d-block mb-2"></i>
                                        Aucun marché validé disponible
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
        </div>

        <!-- =================================================
         COLONNE DROITE - FORMULAIRE
        ================================================== -->

        <div class="col-lg-5">
            <div class="card card-simple" id="blocFormulaire">
                <div class="card-body">

                    <div class="section-title">
                        <i class="bi bi-pencil-square"></i>
                        Nouvel engagement
                    </div>

                    <form action="traitement_eng" method="POST" id="formEngagement" class="needs-validation" novalidate>

                        <input type="hidden" name="marche_id" id="marche_id">

                        <!-- ===== INFOS MARCHÉ ===== -->
                        <div class="info-marche mb-2" id="infoMarche">
                            <div class="row">
                                <div class="col-6">
                                    <span class="detail-label">Référence</span>
                                    <div class="detail-value" id="detailReference">—</div>
                                </div>
                                <div class="col-6">
                                    <span class="detail-label">Montant</span>
                                    <div class="detail-value">
                                        <span class="montant" id="detailMontant">—</span> FCFA
                                    </div>
                                </div>
                                <div class="col-12 mt-1">
                                    <span class="detail-label">Fournisseur</span>
                                    <div class="detail-value fournisseur" id="detailFournisseur">—</div>
                                </div>
                                <div class="col-12">
                                    <span class="detail-label">Objet</span>
                                    <div class="detail-value" id="detailObjet" style="font-size:12px;font-weight:400;">—
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ===== COMPTE ===== -->
                        <div class="mb-2">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-book me-1"></i>
                                Compte budgétaire
                                <span class="text-danger">*</span>
                            </label>
                            <select name="idCompte" id="idCompte" class="form-select" required>
                                <option value="">Sélectionner un compte</option>
                                <?php 
                                //mysqli_data_seek($resultComptes, 0);
                                 foreach ($comptes as $compte): ?> 
                                <option value="<?= $compte['idCompte'] ?>"
                                    data-numcompte="<?= htmlspecialchars($compte['numCompte']) ?>"
                                    data-idcompte="<?= $compte['idCompte'] ?>">
                                    <?= htmlspecialchars($compte['numCompte']) ?>
                                    <?php if (!empty($compte['libelle'])): ?>
                                    - <?= htmlspecialchars($compte['libelle']) ?>
                                    <?php endif; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Veuillez sélectionner un compte.</div>
                        </div>

                        <input type="hidden" name="numc" id="numc">

                        <!-- ===== INFOS COMPTE (AJOUTÉ) ===== -->
                        <div class="info-compte mb-2" id="infoCompte">
                            <div class="row">
                                <div class="col-6">
                                    <span class="detail-label">Dotation totale</span>
                                    <div class="detail-value">
                                        <span class="montant-compte" id="detailDotation">—</span> FCFA
                                    </div>
                                </div>
                                <div class="col-6">
                                    <span class="detail-label">Engagements</span>
                                    <div class="detail-value">
                                        <span class="montant-compte" id="detailEngagements">—</span> FCFA
                                    </div>
                                </div>
                                <div class="col-6 mt-1">
                                    <span class="detail-label">Crédit disponible</span>
                                    <div class="detail-value">
                                        <span class="credit-dispo" id="detailCredit">—</span> FCFA
                                    </div>
                                </div>
                                <div class="col-6 mt-1">
                                    <span class="detail-label">Mandats</span>
                                    <div class="detail-value">
                                        <span class="montant-compte" id="detailMandats">—</span> FCFA
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ===== DATE ===== -->
                        <div class="mb-2">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar me-1"></i>
                                Date d'engagement
                                <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="dateEng" class="form-control" value="<?= date('Y-m-d') ?>"
                                required>
                            <div class="invalid-feedback">Veuillez sélectionner une date.</div>
                        </div>

                        <!-- ===== BOUTONS ===== -->
                        <div class="d-flex justify-content-between mt-3 pt-2 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnAnnuler">
                                <i class="bi bi-x-lg me-1"></i> Annuler
                            </button>
                            <button type="submit" class="btn btn-success btn-sm" id="btnSubmit" disabled>
                                <i class="bi bi-check-lg me-1"></i> Enregistrer
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>

</main>

<?php include '../../includes/footer.php'; ?>

<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {

    // =========================================================
    // DATATABLE
    // =========================================================

    var table = $('#tableMarches').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        order: [],
        language: {
            search: "Rechercher :",
            lengthMenu: "Afficher _MENU_ lignes",
            info: "Affichage de _START_ à _END_ sur _TOTAL_ marchés",
            zeroRecords: "Aucun marché trouvé",
            emptyTable: "Aucun marché validé",
            paginate: {
                previous: "Précédent",
                next: "Suivant"
            }
        }
    });

    // =========================================================
    // SÉLECTION PAR BOUTON RADIO
    // =========================================================

    $('.radio-marche').on('change', function() {
        if ($(this).is(':checked')) {
            const id = $(this).val();
            const data = $(this).data();

            // Mettre en évidence la ligne
            $('.selected').removeClass('selected');
            $('#marche-' + id).addClass('selected');

            // Remplir les infos
            $('#marche_id').val(id);
            $('#detailReference').text(data.reference || '—');
            $('#detailMontant').text(data.montant ? data.montant.toLocaleString('fr-FR') : '—');
            $('#detailFournisseur').text(data.fournisseur || '—');
            $('#detailObjet').text(data.objet || '—');

            // Activer le bouton si un compte est sélectionné
            if ($('#idCompte').val() !== '') {
                $('#btnSubmit').prop('disabled', false);
            }

            // Scroll vers le formulaire sur mobile
            if ($(window).width() < 992) {
                $('html, body').animate({
                    scrollTop: $('#blocFormulaire').offset().top - 20
                }, 300);
            }
        }
    });

    // =========================================================
    // CLIC SUR LA LIGNE DU TABLEAU
    // =========================================================

    $('#tableMarches tbody tr').on('click', function(e) {
        // Ignorer si on clique sur le lien "Voir"
        if ($(e.target).closest('.btn-view').length) {
            return;
        }

        const radio = $(this).find('.radio-marche');
        if (radio.length) {
            radio.prop('checked', true).trigger('change');
        }
    });

    // =========================================================
    // CHARGEMENT DES INFOS DU COMPTE
    // =========================================================

    $('#idCompte').on('change', function() {
        const selected = $(this).find(':selected');
        const numCompte = selected.data('numcompte') || '';
        const idCompte = selected.data('idcompte') || '';
        
        $('#numc').val(numCompte);

        // Vérifier si un compte est sélectionné
        if (idCompte !== '') {
            // Afficher le bloc de chargement
            $('#infoCompte').show();
            $('#detailDotation').text('Chargement...');
            $('#detailEngagements').text('Chargement...');
            $('#detailCredit').text('Chargement...');
            $('#detailMandats').text('Chargement...');
            
            // Charger les informations du compte via AJAX
            $.ajax({
                url: 'get_compte_details.php',
                method: 'GET',
                data: { idCompte: idCompte },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        
                        // Afficher les infos du compte
                        $('#detailDotation').text(data.dotationTotale ? data.dotationTotale.toLocaleString('fr-FR') : '0');
                        $('#detailEngagements').text(data.engagement ? data.engagement.toLocaleString('fr-FR') : '0');
                        $('#detailMandats').text(data.mandat ? data.mandat.toLocaleString('fr-FR') : '0');
                        
                        // Crédit disponible avec couleur
                        const credit = data.credit || 0;
                        const creditSpan = $('#detailCredit');
                        creditSpan.text(credit.toLocaleString('fr-FR'));
                        
                        // Changer la couleur du crédit
                        creditSpan.removeClass('credit-dispo credit-epuise');
                        if (credit > 0) {
                            creditSpan.addClass('credit-dispo');
                        } else if (credit < 0) {
                            creditSpan.addClass('credit-epuise');
                        }
                        
                        // Afficher le bloc des infos du compte
                        $('#infoCompte').show();
                    } else {
                        $('#infoCompte').hide();
                    }
                },
                error: function() {
                    $('#detailDotation').text('Erreur');
                    $('#detailEngagements').text('Erreur');
                    $('#detailCredit').text('Erreur');
                    $('#detailMandats').text('Erreur');
                }
            });

            // Activer le bouton si un marché est sélectionné
            if ($('.radio-marche:checked').length > 0) {
                $('#btnSubmit').prop('disabled', false);
            }
        } else {
            $('#infoCompte').hide();
            $('#numc').val('');
            // Désactiver le bouton si pas de compte
            $('#btnSubmit').prop('disabled', true);
        }
    });

    // =========================================================
    // ANNULER
    // =========================================================

    $('#btnAnnuler').on('click', function() {
        $('.radio-marche').prop('checked', false);
        $('#formEngagement')[0].reset();
        $('#idCompte').val('');
        $('#numc').val('');
        $('#marche_id').val('');
        $('#detailReference').text('—');
        $('#detailMontant').text('—');
        $('#detailFournisseur').text('—');
        $('#detailObjet').text('—');
        $('#detailDotation').text('—');
        $('#detailEngagements').text('—');
        $('#detailCredit').text('—');
        $('#detailMandats').text('—');
        $('#infoCompte').hide();
        $('#btnSubmit').prop('disabled', true);
        $('.selected').removeClass('selected');
    });

    // =========================================================
    // VALIDATION BOOTSTRAP
    // =========================================================

    (function() {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();

});
</script>