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
            <input type='hidden' name='action' value='connecter'>
                <legend> Connexion </legend>
                <p>
                    <label for="login_id">Login</label>
                    <br><input type="text" name="login" id="login_id" placeholder="Willy34"/>
                </p>
                <p>
                    <label for="password_id">Mot de passe</label>
                    <br><input type="password" value="" placeholder="********" name="password" id="password_id">
                </p>
                <p>
                    <input class="btn" type="submit" value="Connexion"/>
                </p>
        </form>
    </div>
</div>