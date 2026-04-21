<?php 
$anneeSession = $_SESSION['an'] ?? date("Y"); // Valeur par défaut si la session n'a pas 'annee'
$anneeActuelle = date("Y");
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
    <!-- Lien pour inclure Google Fonts -->
    <title>BUDGET</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, sans-serif;
        background-color: #f8f9fa;
        margin: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    main {
        flex: 1;
    }

    /* Navbar */
    .navbar {
        background-color: #ffffff;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .navbar a {
        text-decoration: underline;
        font-size: 15px;
    }

    .navbar-brand {
        font-size: 16px;
        font-weight: 600;
        color: #4655a4 !important;
    }

    .nav-link {
        font-size: 14px;
        color: #333 !important;
    }

    .nav-link:hover {
        color: #4655a4 !important;
    }


    /* Dropdown */
    .dropdown-menu {
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .dropdown-item:hover {
        background-color: #4655a4;
        color: #fff;
    }

    .header-clean p {
        margin: 0 !important;
        padding: 0 !important;
    }

    .header-clean span {
        margin: 0 !important;
        padding: 0 !important;
    }

    .img {
        margin: 0 !important;
        padding: 0 !important;
    }

    /* LA CORRECTION IMPORTANTE */
    .header-clean p img {
        margin: 0 !important;
        padding: 0 !important;
    }


    /* ===== HEADER TEXT RESPONSIVE ===== */

    .header-title {
        font-size: clamp(0.65rem, 1.2vw, 0.8rem);
        font-weight: 600;
    }

    .header-motto {
        font-size: clamp(0.45rem, 1vw, 0.55rem);
    }

    .header-text {
        font-size: clamp(0.5rem, 1.1vw, 0.65rem);
    }

    /* LOGO CENTRE */
    .header-flag {
        height: clamp(20px, 3vw, 30px);
    }

    /* LOGO DROITE */
    .header-logo-right {
        height: clamp(30px, 6vw, 60px);
    }

    .header-clean p {
        margin: 0 !important;
        padding: 0 !important;
        line-height: 1.05;
        /* très compact */
    }

    .header-clean a {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
        /* petit espace uniforme */
    }

    /* MOBILE AJUSTEMENT FIN */
    @media (max-width: 576px) {
        .header-clean {
            line-height: 1.1;
        }
    }
    </style>
    <?php 
    include($_SERVER['DOCUMENT_ROOT'] . '/BUDGET/includes/activite.php');
    //session_start();
    ?>
</head>

<body>
    <div class="container-fluid mb-1">

        <!-- HEADER ADMIN -->
        <div class="bg-white header-clean position-relative py-0">

            <!-- LOGO A DROITE -->
            <img src="/BUDGET/assets/images/logo-du-coud.jpg" alt="Logo"
                class="position-absolute top-50 end-0 translate-middle-y me-2 header-logo-right">

            <!-- CONTENU CENTRE -->
            <div class="text-center px-0">

                <a href="http://localhost/BUDGET/shared/accueil" class="text-decoration-none text-dark d-block">

                    <p class="header-title">
                        REPUBLIQUE DU SENEGAL
                    </p>

                    <p class="header-motto mb-0">
                        UN PEUPLE - UN BUT - UNE FOI
                    </p>

                    <img src="/BUDGET/assets/images/senegal.png" alt="Logo" class="header-flag">

                    <p class="header-text">
                        MINISTERE DE L'ENSEIGNEMENT SUPERIEUR DE LA RECHERCHE ET DE L'INNOVATION
                    </p>

                    <p class="header-text">
                        CENTRE DES OEUVRES UNIVERSITAIRES DE DAKAR
                    </p>

                    <p class="header-text fw-semibold">
                        DEPARTEMENT DU BUDGET
                    </p>

                </a>
            </div>

        </div>
        <hr class="mt-1 mb-1">
        <?php if (isset($_SESSION['user'])): ?>
        <nav class="navbar navbar-expand-lg  bg-white">
            <div class="container-fluid ml-0">
                <strong class="navbar-brand" href="#" style="font-size:15px;font-weigth:bold;">SUIVI-EXECUTION DU BUDGET
                    <?php echo htmlspecialchars($_SESSION['an']); ?></strong>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
                    <ul class="navbar-nav">
                        <?php if ($_SESSION['priv'] == 'sag' || $_SESSION['priv'] == 'admin') : ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link active dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">Consulter</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item"
                                        href="http://localhost/BUDGET/profiles/dg/administratifs/rapports">Administratif</a>
                                </li>
                                <li>
                                    <a class="dropdown-item"
                                        href="http://localhost/BUDGET/profiles/dg/actuel_1">Globale</a>
                                </li>
                                <li>
                                    <a class="dropdown-item"
                                        href="http://localhost/BUDGET/profiles/dg/global_1">Globale O.P</a>
                                </li>
                                <li>
                                    <a class="dropdown-item"
                                        href="http://localhost/BUDGET/profiles/dg/borner_1">Borner</a>
                                </li>

                                <li><a class="dropdown-item"
                                        href="http://localhost/BUDGET/profiles/dg/journ_1">Journaliere</a>
                                </li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <?php if ($_SESSION['priv'] == 'sag' || $_SESSION['priv'] == 'admin') : ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link active dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">Dotations</a>
                            <ul class="dropdown-menu">
                                <?php if ($anneeSession == $anneeActuelle): ?>
                                <li>
                                    <a class="dropdown-item"
                                        href="http://localhost/BUDGET/profiles/dotations/add_ini_dot">
                                        Initiale
                                    </a>
                                </li>
                                <?php else: ?>
                                <li>
                                    <span class="dropdown-item text-muted" style="cursor: not-allowed; opacity: 0.5;"
                                        title="Lien désactivé car l'année de session est différente">
                                        Initiale
                                    </span>
                                </li>
                                <?php endif; ?>


                                <?php if ($anneeSession == $anneeActuelle): ?>
                                <li>
                                    <a class="dropdown-item"
                                        href="http://localhost/BUDGET/profiles/dotations/add_rem_dot">
                                        Remaniement
                                    </a>
                                </li>
                                <?php else: ?>
                                <li>
                                    <span class="dropdown-item text-muted" style="cursor: not-allowed; opacity: 0.5;"
                                        title="Lien désactivé car l'année de session est différente">
                                        Remaniement
                                    </span>
                                </li>
                                <?php endif; ?>

                                <li><a class="dropdown-item"
                                        href="http://localhost/BUDGET/profiles/dotations/liste_dotations">Consulter</a>
                                </li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <?php if ($_SESSION['priv'] == 'sag' || $_SESSION['priv'] == 'admin') : ?>
                        <li class="nav-item">
                            <a class="nav-link active"
                                href="http://localhost/BUDGET/profiles/fournisseurs/liste_fournisseurs">Fournisseurs</a>
                        </li>
                        <?php endif; ?>
                        <?php if ($_SESSION['priv'] == 'sag' || $_SESSION['priv'] == 'admin' || $_SESSION['priv'] == 'op') : ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link active dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">Engagements</a>
                            <ul class="dropdown-menu">
                                <?php if ($anneeSession == $anneeActuelle): ?>
                                <li>
                                    <a class="dropdown-item"
                                        href="http://localhost/BUDGET/profiles/engagements/add_eng1">
                                        Nouveau
                                    </a>
                                </li>
                                <?php else: ?>
                                <li>
                                    <span class="dropdown-item text-muted" style="cursor: not-allowed; opacity: 0.5;"
                                        title="Lien désactivé car l'année de session est différente">
                                        Nouveau
                                    </span>
                                </li>
                                <?php endif; ?>

                                <li><a class="dropdown-item"
                                        href="http://localhost/BUDGET/profiles/engagements/liste_engs">Consulter</a>
                                </li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <?php if ($_SESSION['priv'] == 'sag' || $_SESSION['priv'] == 'admin' || $_SESSION['priv'] == 'op') : ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link active dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">M_Paiement</a>
                            <ul class="dropdown-menu">
                                <?php if ($anneeSession == $anneeActuelle): ?>
                                <li>
                                    <a class="dropdown-item"
                                        href="http://localhost/BUDGET/profiles/paiement/add_paie1">
                                        Nouveau
                                    </a>
                                </li>
                                <?php else: ?>
                                <li>
                                    <span class="dropdown-item text-muted" style="cursor: not-allowed; opacity: 0.5;"
                                        title="Lien désactivé car l'année de session est différente">
                                        Nouveau
                                    </span>
                                </li>
                                <?php endif; ?>

                                <li><a class="dropdown-item"
                                        href="http://localhost/BUDGET/profiles/paiement/liste_op">Consulter</a>
                                </li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <?php if ($_SESSION['priv'] == 'sag' || $_SESSION['priv'] == 'admin' || $_SESSION['priv'] == 'or') : ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link active dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">O.R</a>
                            <ul class="dropdown-menu">
                                <?php if ($anneeSession == $anneeActuelle): ?>
                                <li>
                                    <a class="dropdown-item"
                                        href="http://localhost/BUDGET/profiles/recettes/add_rec1">
                                        Nouveau
                                    </a>
                                </li>
                                <?php else: ?>
                                <li>
                                    <span class="dropdown-item text-muted" style="cursor: not-allowed; opacity: 0.5;"
                                        title="Lien désactivé car l'année de session est différente">
                                        Nouveau
                                    </span>
                                </li>
                                <?php endif; ?>

                                <li><a class="dropdown-item"
                                        href="http://localhost/BUDGET/profiles/recettes/liste_rec">Consulter</a>
                                </li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <?php if ($_SESSION['priv'] == 'sag' || $_SESSION['priv'] == 'admin') : ?>
                        <li class="nav-item">
                            <a class="nav-link active"
                                href="http://localhost/BUDGET/profiles/dba/liste_compte">Comptes</a>
                        </li>
                        <?php endif; ?>
                        <?php if ($_SESSION['priv'] == 'sag' || $_SESSION['priv'] == 'admin') : ?>
                        <li class="nav-item">
                            <a class="nav-link active"
                                href="http://localhost/BUDGET/profiles/dba/liste_users">Utilisateurs</a>
                        </li>
                        <?php endif; ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link active dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                Mon_Compte
                            </a>

                            <ul class="dropdown-menu">

                                <!-- CHANGER ANNEE -->
                                <li>
                                    <form action="http://localhost/BUDGET/auth/chance_annee" method="POST"
                                        class="px-3 py-2">
                                        <label class="small fw-semibold">Année</label>
                                        <select name="annee" class="form-select form-select-sm mb-2" required>
                                            <?php
                                                $annee_actuelle = date("Y");
                                                $annee_debut = 2025;

                                                for ($i = $annee_actuelle; $i >= $annee_debut; $i--) {
                                                    $selected = ($_SESSION['an'] == $i) ? 'selected' : '';
                                                    echo "<option value='$i' $selected>$i</option>";
                                                }
                                            ?>
                                        </select>

                                        <button type="submit" class="btn btn-primary btn-sm w-100">
                                            Changer
                                        </button>
                                    </form>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <!-- MOT DE PASSE -->
                                <li>
                                    <a class="dropdown-item" href="http://localhost/BUDGET/shared/updated_mdp">
                                        Mot_de_passe
                                    </a>
                                </li>

                                <!-- DECONNEXION -->
                                <li>
                                    <a class="dropdown-item" href="http://localhost/BUDGET/auth/logout"
                                        onclick="return confirm('Etes-vous sûr ?')">
                                        Déconnexion
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <?php else: ?>
        <!-- Public Header -->
        <!-- <div class="text-center py-0 mb-0">
            <h5 class="text-muted mb-0"> <strong class="navbar-brand">Système de Suivi et d'Exécution
                    Budgétaire</strong></h5>
            <p class="small text-secondary mt-0 mb-0">Plateforme de gestion intégrée</p>
        </div> -->
        <?php endif; ?>
    </div>
</body>