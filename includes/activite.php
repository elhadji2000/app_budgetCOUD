<script>
    // 🔁 Rechargement automatique de la page toutes les 10 secondes
    setTimeout(() => {
        location.reload();
    }, 10000); // 10000 ms = 10 secondes

    // ⏳ Déconnexion après 1 minute d'inactivité
    let inactivityTimer;

    function resetInactivityTimer() {
        clearTimeout(inactivityTimer);
        inactivityTimer = setTimeout(() => {
            window.location.href = 'http://localhost/BUDGET/auth/logout.php'; // Redirige vers la déconnexion
        }, 60000); // 60000 ms = 1 minute
    }

    // Réinitialise le timer à chaque activité utilisateur
    ['click', 'mousemove', 'keydown', 'scroll', 'touchstart'].forEach(event => {
        document.addEventListener(event, resetInactivityTimer);
    });

    // Lancer le timer dès le chargement
    resetInactivityTimer();
</script>
