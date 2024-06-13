
<div class='container_form'>
    <div class='item_form'>
        <form method="<?php
        $method = "post";
        if(\App\Pluche\Configuration\Configuration::getDebug()){
            $method="get";
        }
        echo $method;
        ?>" action="controleurFrontal.php">
            <input type='hidden' name='action' value='creerDepuisFormulaire'>
                <legend>Nouveau produit</legend>
                <p>
                    <label for="nom_id">Nom&#42;</label>
                    <br><input type="text" placeholder="Creeper" name="nom" id="nom_id" required/>
                </p>
                <p>
                    <label for="prix_id">Prix&#42;</label>
                    <br><input type="number"  step='0.01' min='0.01' max='99999999.99' placeholder="89" name="prix" id="prix_id" required/>
                </p>
                <p>
                    <label for="desc_id">Description&#42;</label>
                </p>
                <p>
                    <textarea placeholder="Jolie peluche verte." name="description" id="desc_id" cols="30" rows="6" maxlength="255" required></textarea>
                </p>
                <p>
                    <input class="btn" type="submit" value="Envoyer"/>
                </p>
        </form>
    </div>
</div>