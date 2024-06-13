<?php

/** @var array $commandesEnParametre */

use App\Pluche\Lib\ConnexionUtilisateur;
$somme = 0;
$keyBefore = false;
foreach ($commandesEnParametre as $key)
{

    if(ConnexionUtilisateur::getLoginUtilisateurConnecte()==$key->getAcheteurId() || ConnexionUtilisateur::estAdministrateur()){

        $dateKeyBefore = "";
        if(empty($keyBefore)){
            $keyBefore = $key;

        }else{
            $dateKeyBefore = $keyBefore->getDate();
        }

        $acheteurKeyBefore = $keyBefore->getAcheteurId();
        $acheteurKey = $key->getAcheteurId();
        $adrKeyBefore = $keyBefore->getAdresseLivraison();
        $adrKey = $key->getAdresseLivraison();

        $url = rawurlencode($key->getId());
        $id = htmlspecialchars($key->getId());
        $etat = htmlspecialchars($key->getEtat());
        $keydate = $key->getDate();

        $date = htmlspecialchars($keydate);
        if($dateKeyBefore != $keydate || $adrKey != $adrKeyBefore || $acheteurKeyBefore != $acheteurKey){
            if($somme!=0){
                echo "</div>";
            }
            echo "<br><h3>Commande du {$date}</h3>";
            echo "<br><h5><i>Livraison : ".htmlspecialchars($adrKey)."</i></h5>";
            if(ConnexionUtilisateur::estAdministrateur()){
                echo "<br><h4><i>Acheteur : ".htmlspecialchars($acheteurKey)."</i></h4>";
            }
            echo "<div class='container'>";
        }

            echo "<div class='item'>";
                echo "<p>";
                echo "<a href=\"controleurFrontal.php?controleur=commande&action=afficherDetail&id={$url}\">Commande n°{$id} : {$etat} </a></p>";
            echo "</div>";
        $somme++;
    }
    $keyBefore = $key;
}
echo "</div>";

echo "<p><b>Nombre total de commandes </b> : {$somme} </p>"
?>