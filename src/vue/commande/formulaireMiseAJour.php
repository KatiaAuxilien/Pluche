<?php
use App\Pluche\Configuration\Configuration;

/** @var array $commandeEnParametre */

$id = htmlspecialchars($commandeEnParametre->getId());
$date = htmlspecialchars($commandeEnParametre->getDate());
$acheteurId = htmlspecialchars($commandeEnParametre->getAcheteurId());
$produitId = htmlspecialchars($commandeEnParametre->getProduitId());
$prix = htmlspecialchars($commandeEnParametre->getPrix());
$etat = htmlspecialchars($commandeEnParametre->getEtat());
$dateReception = htmlspecialchars($commandeEnParametre->getDateReception());

$method = "post";
if (Configuration::getDebug()) {
    $method = "get";
}

echo "
<div class='container_form'>
    <div class='item_form'>
<form method=\"{$method}\" action=\"controleurFrontal.php\">
    <input type='hidden' name='controleur' value='commande'>
    <input type='hidden' name='action' value='mettreAJour'>
    <input type='hidden' name='id' value=\"{$id}\">
        <legend>Modification de la commande</legend>
        <p>
            <label for=\"identificateur_id\">Id</label>
            <br><input type=\"text\" name=\"id\" id=\"identificateur_id\" value=\"{$id}\" readonly/>
        </p>
        <p>
            <label for=\"date_id\">Date </label>
            <br><input type=\"date\" name=\"date\" id=\"date_id\" value=\"{$date}\" readonly/>
        </p>
        <p>
            <label for=\"acheteur_id\">Acheteur </label>
            <br><input type=\"text\" name=\"acheteurId\" id=\"acheteur_id\" value=\"{$acheteurId}\" readonly/>
        </p>
        <p>
            <label for=\"produit_id\">Produit </label>
            <br><input type=\"number\" name=\"produitId\" id=\"produit_id\" value=\"{$produitId}\" readonly/>
        </p>
        <p>
            <label for=\"prix_id\">Prix</label>
            <br><input type=\"number\" name=\"prix\" id=\"prix_id\" value=\"{$prix}\" readonly/>
        </p>           
        <p>
            <label for=\"etat_id\">Etat </label>
            <br><select name=\"etat\" id=\"etat_id\" value=\"{$etat}\" required>
                <option value='En attente de préparation'>En attente de préparation</option>
                <option value='En préparation'>En préparation</option>
                <option value='Livraison en cours'>Livraison en cours</option>
                <option value='Annulée'>Annulée</option>
                <option value='Livrée'>Livrée</option>
                <option value='Remboursée'>Remboursée</option>
                <option value='En cours de retour'>En cours de retour</option>
            </select>
        </p>
        <p>
            <label for=\"dateReception_id\">Date de réception </label>
            <br><input type=\"date\" name=\"dateReception\" id=\"dateReception_id\" value=\"{$dateReception}\"/>
        </p>
        <p>
            <input class='btn' type=\"submit\" value=\"Envoyer\"/>
        </p>
</form>
</div>
</div>";
?>
