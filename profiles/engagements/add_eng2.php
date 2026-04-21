<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}

include '../../includes/fonctions.php';

$numCompte = $_GET['numc'];
$idCompte = getIdCompteByNum($numCompte);
$data = getCompteByNum($numCompte);
$details = getDetailsCompte($numCompte);
$fourns = getAllFourniseurs();

$dotationInitiale = $details['dotationInitiale'] ?? 0;
$dotationRemaniee = $details['dotationRemaniee'] ?? 0;
$dotationTotale = $dotationInitiale + $dotationRemaniee;

$engagement = $details['totalEngagement'] ?? 0;
$mandat = $details['totalDepense'] ?? ($details['O.P'] ?? 0);

// Crédit disponible (avant paiement)
$credit = $dotationTotale - $engagement;

// Solde réel (après paiement)
$solde = $dotationTotale - $mandat;

// Couleurs dynamiques
$colorCredit = ($credit > 0) ? 'text-success' : (($credit < 0) ? 'text-danger' : 'text-dark');
$colorSolde = ($solde > 0) ? 'text-success' : (($solde < 0) ? 'text-danger' : 'text-dark');

$annee_connexion = $_SESSION['an'];
$min_date = $annee_connexion . "-01-01";
$max_date = date("Y-m-d");

include '../../includes/header.php';
?>

<style>
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

<main class="container py-4">

    <!-- HEADER COMPTE -->
    <div class="text-center mb-4">
        <h4 class="title-admin">
            Dossier du compte : <?= $data['numCompte']; ?> - <?= $data['libelle']; ?>
        </h4>
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
                            <?= number_format($dotationInitiale, 0, ',', ' '); ?> FCFA
                        </td>
                    </tr>

                    <tr>
                        <td>aprés remaniement(s) :</td>
                        <td class="text-end amount">
                            <?= number_format($dotationTotale, 0, ',', ' '); ?> FCFA
                        </td>
                    </tr>

                    <!-- ENGAGEMENT -->
                    <tr>
                        <td>Total engagements :</td>
                        <td class="text-end">
                            <a href="liste_engsByCompte.php?numc=<?= $numCompte ?>" class="text-decoration-underline">
                                <?= number_format($engagement, 0, ',', ' '); ?> FCFA
                            </a>
                        </td>
                    </tr>

                    <!-- CREDIT -->
                    <tr>
                        <td>Crédit disponible :</td>
                        <td class="text-end <?= $colorCredit ?>">
                            <?= number_format($credit, 0, ',', ' '); ?> FCFA
                        </td>
                    </tr>

                    <!-- MANDAT -->
                    <tr>
                        <td>Total mandat(s) :</td>
                        <td class="text-end text-warning">
                            <?= number_format($mandat, 0, ',', ' '); ?> FCFA
                        </td>
                    </tr>

                    <!-- SOLDE -->
                    <tr>
                        <td>Solde réel :</td>
                        <td class="text-end fw-bold <?= $colorSolde ?>">
                            <?= number_format($solde, 0, ',', ' '); ?> FCFA
                        </td>
                    </tr>

                </table>
            </div>

        </div>

        <!-- ================= RIGHT ================= -->
        <div class="col-md-7">

            <div class="card card-admin p-3">

                <div class="section-header">
                    <h5 class="mb-0">Enregistrement d’un nouvel engagement</h5>
                </div>

                <?php if (!empty($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <?= $_GET['error']; ?>
                </div>
                <?php endif; ?>

                <form action="traitement_eng.php" id="Form" method="POST">

                    <input type="hidden" name="idCompte" value="<?= htmlspecialchars($idCompte) ?>">

                    <!-- Ligne 1 -->
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label class="form-label"><strong>NUMERO DE COMPTE</strong></label>
                            <input style="border: 1px solid black;" name="numc" type="text" class="form-control"
                                value="<?= $numCompte ?>" readonly>
                            <input style="border: 1px solid black;" name="credit" type="text" class="form-control"
                                value="<?= $credit ?>" hidden>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"><strong>DATE</strong></label>
                            <input style="border: 1px solid black;" type="date" name="dateEng"
                                value="<?= date('Y-m-d') ?>" class="form-control" min="<?= $min_date ?>"
                                max="<?= $max_date ?>" required>
                        </div>
                    </div>

                    <!-- Ligne 2 -->
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label class="form-label"><strong>OBJET DE LA DEPENSE</strong></label>
                            <input type="text" style="border: 1px solid black;" name="objet" class="form-control"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Type</strong></label>
                            <select style="border: 1px solid black;" name="type_eng" class="form-select" required>
                                <option value="">Sélectionner le type</option>
                                <option value="Biens et services">Biens et services</option>
                                <option value="Personnel">Personnel</option>
                                <option value="Transfert">Transfert</option>
                            </select>
                        </div>
                    </div>

                    <!-- Ligne 3 -->
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label class="form-label"><strong>BENIFICIARE</strong></label>
                            <select style="border: 1px solid black;" name="idFourn" class="form-select" required>
                                <option value="">Sélectionner un fournisseur</option>
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
                        <button type="submit" class="btn btn-success">
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
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<?php include '../../includes/footer.php'; ?>
<script>
document.getElementById('Form').addEventListener('submit', function(e) {

    const montantInput = document.querySelector("[name='montant']");
    const montant = parseFloat(montantInput.value);

    const ecart = <?= (float)$credit ?>; // 

    if (isNaN(montant) || montant < 0) {
        alert("Le montant doit être supérieur à zéro.");
        e.preventDefault();
        return;
    }

    if (montant > ecart) {
        const confirmation = confirm(
            " Attention !\n\n" +
            "Le montant dépasse le crédit disponible.\n\n" +
            "Crédit disponible : " + ecart.toLocaleString() + " FCFA\n" +
            "Montant saisi : " + montant.toLocaleString() + " FCFA\n\n" +
            "Voulez-vous vraiment continuer ?"
        );

        if (!confirmation) {
            e.preventDefault();
        }
    }
});
</script>