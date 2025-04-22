<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php"); // Redirige vers la page de connexion
    exit();
}
?>
<?php include '../../includes/fonctions.php';?>
<?php include '../../includes/header.php';?>
<main>
    <div class='container'>
        <?php include '../../shared/menu.php';?>
    </div>

    <!-- Barre de recherche -->
    <div class='text-center' style='margin-bottom:20px;color:#4655a4;'>
        <h3>REALISATIONS: 5,789,990/,890,000 soit 102,7%</h3>
    </div>

    <!-- Tableau -->
    <div class='container-fluid' style="margin-bottom: 20px;">
        <div
            style='width: 70%; margin: 0 auto; border-top: 4px solid #4655a4; border-bottom: 4px solid #4655a4; padding: 20px;'>
            <table class="table table-bordered text-center" style="width: 95%;margin: 0 auto;">
                <thead style="color: white !important;">
                    <tr>
                        <th style="background-color: #4655a4;">Compte_principal</th>
                        <th style="background-color: #4655a4;">Libelle</th>
                        <th style="background-color: #4655a4;">Dotations</th>
                        <th style="background-color: #4655a4;">Realisation</th>
                        <th style="background-color: #4655a4;">Taux_Realisation</th>
                        <th style="background-color: #4655a4;">Disponible</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php
                $dotations = getAllDotations();
                $n=1;
                if (!empty($dotations)) :
                    foreach ($dotations as $dotation) : ?>
                    <tr>
                        <td><?= $dotation['numCompte']; ?></td>
                        <td style='text-align: left;'><?= $dotation['date']; ?></td>
                        <td style='text-align: right;'><?= number_format($dotation['volume'], 0, ',', ','); ?> FCFA</td>
                        <td style='text-align: right;'>
                            <span class="badge <?= ($dotation['type'] == 'initiale') ? 'bg-info' : 'bg-warning'; ?>">
                                <?= ucfirst($dotation['type']); ?>
                            </span>
                        </td>
                        <td style='text-align: right;'><?= $dotation['log']; ?></td>
                        <td style='text-align: right;'>
                            <?php if (!isDotationUsed($dotation['idDot'])): ?>
                            <a href="supprimer_engagement.php?id=<?= $dotation['idDot'] ?>"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet Dotation ?')">Supprimer</a>
                            <?php else: ?>
                            <span style="color: grey; cursor: not-allowed;"
                                title="Dotation utilisé, suppression désactivée">Supprimer</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach;?>
                    <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-danger">Aucune recette trouvée</td>
                    </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
        <div style='width: 65%;' class="d-flex container justify-content-between align-items-center py-2 px-2"
            style="color:rgb(69, 47, 196); font-size: 18px; font-weight: 400;">
            <button type='submit' class='btn btn-success'><strong>Imprimer</strong></button>
            <a href='javascript:history.back()' class='btn btn-danger mb-0 text-right'><strong>Annuler</strong></a>
        </div>
    </div>



    <div class="container text-center" style="font-size: 15px; font-weight: 400;margin-bottom:20px;">
        <a href="javascript:history.back()" class="btn btn-info text-center"><strong>retour</strong></a>
    </div>
</main>
<?php include '../../includes/footer.php';?>