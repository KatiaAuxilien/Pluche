<?php

/** @var array $utilisateurs */
$login = "";

echo "<div class='container'>";
foreach ($utilisateurs as $key) {
    $login = htmlentities($key->getLogin());
    $url = rawurlencode($key->getLogin());
    echo "<a href=\"controleurFrontal.php?controleur=utilisateur&action=afficherDetail&login={$url}\"><div class='item'> Utilisateur de login : {$login}.</div></a>";
}
echo "</div>";


