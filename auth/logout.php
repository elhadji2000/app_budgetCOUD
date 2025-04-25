<?php
session_start();
session_unset();
session_destroy(); // Détruire la session
header("Location: ../index.php"); // Rediriger vers la page de connexion
exit();
?>
