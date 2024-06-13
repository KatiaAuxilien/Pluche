<?php

namespace App\Pluche\Lib;

use App\Pluche\Configuration\Configuration;
use App\Pluche\Modele\DataObjects\Utilisateur;
use App\Pluche\Modele\Repository\UtilisateurRepository;

class VerificationEmail
{
    public static function envoiEmailValidation(Utilisateur $utilisateur): void
    {
        $loginURL = rawurlencode($utilisateur->getLogin());
        $nonceURL = rawurlencode($utilisateur->getNonce());
        $URLAbsolue = Configuration::getURLAbsolue();
        $lienValidationEmail = "$URLAbsolue?action=validerEmail&controleur=utilisateur&login=$loginURL&nonce=$nonceURL";

        $destinataire = $utilisateur->getEmailAValider();
        $sujet = "Pluche - Validation d'email.";
        $corpsEmail = "<!DOCTYPE html>
        <html lang=\"fr\">
        <head>
          <meta charset=\"UTF-8\">
          <title>{$sujet}</title>
        </head>
        <body>
          <div class=`\"container\">
            <h1>Pluche</h1>
            <p> Bonjour {$loginURL} ! <p>
            <p>Merci pour votre inscription ! Veuillez cliquer sur lien ci-dessous pour confirmer votre inscription : <a href=\"$lienValidationEmail\" >ici</a></p>
            <p> Cordialement,<p>
            <p><i> L'équipe Pluche </i><p> 
          </div>
        </body>
        </html>";
        // Pour envoyer un email contenant du HTML
        $enTete = "MIME-Version: 1.0" . "\r\n";
        $enTete .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                mail($destinataire,$sujet,$corpsEmail,$enTete);
    }

    public static function traiterEmailValidation($login, $nonce): bool
    {
        $Utilisateur = (new UtilisateurRepository())->recupererParClePrimaire($login);
        if (strcmp($Utilisateur->getNonce(), $nonce) == 0) {
            $Utilisateur->setEmail($Utilisateur->getEmailAValider());
            $Utilisateur->setEmailAValider("");
            $Utilisateur->setNonce("");
            (new UtilisateurRepository())->mettreAJour($Utilisateur);
            return true;
        } else {
            return false;
        }
    }

    public static function aValideEmail(Utilisateur $utilisateur): bool
    {
        if (strcmp($utilisateur->getEmail(), "") == 0) {
            return false;
        } else {
            return true;
        }
    }
}
