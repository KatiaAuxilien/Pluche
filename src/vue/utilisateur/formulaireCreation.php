<div class='container_form'>
    <div class='item_form'>
        <form method="<?php
        $method = "post";
        if(\App\Pluche\Configuration\Configuration::getDebug()){
            $method="get";
        }
        echo $method;
        ?>"  action="controleurFrontal.php">
            <input type='hidden' name='controleur' value='utilisateur'>
            <input type='hidden' name='action' value='creerDepuisFormulaire'>
                <legend> Inscription </legend>
                <p>
                    <label for="login_id">Login&#42;</label>
                    <br><input type="text" name="login" id="login_id" placeholder="Willy34" required/>
                </p>
                <p>
                    <label for="email_id">Email&#42;</label>
                    <br><input type="email" value="" placeholder="willywonka@yopmail.com" name="email" id="email_id" required>
                </p>
                <p>
                    <label for="nom_id">Nom&#42;</label>
                    <br><input type="text" placeholder="Wonka" name="nom" id="nom_id" required/>
                </p>
                <p>
                    <label for="prenom_id">Prenom&#42;</label>
                    <br><input type="text" placeholder="Willy" name="prenom" id="prenom_id" required/>
                </p>
                <p>
                    <label for="password_id">Mot de passe&#42;</label>
                    <br><input type="password" minlength="8" maxlength="32" value="" placeholder="****" name="password" id="password_id" required>
                </p>
                <p>
                    <label for="password2_id">Vérification du mot de passe&#42;</label>
                    <br><input type="password" minlength="8" maxlength="32" value="" placeholder="****" name="password2" id="password2_id" required>
                </p>
                <?php
                if(\App\Pluche\Lib\ConnexionUtilisateur::estAdministrateur()){
                    echo "<p>
                    <label for=\"estAdmin_id\">Administrateur</label>
        
                    <input type=\"checkbox\" placeholder=\"\" name=\"estAdmin\" id=\"estAdmin_id\" value=\"1\">
                </p>";
                }
                ?>
                <p>
                    <input class="btn" type="submit" value="Envoyer"/>
                </p>
        </form>
    </div>
</div>