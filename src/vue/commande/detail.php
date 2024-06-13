<?php
/** @var array $commandeEnParametre
 * @var array $produitEnParametre
 */

use App\Pluche\Lib\ConnexionUtilisateur;

$id = htmlspecialchars($commandeEnParametre->getId());
$date = htmlspecialchars($commandeEnParametre->getDate());
$acheteurId = htmlspecialchars($commandeEnParametre->getAcheteurId());
$produitId = htmlspecialchars($commandeEnParametre->getProduitId());
    $nomProduit = htmlspecialchars($produitEnParametre->getNom());
$prix = htmlspecialchars($commandeEnParametre->getPrix());
$etat = htmlspecialchars($commandeEnParametre->getEtat());
$adresse = htmlspecialchars($commandeEnParametre->getAdresseLivraison());
$dateReception = htmlspecialchars($commandeEnParametre->getDateReception());
$url = rawurlencode($commandeEnParametre->getId());

echo"<div class='container_detail'>";
echo"<div class='item_detail'>";
echo "<h3 class='info'>Commande n°{$id}</h3>";
echo "<p class='info'>Commandée le : {$date}</p>";
echo "<p class='info'>Adresse de livraison : {$adresse}</p>";
echo "<p class='info'>Acheteur : {$acheteurId}</p>";
echo "<p class='info'>Produit n°{$produitId} : {$nomProduit} </p>";
echo "<p class='info'>Prix : {$prix}</p>";

    if($etat == "Livrée" || $etat == "Remboursée")
    {
        echo "<p class='info'>{$etat} le {$dateReception} </p>";
    }
    if($etat == "Abandonnée" || $etat == "Annulée" )
    {
        echo "<p class='info'>Etat : {$etat} </p>";
        echo "<p class='info'>Remboursement prévue le {$dateReception}</p>";
    }
    else{
        echo "<p class='info'>Etat : {$etat} </p>";
        echo "<p class='info'> Livraison prévuée le {$dateReception}";
    }

if(ConnexionUtilisateur::estUtilisateur($acheteurId) && ($etat == 'En attente de préparation' || $etat == 'En préparation'))
{
    echo "<p><button class='btn' onclick =\"window.location.href='controleurFrontal.php?controleur=commande&action=abandonner&id={$url}';\">Abandonner</button></p>";
}
if (ConnexionUtilisateur::estAdministrateur())
{
    echo "<p><button  class='btn' onclick =\"window.location.href='controleurFrontal.php?controleur=commande&action=supprimer&id={$url}';\">Supprimer</button></p>
        <p><button  class='btn' onclick =\"window.location.href='controleurFrontal.php?controleur=commande&action=afficherFormulaireMiseAJour&id={$url}';\">Modifier</button></p>";
}
echo "</div></div>";
?>