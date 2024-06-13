<?php
use App\Pluche\Lib\ConnexionUtilisateur;
/** @var array $utilisateur */

$login = htmlspecialchars($utilisateur->getLogin());
$nom = htmlspecialchars($utilisateur->getNom());
$prenom = htmlspecialchars($utilisateur->getPrenom());
$url = rawurlencode($utilisateur->getLogin());

echo"<div class='container_form'>";
    echo"<div class='item_form'>";

echo "<p> Utilisateur {$login} est nommé {$nom} {$prenom} </p>";

if(ConnexionUtilisateur::estUtilisateur($login) || ConnexionUtilisateur::estAdministrateur())
{
    echo "<p><button class='btn' onclick =\"window.location.href='controleurFrontal.php?controleur=utilisateur&action=afficherFormulaireModification&login={$url}';\">Modifier</button></p>";
    if(ConnexionUtilisateur::estAdministrateur()){
        echo "<p><button class='btn' onclick =\"window.location.href='controleurFrontal.php?controleur=utilisateur&action=supprimer&login={$url}';\">Supprimer</button></p>";
    }
}
echo "</div></div>";
?>

