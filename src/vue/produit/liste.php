<?php
/** @var array $produits */
$nom = "";

if(\App\Pluche\Lib\ConnexionUtilisateur::estAdministrateur()){
    echo "<button class='btn' onclick =\"window.location.href='controleurFrontal.php?controleur=produit&action=afficherFormulaireCreation';\">Nouveau produit</button>";
}

echo "<div class='articles_container'>";
foreach ($produits as $key)
{
    if($key->getId()!=0){
    $nom = htmlentities($key->getNom());
    $prix = htmlentities($key->getPrix());
    $url = rawurlencode($key->getId());
    $urlprix = rawurldecode($key->getPrix());
    echo "
    <div class='articles_item'>
    <a href=\"controleurFrontal.php?controleur=produit&action=afficherDetail&id={$url}\">
        <div class='articles_contenu'>{$nom}</div>
        <div class='articles_contenu'><img src='/~auxilienk/projetdevweb/ressources/img/lapin.png' alt='Peluche.' height='50px' width='50px' /></div>
        <div class='articles_contenu'>{$prix} €</div>
        </a>
            <div class='articles_contenu'>
            <button class='btn' onclick =\"window.location.href='controleurFrontal.php?action=enregistrerProduit&id={$url}&prix={$urlprix}';\">Ajouter au panier</button></div>
        </div>";
    }
}
echo "</div>";

?>