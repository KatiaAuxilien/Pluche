<?php
/** @var array $produitEnParametre */

$id = htmlspecialchars($produitEnParametre->getId());
$nom = htmlspecialchars($produitEnParametre->getNom());
$prix = htmlspecialchars($produitEnParametre->getPrix());
$description = htmlspecialchars($produitEnParametre->getDescription());
$url = rawurlencode($produitEnParametre->getId());

echo"<div class='container_detail'>";
    echo"<div class='item_detail'>";

    echo "<h3 class='info'>";
    if(\App\Pluche\Lib\ConnexionUtilisateur::estAdministrateur())
    {
        echo "<i>n° {$id} </i>";
    }
    echo "{$nom}</h3>";
    echo "<h4 class='info'>{$prix} €</h4>";
    echo "<p class='info'>{$description}</p>";

    if($id != 0){
        echo "<button class='btn' onclick =\"window.location.href='controleurFrontal.php?action=enregistrerProduit&id={$url}&prix={$prix}';\"> Ajouter au panier </button>";
    }
    if(\App\Pluche\Lib\ConnexionUtilisateur::estAdministrateur() && $id != 0)
    {
        echo "<button class='btn' onclick =\"window.location.href='controleurFrontal.php?controleur=produit&action=supprimer&id={$url}';\">Supprimer</button>
            <button class='btn' onclick =\"window.location.href='controleurFrontal.php?controleur=produit&action=afficherFormulaireModification&id={$url}';\">Modifier</button>";
    }
echo "</div></div>";
?>