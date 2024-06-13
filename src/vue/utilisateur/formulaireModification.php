<?php
use App\Pluche\Lib\ConnexionUtilisateur;
use App\Pluche\Configuration\Configuration;
/** @var array $utilisateur */
$login = htmlspecialchars($utilisateur->getLogin());
$nom = htmlspecialchars($utilisateur->getNom());
$prenom = htmlspecialchars($utilisateur->getPrenom());
$email = htmlspecialchars($utilisateur->getEmail());

$method = "post";
if (Configuration::getDebug()) {
    $method = "get";
}

echo "
<div class='container_form'>
    <div class='item_form'>
        <form method=\"{$method}\" action=\"controleurFrontal.php\">
            <input type='hidden' name='controleur' value='utilisateur'>
            <input type='hidden' name='action' value='modifierDepuisFormulaire'>
                <legend> Mise à jour</legend>
                <p>
                    <label for=\"login_id\">Login</label>
                    <br><input type=\"text\" name=\"login\" id=\"login_id\" value=\"{$login}\" readonly/>
                </p>
                <p>
                    <label  for=\"nom_id\">Nom&#42;</label>
                    <br><input type=\"text\" placeholder=\"Auxilien\" name=\"nom\" id=\"nom_id\" value=\"{$nom}\" required/>
                </p>
                <p>
                    <label  for=\"prenom_id\">Prenom&#42;</label>
                    <br><input type=\"text\" placeholder=\"Katia\" name=\"prenom\" id=\"prenom_id\" value=\"{$prenom}\" required/>
                </p>
                <p>
                    <label   for=\"email_id\">Email&#42;</label>
                    <br><input type=\"email\" placeholder=\"toto@yopmail.com\" name=\"email\" id=\"email_id\" value=\"{$email}\" required>
                </p>
                ";
        if (!ConnexionUtilisateur::estAdministrateur()) {
            echo "
                <p>
                    <label  for=\"oldpassword_id\">Ancien mot de passe&#42;</label>
                    <br><input type=\"password\" minlength=\"8\" maxlength=\"32\" value=\"\" placeholder=\"\" name=\"passwordold\" id=\"oldpassword_id\" required>
                </p>";
        }
        echo "
                <p>
                    <label  for=\"password_id\">Mot de passe&#42;</label>
                    <br><input type=\"password\" minlength=\"8\" maxlength=\"32\" value=\"\" placeholder=\"\" name=\"password\" id=\"password_id\" required>
                </p>
                <p>
                    <label  for=\"password2_id\">Vérification du mot de passe&#42;</label>
                    <br><input type=\"password\"  minlength=\"8\" maxlength=\"32\" value=\"\" placeholder=\"\" name=\"password2\" id=\"password2_id\" required>
                </p>
                ";

        if (ConnexionUtilisateur::estAdministrateur()) {
            echo "<p>
                        <label  for=\"estAdmin_id\">Administrateur</label>
                        <input type=\"checkbox\" placeholder=\"\" name=\"estAdmin\" id=\"estAdmin_id\" ";

            if ($utilisateur->getEstAdmin() == true) {
                echo " value=\"1\" checked";
            }else{
                echo " value=\"0\"";
            }
            echo "></p>";
        }
    echo "
                    <p>
                        <input class=\"btn\" type=\"submit\" value=\"Envoyer\"/>
                    </p>
            </div>
        </div>
    </form>";

