<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['annee'])) {
    $_SESSION['an'] = $_POST['annee'];
}

// Redirection vers la page précédente
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();