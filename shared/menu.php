<?php
if (!isset($_SESSION['user'])) {
    header("Location: /BUDGET/index.php"); // Redirige vers la page de connexion
    exit();
}
?>

<table class="table-bordered" border="1" width="100%"
    style="margin-bottom: 40px; text-align: center; border-collapse: collapse;">
    <tr style="background-color: #4655a4; color: white;font-size: 13px; font-weight: 400;">
        <td><a href="dashboard.php" class="text-white" style="color: white; text-decoration: none;"><strong>Consulter
                    l'exécution</strong></a></td>
        <td>
            <div class="dropdown">
                <a style="color: white; text-decoration: none;font-size: 13px;" class="dropdown-toggle text-white"
                    href="#" role="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <strong>Dotations</strong>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item"
                            href="http://localhost/BUDGET/profiles/dotations/add_ini_dot.php"><strong>Initiale</strong></a>
                    </li>
                    <li><a class="dropdown-item"
                            href="http://localhost/BUDGET/profiles/dotations/add_rem_dot.php"><strong>Remaniement</strong></a>
                    </li>
                    <li><a class="dropdown-item"
                            href="http://localhost/BUDGET/profiles/dotations/liste_dotations.php"><strong>Consulter</strong></a>
                    </li>
                </ul>
            </div>
        </td>

        <td><a href="http://localhost/BUDGET/profiles/fournisseurs/liste_fournisseurs.php" class="text-white"
                style="color: white; text-decoration: none;"><strong>Fournisseurs</strong></a></td>
        <td>
            <div class="dropdown">
                <a style="color: white; text-decoration: none;font-size: 13px;" class="dropdown-toggle text-white"
                    href="#" role="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <strong>Engagements</strong>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item"
                            href="http://localhost/BUDGET/profiles/engagements/add_eng1.php"><strong>Nouveau</strong></a>
                    </li>
                    <li><a class="dropdown-item"
                            href="http://localhost/BUDGET/profiles/engagements/liste_engs.php"><strong>Consulter</strong></a>
                    </li>
                </ul>
            </div>
        </td>
        <td>
            <div class="dropdown">
                <a style="color: white; text-decoration: none;font-size: 13px;" class="dropdown-toggle text-white"
                    href="#" role="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <strong>Ordre de Paiment(O.P)</strong>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item"
                            href="http://localhost/BUDGET/profiles/paiement/add_paie1.php"><strong>Nouveau</strong></a>
                    </li>
                    <li><a class="dropdown-item"
                            href="http://localhost/BUDGET/profiles/paiement/liste_op.php"><strong>Consulter</strong></a>
                    </li>
                </ul>
            </div>
        </td>
        <td>
            <div class="dropdown">
                <a style="color: white; text-decoration: none;font-size: 13px;" class="dropdown-toggle text-white"
                    href="#" role="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <strong>Ordre de Recette(O.R)</strong>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item"
                            href="http://localhost/BUDGET/profiles/recettes/add_rec1.php"><strong>Nouveau</strong></a>
                    </li>
                    <li><a class="dropdown-item"
                            href="http://localhost/BUDGET/profiles/recettes/liste_rec.php"><strong>Consulter</strong></a>
                    </li>
                </ul>
            </div>
        </td>
        <td><a href="http://localhost/BUDGET/profiles/dba/liste_compte.php" class="text-white"
                style="color: white; text-decoration: none;"><strong>Compte</strong></a></td>
                <td><a href="http://localhost/BUDGET/profiles/dba/liste_users.php" class="text-white"
                style="color: white; text-decoration: none;"><strong>Utilisateurs</strong></a></td>
        <td><a href="rapport.php" class="text-white" style="color: white; text-decoration: none;"><strong>Mot de
                    passe</strong></a></td>
    </tr>
</table>