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
        id,
        reference,
        annee,
        montant,
        type_marche,
        objet,
        date_creation
    FROM sigm_marches
    WHERE statut = 'valide'
    ORDER BY date_creation DESC
";

$resultMarches = mysqli_query($connexion, $sql);

if (!$resultMarches) {
    die("Erreur SQL : " . mysqli_error($connexion));
}


/*
|--------------------------------------------------------------------------
| Récupérer les comptes budgétaires
|--------------------------------------------------------------------------
|
| Adapter cette requête au nom réel de ta table des comptes.
|
*/

$sqlComptes = "SELECT * FROM bud_compte ORDER BY numCompte ASC";

$resultComptes = mysqli_query($connexion, $sqlComptes);

if (!$resultComptes) {
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
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, .06);
}

.section-title {
    font-size: 15px;
    font-weight: 600;
    color: #343a40;
}

#tableMarches {
    font-size: 13px;
}

#tableMarches th {
    font-size: 12px;
    white-space: nowrap;
}

#tableMarches td {
    vertical-align: middle;
}

.marche-selected {
    background-color: #f8f9fa !important;
}

#blocFormulaire {
    display: none;
}

.detail-label {
    display: block;
    color: #6c757d;
    font-size: 11px;
    margin-bottom: 2px;
}

.detail-value {
    font-size: 13px;
    font-weight: 600;
}
</style>

<main class="container-fluid mt-3">
    <!-- =====================================================
     TITRE
====================================================== -->

    <div class="card card-simple mb-3">

        <div class="card-body py-3">

            <h5 class="fw-bold mb-1">

                <i class="bi bi-file-earmark-text me-1"></i>

                Enregistrer un engagement

            </h5>

            <small class="text-muted">

                Sélectionnez un marché validé puis choisissez le compte budgétaire correspondant.

            </small>

        </div>

    </div>



    <!-- =====================================================
     LISTE DES MARCHÉS
====================================================== -->

    <div class="card card-simple mb-3">

        <div class="card-body">

            <div class="section-title mb-3">

                <i class="bi bi-folder-check me-1"></i>

                Marchés validés

            </div>


            <div class="table-responsive">

                <table id="tableMarches" class="table table-striped table-hover align-middle">

                    <thead>

                        <tr>

                            <th>Référence</th>

                            <th>Objet</th>

                            <th>Type</th>

                            <th>Montant</th>

                            <th>Date</th>

                            <th class="text-center">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php while ($marche = mysqli_fetch_assoc($resultMarches)): ?>

                        <tr id="marche-<?= $marche['id'] ?>">

                            <td>

                                <strong>
                                    <?= htmlspecialchars($marche['reference']) ?>
                                </strong>
                            </td>


                            <td>

                                <?= htmlspecialchars($marche['objet']) ?>

                            </td>


                            <td>

                                <?= htmlspecialchars($marche['type_marche']) ?>

                            </td>


                            <td class="text-end">

                                <strong>

                                    <?= number_format(
                                    $marche['montant'],
                                    0,
                                    ',',
                                    ' '
                                ) ?>

                                </strong>

                                <small class="text-muted">
                                    FCFA
                                </small>

                            </td>


                            <td>

                                <?= date(
                                'd/m/Y',
                                strtotime($marche['date_creation'])
                            ) ?>

                            </td>


                            <td class="text-center">

                                <button type="button" class="btn btn-outline-primary btn-sm btn-choisir"
                                    data-id="<?= $marche['id'] ?>" data-reference="<?= htmlspecialchars(
                                        $marche['reference'],
                                        ENT_QUOTES
                                    ) ?>" data-objet="<?= htmlspecialchars(
                                        $marche['objet'],
                                        ENT_QUOTES
                                    ) ?>" data-type="<?= htmlspecialchars(
                                        $marche['type_marche'],
                                        ENT_QUOTES
                                    ) ?>" data-montant="<?= $marche['montant'] ?>">

                                    <i class="bi bi-check2"></i>

                                    Choisir

                                </button>

                            </td>

                        </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>



    <!-- =====================================================
     FORMULAIRE APRÈS CHOIX DU MARCHÉ
====================================================== -->

    <div class="card card-simple mb-3" id="blocFormulaire">

        <div class="card-body">

            <div class="section-title mb-3">

                <i class="bi bi-pencil-square me-1"></i>

                Informations de l'engagement

            </div>


            <!-- INFORMATIONS DU MARCHÉ -->

            <div class="row g-3 mb-3">

                <div class="col-md-3">

                    <span class="detail-label">
                        Référence
                    </span>

                    <div class="detail-value" id="detailReference">
                    </div>

                </div>


                <div class="col-md-5">

                    <span class="detail-label">
                        Objet
                    </span>

                    <div class="detail-value" id="detailObjet">
                    </div>

                </div>


                <div class="col-md-2">

                    <span class="detail-label">
                        Type
                    </span>

                    <div class="detail-value" id="detailType">
                    </div>

                </div>


                <div class="col-md-2">

                    <span class="detail-label">
                        Montant
                    </span>

                    <div class="detail-value">

                        <span id="detailMontant"></span>

                        FCFA

                    </div>

                </div>

            </div>


            <hr>


            <!-- FORMULAIRE -->

            <form action="traitement_eng.php" method="POST" id="Form">


                <!-- ID DU MARCHÉ -->

                <input type="hidden" name="marche_id" id="marche_id" required>


                <div class="row g-3">


                    <!-- COMPTE -->

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Numéro de compte
                            <span class="text-danger">*</span>

                        </label>

                        <select name="idCompte" id="idCompte" class="form-select" required>

                            <option value="">
                                Sélectionner le compte
                            </option>

                            <?php while ($compte = mysqli_fetch_assoc($resultComptes)): ?>

                            <option value="<?= $compte['idCompte'] ?>"
                                data-numcompte="<?= htmlspecialchars($compte['numCompte']) ?>">

                                <?= htmlspecialchars($compte['numCompte']) ?>

                                <?php if (!empty($compte['libelle'])): ?>

                                - <?= htmlspecialchars($compte['libelle']) ?>

                                <?php endif; ?>

                            </option>

                            <?php endwhile; ?>

                        </select>

                    </div>


                    <!-- DATE -->

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">

                            Date

                            <span class="text-danger">*</span>

                        </label>

                        <input type="date" name="dateEng" class="form-control" value="<?= date('Y-m-d') ?>" required>

                    </div>


                    <!-- NUMERO COMPTE ENVOYÉ -->

                    <input type="hidden" name="numc" id="numc">


                </div>


                <div class="d-flex justify-content-between mt-4">

                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnAnnuler">

                        <i class="bi bi-x-lg me-1"></i>

                        Annuler

                    </button>


                    <button type="submit" class="btn btn-success btn-sm">

                        <i class="bi bi-check-lg me-1"></i>

                        Enregistrer l'engagement

                    </button>

                </div>


            </form>

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


    /*
    |--------------------------------------------------------------------------
    | DataTable
    |--------------------------------------------------------------------------
    */

    $('#tableMarches').DataTable({

        pageLength: 10,

        lengthMenu: [5, 10, 25, 50],

        language: {

            search: "Rechercher :",

            lengthMenu: "Afficher _MENU_ lignes",

            info: "Affichage de _START_ à _END_ sur _TOTAL_ lignes",

            zeroRecords: "Aucun marché trouvé",

            emptyTable: "Aucun marché validé",

            paginate: {

                previous: "Précédent",

                next: "Suivant"

            }

        }

    });



    /*
    |--------------------------------------------------------------------------
    | Choisir un marché
    |--------------------------------------------------------------------------
    */

    $('.btn-choisir').on('click', function() {

        const button = $(this);


        const id = button.data('id');

        const reference = button.data('reference');

        const objet = button.data('objet');

        const type = button.data('type');

        const montant = parseFloat(button.data('montant'));


        /*
        | Mettre en évidence la ligne
        */

        $('#tableMarches tbody tr')
            .removeClass('marche-selected');

        $('#marche-' + id)
            .addClass('marche-selected');


        /*
        | Remplir les informations
        */

        $('#marche_id').val(id);

        $('#detailReference').text(reference);

        $('#detailObjet').text(objet);

        $('#detailType').text(type);

        $('#detailMontant').text(
            montant.toLocaleString('fr-FR')
        );


        /*
        | Afficher le formulaire
        */

        $('#blocFormulaire').slideDown();


        /*
        | Aller vers le formulaire
        */

        $('html, body').animate({

            scrollTop: $('#blocFormulaire').offset().top - 20

        }, 400);

    });



    /*
    |--------------------------------------------------------------------------
    | Récupérer le numéro de compte
    |--------------------------------------------------------------------------
    */

    $('#idCompte').on('change', function() {

        const selected = $(this).find(':selected');

        const numCompte = selected.data('numcompte') || '';

        $('#numc').val(numCompte);

    });



    /*
    |--------------------------------------------------------------------------
    | Annuler la sélection
    |--------------------------------------------------------------------------
    */

    $('#btnAnnuler').on('click', function() {

        $('#Form')[0].reset();

        $('#marche_id').val('');

        $('#numc').val('');

        $('#blocFormulaire').slideUp();

        $('#tableMarches tbody tr')
            .removeClass('marche-selected');

    });


});
</script>