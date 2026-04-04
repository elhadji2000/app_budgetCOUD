<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    // Redirige vers la page de connexion
    exit();
}
?>
<?php include '../../includes/fonctions.php'; ?>
<?php include '../../includes/header.php'; ?>
<?php
$numCompte = $_GET['numc'];
$compte = getCompteByNum($numCompte);

$idCompte = getIdCompteByNum($numCompte);
$data = getCompteByNum($numCompte);
$details = getDetailsCompte($numCompte);
$fourns = getAllFourniseurs();

// Sécurité
$type = $details['type'] ?? '';
$recettes = $details['totalRecette'] ?? 0;
$dotation = $details['dotationTotale'] ?? 0;
$depenses = $details['totalDepense'] ?? 0;

// Variation (gain ou perte)
$variation = $recettes - $dotation;

// Format signe +
$variationAffiche = ($variation > 0 ? '+' : '') . number_format($variation, 0, ',', ' ') . ' FCFA';

// Couleur dynamique
$colorVariation = ($variation > 0) ? 'text-success' : (($variation < 0) ? 'text-danger' : 'text-dark');
?>
<?php
$annee_connexion = $_SESSION['an'];  // ex: 2023
$min_date = $annee_connexion . '-01-01';
$max_date = date('Y-m-d');  // aujourd'hui
?>
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
body {
    background-color: #f4f6f9;
}

.pdf-card {
    background: #fff;
    border-radius: 6px;
    box-shadow: 0 1px 8px rgba(0, 0, 0, 0.08);
    padding: 20px;
    margin-bottom: 20px;
}

.title {
    color: #4655a4;
    font-weight: 600;
}

.small-text {
    font-size: 13px;
    color: #6c757d;
}

.table-primary-custom thead {
    background-color: #4655a4;
    color: white;
}

.select-eng-btn:hover {
    transform: scale(1.05);
    transition: 0.2s;
}

.table-active-custom {
    background-color: #e9edff !important;
}

.form-check-input {
    transform: scale(1.2);
    cursor: pointer;
}

.table-active-custom {
    background-color: #e9edff !important;
}

.card-admin {
    border: 0;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}

.title-admin {
    font-size: 18px;
    font-weight: 700;
    color: #2c3e50;
    text-transform: uppercase;
    letter-spacing: .5px;
}

.table-finance td {
    padding: 10px 8px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
}

.table-finance td:first-child {
    font-weight: 600;
    color: #34495e;
}

.amount {
    font-weight: 700;
    color: #1f3a93;
}

.amount.green {
    color: #1e8449;
}

.amount.red {
    color: #c0392b;
}

.section-header {
    border-left: 5px solid #4655a4;
    padding-left: 10px;
    margin-bottom: 15px;
}
</style>

<main class="container-fluid">
    <!-- HEADER -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">

            <div>
                <h5 class="fw-bold text-primary mb-1">
                    <i class="bi bi-graph-up-arrow"></i> ORDRE DE RECETTE
                </h5>
                <small class="text-muted"><strong><?= htmlspecialchars($compte['numCompte'] ?? '') ?> -
                        <?= htmlspecialchars($compte['libelle'] ?? '') ?></small>
            </div>

            <div class="text-end">
                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>

        </div>
    </div>

    <div class="row g-4">

        <!-- ================= LEFT ================= -->
        <div class="col-md-5">

            <div class="card card-admin p-3">
                <div class="section-header">
                    <h5 class="mb-0">Situation budgétaire du compte</h5>
                </div>

                <table class="w-100 fw-bold text-uppercase table-finance">

                    <!-- DOTATION -->
                    <tr>
                        <td>Dotation initiale :</td>
                        <td class="text-end">
                            <?= number_format($details['dotationInitiale'] ?? 0, 0, ',', ' '); ?> FCFA
                        </td>
                    </tr>

                    <tr>
                        <td>aprés remaniement(s) :</td>
                        <td class="text-end amount">
                            <?= number_format($dotation, 0, ',', ' '); ?> FCFA
                        </td>
                    </tr>

                    <!-- RECETTES -->
                    <tr>
                        <td>Total recettes :</td>
                        <td class="text-end text-primary">
                            <a href="#?numc=<?= $numCompte ?>" class="text-decoration-underline">
                                <?= number_format($recettes, 0, ',', ' '); ?> FCFA
                            </a>
                        </td>
                    </tr>

                    <!-- VARIATION -->
                    <tr>
                        <td>Variation (gain/perte) :</td>
                        <td class="text-end <?= $colorVariation ?>">
                            <?= $variationAffiche; ?>
                        </td>
                    </tr>

                    <!-- SOLDE -->
                    <tr>
                        <td>Solde final :</td>
                        <td class="text-end fw-bold <?= $colorVariation ?>">
                            <?= number_format($recettes, 0, ',', ' '); ?> FCFA
                        </td>
                    </tr>

                </table>
            </div>

        </div>

        <!-- ================= RIGHT ================= -->
        <div class="col-md-7 mb-3">

            <div class="card card-admin p-3">

                <div class="section-header">
                    <h5 class="mb-0">Enregistrement d’une nouvelle Recette</h5>
                </div>

                <?php if (!empty($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <?= $_GET['error']; ?>
                </div>
                <?php endif; ?>

                <form action="traitement_recette.php" method="POST">

                    <input type="hidden" name="idCompte" value="<?= htmlspecialchars($idCompte) ?>">

                    <!-- Ligne 1 -->
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label class="form-label"><strong>NUMERO DE COMPTE</strong></label>
                            <input style="border: 1px solid black;" type="text" class="form-control"
                                value="<?= $numCompte ?>" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"><strong>DATE</strong></label>
                            <input style="border: 1px solid black;" value="<?= date('Y-m-d') ?>" type="date"
                                name="dateOr" class="form-control" min="<?= $min_date ?>" max="<?= $max_date ?>"
                                required>
                        </div>
                    </div>

                    <!-- Ligne 2 -->
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label class="form-label"><strong>OBJET DE LA RECETTE</strong></label>
                            <input type="text" style="border: 1px solid black;" name="objet_recette"
                                class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"><strong>PIECES_ANNEXÈES</strong></label>
                            <input type="text" style="border: 1px solid black;" name="pieces_annexees"
                                class="form-control" required>
                        </div>
                    </div>

                    <!-- Ligne 3 -->
                    <div class="row mb-2">

                        <div class="col-md-12">
                            <label class="form-label"><strong>DÈBITEUR(S)</strong></label>
                            <select style="border: 1px solid black;" name="idFourn" class="form-select" required>
                                <option value="">Sélectionner un debiteur</option>
                                <?php foreach ($fourns as $f): ?>
                                <option value="<?= $f['idFourn'] ?>">
                                    <?= $f['numFourn'] ?> : <?= $f['nom'] ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>MONTANT</strong></label>
                        <input style="border: 1px solid black;" type="number" name="montant" class="form-control"
                            required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button class="btn btn-success">
                            <strong>Enregistrer</strong>
                        </button>
                        <a href="javascript:history.back()" class="btn btn-danger">
                            <strong>Annuler</strong>
                        </a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</main>

<script>
document.querySelector("form").addEventListener("submit", function(e) {
    const montant = parseFloat(document.querySelector("[name='montant']").value);
    const ecart = <?= (float) $details['ecart']; ?>;

    if (montant < 0) {
        alert("Le montant doit être supérieur ou égal à zéro.");
        e.preventDefault();
        return;
    }

    if (montant > ecart) {
        alert("Montant supérieur au crédit disponible (" + ecart.toLocaleString() + " FCFA)");
        e.preventDefault();
    }
});
</script>

<?php include '../../includes/footer.php'; ?>