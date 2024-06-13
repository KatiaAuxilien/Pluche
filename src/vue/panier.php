<?php

/** @var array $panier */

use App\Pluche\Modele\Repository\ProduitRepository;

$somme=0.0;

echo "<div class='articles_container'>";

if(empty($panier)){
    echo "<h4 style='color:#808080;'><i>Panier vide.</i></h4>";
}else{
    foreach ($panier as $key)
    {
        $id = htmlentities($key);
        $url = rawurlencode($id);
        $produit = (new ProduitRepository())->recupererParClePrimaire($id);
        $nom = htmlentities($produit->getNom());
        $prix = htmlentities($produit->getPrix());
        $somme = $somme + floatval($prix);

        echo "
        <div class='articles_item'>
        <a href=\"controleurFrontal.php?controleur=produit&action=afficherDetail&id={$url}\">
            <div class='articles_contenu'>{$nom}</div>
            <div class='articles_contenu'><img src='/~auxilienk/projetdevweb/ressources/img/lapin.png' alt='Peluche.' height='50px' width='50px' /></div>
            <div class='articles_contenu'>{$prix} €</div>
            </a>
                <div class='articles_contenu'>
                <button class='btn' onclick =\"window.location.href='controleurFrontal.php?&action=retirerProduit&id={$id}';\">Retirer</button></div>
            </div>";
    }
}
echo "</div>";

echo "<form method=";
$method = "post";
if(\App\Pluche\Configuration\Configuration::getDebug()){
    $method="get";
}
echo $method;
echo " action=\"controleurFrontal.php\" class='item_form' style='width: 97%;'>
    <input type='hidden' name='controleur' value='commande'>
    <input type='hidden' name='action' value='creerDepuisPanier'>
    <p>
        <label for=\"adresse_id\">Adresse de Livraison</label>
        <br><input style='width: 30%;' type=\"text\" name=\"adresse\" id=\"adresse_id\" placeholder=\"23 avenue Winnie l'Ourson, 00000 PELUCHELAND\" required/>
    </p>
    <p> <b> Total : </b> {$somme} €</p>
    <p>
        <input class=\"btn\" style='width: 96.5%;' type=\"submit\" value=\"Commander\"/>
    </p></form>";
?>