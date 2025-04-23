<?php include 'includes/header.php'; ?>


<div class="container">
    <!-- Le contenu principal de votre page va ici -->
    <div class="text text-center" style="margin-bottom:5px">
        <b>Veuillez vous identifier et indiquer l'annee budgetaire !</b>
    </div>
    <div class="text-center" style="margin-bottom:20px;color:red;">
        <b>Attention: Ne jamais saisir son mot de passe devant une tierce personne!!!</b>
    </div>
    <div class="text-center" style="color:green;">
        <strong>AUTHENTIFICATION</strong>
    </div>
    <!-- Formulaire centré avec une largeur de 50% et bordures en haut et en bas -->
    <div
        style="width: 50%; margin: 0 auto; border-top: 3px solid #4655a4; border-bottom: 3px solid #4655a4; padding: 20px;">
        <form action="auth/log.php" method="POST">
            <table style="width: 80%;margin: 0 auto; text-align: left;">
                <?php if (!empty($_GET["error"])): ?>
                <center> <i class="text-center" style="color: red;"><?php echo $_GET["error"]; ?></i></center>
                <?php endif; ?>
                <tr>
                    <td style="padding: 10px 0;"><strong>Utilisateur</strong></td>
                    <td style="padding: 10px 0;">
                        <input type="text" name="utilisateur" placeholder="Matricule Agent..." style="width: 100%; padding: 7px;" required />
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0;"><strong>Mot de passe</strong></td>
                    <td style="padding: 10px 0;">
                        <input type="password" name="motdepasse" placeholder="**** password ****" style="width: 100%; padding: 7px;" required />
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0;"><strong>Année Budgétaire</strong></td>
                    <td style="padding: 10px 0;">
                        <select name="annee_budgetaire" style="width: 100%; padding: 8px;" required>
                            <option value="">--Sélectionnez année--</option>
                            <?php
                                $annee_actuelle = date("Y");
                                for ($i = $annee_actuelle; $i >= $annee_actuelle - 10; $i--) {
                                    echo "<option value='$i'>$i</option>";
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="padding: 20px 0; text-align: center;">
                        <button type="submit"
                            style="padding: 10px 20px; background-color: #4655a4; color: white; border: none; cursor: pointer;">
                            <strong>Se connecter</strong>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center;">
                        <a href="#" style="color: #4655a4; text-decoration: none;">Mot de passe oublié ?</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>


<?php include 'includes/footer.php'; ?>