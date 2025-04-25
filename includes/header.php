<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="http://localhost/BUDGET/assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://localhost/BUDGET/assets/css.css" rel="stylesheet">
    <!-- Lien pour inclure Google Fonts -->
    <title>CAMPUSCOUD: Gestion Budget</title>
    <link rel="shortcut icon" href="log.gif" type="../image/x-icon">
    <link rel="icon" href="log.gif" type="../assets/image/x-icon">
    <style>
    html,
    body {
        height: 100%;
        margin: 0;
        display: flex;
        flex-direction: column;
    }

    main {
        flex: 1;
        /* Prend tout l'espace disponible */
        font-family: sans-serif;
    }

    thead tr th {
        /* background-color:rgba(21, 155, 204, 0.84) !important; */
        color: white !important;

    }

    footer {
        background-color: white;
        width: 100%;
    }

    /* Style de base des items du dropdown */
    .dropdown-menu .dropdown-item {
        color: black;
        /* Noir par défaut */
        font-size: 12px;
        /* Réduit la taille du texte */
        padding: 5px 10px;
        /* Réduit l'espacement */
        transition: color 0.3s ease, background-color 0.3s ease;
        /* Animation fluide */
    }

    /* Effet au survol */
    .dropdown-menu .dropdown-item:hover {
        color: white;
        /* Blanc quand survolé */
        background-color: #4655a4;
        /* Bleu du <tr> */
    }

    /* Quand on quitte le survol */
    .dropdown-menu .dropdown-item:not(:hover) {
        color: #4655a4;
        /* Bleu après le survol */
    }

    /* Réduction de la taille du bouton principal */
    .dropdown-toggle {
        font-size: 13px;
        /* Réduit la taille du texte */
        padding: 5px 12px;
        /* Réduit la hauteur du bouton */
    }
    </style>

    <script>
    // ⏳ Déconnexion après 1 minute d'inactivité
    let inactivityTimer;

    function resetInactivityTimer() {
        clearTimeout(inactivityTimer);
        inactivityTimer = setTimeout(() => {
            window.location.href = 'http://localhost/BUDGET/auth/logout.php'; // Redirige vers la déconnexion
        }, 180000); // 60000 ms = 1 minute
    }

    // Réinitialise le timer à chaque activité utilisateur
   // ['click', 'mousemove', 'keydown', 'scroll', 'touchstart'].forEach(event => {
      //  document.addEventListener(event, resetInactivityTimer);
    //});

    // Lancer le timer dès le chargement
    //resetInactivityTimer();
    </script>

</head>

<body>
    <div class="container-fluid" style="margin-bottom:20px">
        <!-- Logo -->
        <div class="text-center">
            <a href="http://localhost/BUDGET/shared/accueil.php">
                <img src="/BUDGET/assets/images/logo.jpg" width="1020" height="100" alt="Logo">
            </a>
        </div>
        <br>

        <!-- Barre d'information -->
        <div class="d-flex justify-content-between align-items-center py-2 px-3"
            style="background-color: #4655a4; color: #FFFFFF; font-family: 'Roboto', sans-serif; font-size: 15px; font-weight: 400;">

            <?php if (isset($_SESSION['user'])): ?>
            <!-- Utilisateur connecté -->

            <strong class="mb-0">SUIVI-EXECUTION DU BUDGET <?php echo htmlspecialchars($_SESSION['an']); ?></strong>
            <strong class="mb-0 text-center"> BIENVENUE <?php echo htmlspecialchars($_SESSION['user']); ?></strong>
            <a href="http://localhost/BUDGET/auth/logout.php"
                class="btn btn-sm btn-danger"><strong>Déconnexion</strong></a>
            <?php else: ?>
            <!-- Page de connexion -->
            <div class="text-center w-100">
                <strong class="mb-0">PORTAIL APPLICATIF DU SUIVI-EXECUTION DU BUDGET</strong>
            </div>
            <?php endif; ?>

        </div>
    </div>