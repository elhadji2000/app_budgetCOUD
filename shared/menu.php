<?php
if (!isset($_SESSION['user'])) {
    header("Location: /BUDGET/index.php"); // Redirige vers la page de connexion
    exit();
}

$anneeSession = $_SESSION['an'] ?? date("Y"); // Valeur par défaut si la session n'a pas 'annee'
$anneeActuelle = date("Y");
?>

<table class="table-bordered" border="1" width="100%"
    style="margin-bottom: 40px; text-align: center; border-collapse: collapse;">
    <tr style="background-color: #4655a4; color: white;font-size: 13px; font-weight: 400;">

        <!-- LIEN CONSULTER LEXECUTION -->
        <?php if ($_SESSION['priv'] == 'sag' || $_SESSION['priv'] == 'admin') : ?>
        <td>
            <div class="dropdown">
                <a style="color: white; text-decoration: none;font-size: 13px;" class="dropdown-toggle text-white"
                    href="#" role="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <strong>Consulter l'exécution</strong>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li>
                        <a class="dropdown-item" href="http://localhost/BUDGET/profiles/dg/actuel_1.php">
                            <strong>Globale</strong>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="http://localhost/BUDGET/profiles/dg/global_1.php">
                            <strong>Globale O.P</strong>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="http://localhost/BUDGET/profiles/dg/borner_1.php">
                            <strong>Borner</strong>
                        </a>
                    </li>

                    <li><a class="dropdown-item" href="http://localhost/BUDGET/profiles/dg/journ_1.php">
                            <strong>Journaliere</strong>
                        </a>
                    </li>
                </ul>
            </div>
        </td>
        <?php endif; ?>

        <!-- LIEN DE LA DOATIONS -->
        <?php if ($_SESSION['priv'] == 'sag' || $_SESSION['priv'] == 'admin') : ?>
        <td>
            <div class="dropdown">
                <a style="color: white; text-decoration: none;font-size: 13px;" class="dropdown-toggle text-white"
                    href="#" role="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <strong>Dotations</strong>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php if ($anneeSession == $anneeActuelle): ?>
                    <li>
                        <a class="dropdown-item" href="http://localhost/BUDGET/profiles/dotations/add_ini_dot.php">
                            <strong>Initiale</strong>
                        </a>
                    </li>
                    <?php else: ?>
                    <li>
                        <span class="dropdown-item text-muted" style="cursor: not-allowed; opacity: 0.5;"
                            title="Lien désactivé car l'année de session est différente">
                            <strong>Initiale</strong>
                        </span>
                    </li>
                    <?php endif; ?>

                    <?php if ($anneeSession == $anneeActuelle): ?>
                    <li>
                        <a class="dropdown-item" href="http://localhost/BUDGET/profiles/dotations/add_rem_dot.php">
                            <strong>Remaniement</strong>
                        </a>
                    </li>
                    <?php else: ?>
                    <li>
                        <span class="dropdown-item text-muted" style="cursor: not-allowed; opacity: 0.5;"
                            title="Lien désactivé car l'année de session est différente">
                            <strong>Remaniement</strong>
                        </span>
                    </li>
                    <?php endif; ?>

                    <li><a class="dropdown-item"
                            href="http://localhost/BUDGET/profiles/dotations/liste_dotations.php"><strong>Consulter</strong></a>
                    </li>
                </ul>
            </div>
        </td>
        <?php endif; ?>

        <?php if ($_SESSION['priv'] == 'sag' || $_SESSION['priv'] == 'admin') : ?>
        <td>
            <a href="http://localhost/BUDGET/profiles/fournisseurs/liste_fournisseurs.php" class="text-white"
                style="color: white; text-decoration: none;"><strong>Fournisseurs</strong></a>
        </td>
        <?php endif; ?>

        <?php if ($_SESSION['priv'] == 'sag' || $_SESSION['priv'] == 'admin') : ?>
        <td>
            <div class="dropdown">
                <a style="color: white; text-decoration: none;font-size: 13px;" class="dropdown-toggle text-white"
                    href="#" role="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <strong>Engagements</strong>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php

if ($anneeSession == $anneeActuelle): ?>
                    <li>
                        <a class="dropdown-item" href="http://localhost/BUDGET/profiles/engagements/add_eng1.php">
                            <strong>Nouveau</strong>
                        </a>
                    </li>
                    <?php else: ?>
                    <li>
                        <span class="dropdown-item text-muted" style="cursor: not-allowed; opacity: 0.5;"
                            title="Lien désactivé car l'année de session est différente">
                            <strong>Nouveau</strong>
                        </span>
                    </li>
                    <?php endif; ?>

                    <li><a class="dropdown-item"
                            href="http://localhost/BUDGET/profiles/engagements/liste_engs.php"><strong>Consulter</strong></a>
                    </li>
                </ul>
            </div>
        </td>
        <?php endif; ?>

        <?php if ($_SESSION['priv'] == 'sag' || $_SESSION['priv'] == 'admin') : ?>
        <td>
            <div class="dropdown">
                <a style="color: white; text-decoration: none;font-size: 13px;" class="dropdown-toggle text-white"
                    href="#" role="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <strong>Ordre de Paiment(O.P)</strong>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php if ($anneeSession == $anneeActuelle): ?>
                    <li>
                        <a class="dropdown-item" href="http://localhost/BUDGET/profiles/paiement/add_paie1.php">
                            <strong>Nouveau</strong>
                        </a>
                    </li>
                    <?php else: ?>
                    <li>
                        <span class="dropdown-item text-muted" style="cursor: not-allowed; opacity: 0.5;"
                            title="Lien désactivé car l'année de session est différente">
                            <strong>Nouveau</strong>
                        </span>
                    </li>
                    <?php endif; ?>
                    <li><a class="dropdown-item"
                            href="http://localhost/BUDGET/profiles/paiement/liste_op.php"><strong>Consulter</strong></a>
                    </li>
                </ul>
            </div>
        </td>
        <?php endif; ?>

        <?php if ($_SESSION['priv'] == 'sag' || $_SESSION['priv'] == 'admin') : ?>
        <td>
            <div class="dropdown">
                <a style="color: white; text-decoration: none;font-size: 13px;" class="dropdown-toggle text-white"
                    href="#" role="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <strong>Ordre de Recette(O.R)</strong>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php if ($anneeSession == $anneeActuelle): ?>
                    <li>
                        <a class="dropdown-item" href="http://localhost/BUDGET/profiles/recettes/add_rec1.php">
                            <strong>Nouveau</strong>
                        </a>
                    </li>
                    <?php else: ?>
                    <li>
                        <span class="dropdown-item text-muted" style="cursor: not-allowed; opacity: 0.5;"
                            title="Lien désactivé car l'année de session est différente">
                            <strong>Nouveau</strong>
                        </span>
                    </li>
                    <?php endif; ?>

                    <li><a class="dropdown-item"
                            href="http://localhost/BUDGET/profiles/recettes/liste_rec.php"><strong>Consulter</strong></a>
                    </li>
                </ul>
            </div>
        </td>
        <?php endif; ?>

        <?php if ($_SESSION['priv'] == 'sag' || $_SESSION['priv'] == 'admin') : ?>
        <td>
            <a href="http://localhost/BUDGET/profiles/dba/liste_compte.php" class="text-white"
                style="color: white; text-decoration: none;"><strong>Compte</strong></a>
        </td>
        <?php endif; ?>

        <?php if ($_SESSION['priv'] == 'sag' || $_SESSION['priv'] == 'admin') : ?>
        <td>
            <a href="http://localhost/BUDGET/profiles/dba/liste_users.php" class="text-white"
                style="color: white; text-decoration: none;"><strong>Utilisateurs</strong></a>
        </td>
        <?php endif; ?>

        <td><a href="http://localhost/BUDGET/shared/updated_mdp.php" class="text-white"
                style="color: white; text-decoration: none;"><strong>Mot de
                    passe</strong></a></td>
    </tr>
</table>