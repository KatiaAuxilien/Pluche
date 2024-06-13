<?php

namespace App\Pluche\Controleur;

use App\Pluche\Configuration\Configuration;
use App\Pluche\Lib\MessageFlash;
use App\Pluche\Modele\DataObjects\Utilisateur;
use App\Pluche\Modele\Repository\UtilisateurRepository;
use App\Pluche\Lib\ConnexionUtilisateur;
use App\Pluche\Lib\VerificationEmail;
use App\Pluche\Lib\MotDePasse;
use App\Pluche\Modele\HTTP\Session;
use App\Pluche\Controleur\ControleurGenerique;

class ControleurUtilisateur extends ControleurGenerique
{

// --------- Affichages --------- //

    public static function afficherListe(): void
    {
        if(ConnexionUtilisateur::estConnecte())
        {
            if(ConnexionUtilisateur::estAdministrateur())
            {
                ControleurUtilisateur::afficherVue('vueGenerale.php', [
                    "utilisateurs" => (new UtilisateurRepository())->recuperer() ,
                    "pagetitle" => "Liste des utilisateurs.",
                    "cheminVueBody" => "utilisateur/liste.php"
                ]);
            }else{
                MessageFlash::ajouter("danger", "Vous n'avez pas les droits d'accès.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?');
            }
        }else{
            MessageFlash::ajouter("warning", "Connexion requise.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherFormulaireConnexion&controleur=utilisateur');
        }
    }

    public static function afficherDetail(): void
    {
        if(ConnexionUtilisateur::estConnecte()){
            if (empty($_REQUEST['login'])) { // Si l'id n'est pas donné
                MessageFlash::ajouter("warning", "Id manquant.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?');
            }
            $user = (new UtilisateurRepository())->recupererParClePrimaire($_REQUEST['login']);
            if ($user == null)
            {
                MessageFlash::ajouter("danger", "Utilisateur inexistant.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?');
            }
            if(ConnexionUtilisateur::estAdministrateur() || ConnexionUtilisateur::estUtilisateur($user->getLogin()))
            {
                ControleurProduit::afficherVue('vueGenerale.php', [
                    "utilisateur" => $user,
                    "pagetitle" => "{$user->getLogin()}",
                    "cheminVueBody" => "utilisateur/detail.php"
                ]);
            }
            else
            {
                MessageFlash::ajouter("danger", "Vous n'avez pas les droits d'accès.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?');
            }
        }
        else
        {
            MessageFlash::ajouter("warning", "Connexion requise.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherFormulaireConnexion&controleur=utilisateur');
        }
    }

    public static function afficherFormulaireCreation(): void
    {
        if(!ConnexionUtilisateur::estConnecte())
        {
            ControleurUtilisateur::afficherVue('vueGenerale.php', [
                "pagetitle" => "Inscription.",
                "cheminVueBody" => "utilisateur/formulaireCreation.php"
            ]);
        }
        else
        {
            MessageFlash::ajouter("warning", "Vous êtes déjà connecté.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherDetail&controleur=utilisateur&login='.rawurldecode(ConnexionUtilisateur::getLoginUtilisateurConnecte()));
        }
    }

    public static function afficherFormulaireModification()
    {
        if(ConnexionUtilisateur::estConnecte())
        {
            if (empty($_REQUEST['login']))
            {
                MessageFlash::ajouter("danger", "Id non précisé.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?');
            }
            $user = (new UtilisateurRepository())->recupererParClePrimaire($_REQUEST['login']);
            if ($user == null)
            {
                MessageFlash::ajouter("danger", "Utilisateur inexistant.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?');
            }
            if (ConnexionUtilisateur::estUtilisateur($user->getLogin()) || ConnexionUtilisateur::estAdministrateur()){
                ControleurProduit::afficherVue('vueGenerale.php', [
                    "utilisateur" => $user,
                    "pagetitle" => "Modification de {$user->getLogin()}.",
                    "cheminVueBody" => "utilisateur/formulaireModification.php"
                ]);
            }
            else
            {
                MessageFlash::ajouter("danger", "Vous n'avez pas les droits d'accès.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?');
            }
        }
        else
        {
            MessageFlash::ajouter("warning", "Connexion requise.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherFormulaireConnexion&controleur=utilisateur');
        }
    }

    public static function afficherFormulaireConnexion() : void
    {
        if(!ConnexionUtilisateur::estConnecte())
        {
            ControleurUtilisateur::afficherVue('vueGenerale.php', [
                "pagetitle" => "Page de connexion.",
                "cheminVueBody" => "utilisateur/formulaireConnexion.php"
            ]);
        }
        else
        {
            MessageFlash::ajouter("warning", "Vous êtes connecté, déconnectez-vous pour créer un nouveau compte.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherDetail&controleur=utilisateur&login='.rawurldecode(ConnexionUtilisateur::getLoginUtilisateurConnecte()));
        }
    }

// --------- Fonctions --------- //

    public static function creerDepuisFormulaire(): void
    {
        if(!ConnexionUtilisateur::estConnecte() || ConnexionUtilisateur::estAdministrateur())
        {
            if ( empty($_REQUEST['login']) || empty($_REQUEST['email']) || empty($_REQUEST['nom'])|| empty($_REQUEST['prenom']) || empty($_REQUEST['password']) || empty($_REQUEST['password2']))
            {
                MessageFlash::ajouter("warning", "Champ(s) manquant(s).");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=utilisateur&action=afficherFormulaireCreation');
            }
            $user = (new UtilisateurRepository())->recupererParClePrimaire($_REQUEST['login']);
            if ($user != null)
            {
                MessageFlash::ajouter("warning", "Login existant.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=utilisateur&action=afficherFormulaireCreation');
            }
            if ($_REQUEST['password'] != $_REQUEST['password2'])
            {
                MessageFlash::ajouter("warning", "Mots de passe distincts.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=utilisateur&action=afficherFormulaireCreation');
            }
            if (!filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL))
            {
                MessageFlash::ajouter("warning", "L'email est au mauvais format.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=utilisateur&action=afficherFormulaireCreation');
            }

            $msg = MotDePasse::verifierSolidite($_REQUEST['password']);
            if($msg != true)
            {
                MessageFlash::ajouter("warning", $msg);
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=utilisateur&action=afficherFormulaireCreation');
            }

            $tab = array(
                "login" => $_REQUEST['login'],
                "nom" => $_REQUEST['nom'],
                "prenom" => $_REQUEST['prenom'],
                "password" => $_REQUEST['password'],
                "estAdmin" => 0,
                "email" => $_REQUEST['email'],
            );

            if (isset($_REQUEST['estAdmin']))
            { // Si on veux créer l'utilisateur avec les perms admin
                if ($_REQUEST['estAdmin'])
                { // Si c'est set sur 1 (donc OUI)
                    if (ConnexionUtilisateur::estAdministrateur())
                    { // Si il a le droit de faire ca
                        $tab['estAdmin'] = 1;
                    }
                    else
                    { // si pas le droit -> erreur
                        MessageFlash::ajouter("warning", "Vous n'avez pas les droits requis.");
                        ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=utilisateur&action=afficherFormulaireCreation');
                    }
                }
            }

            $user = Utilisateur::construireDepuisFormulaire($tab);
            VerificationEmail::envoiEmailValidation($user);
            (new UtilisateurRepository())->sauvegarder($user);
            MessageFlash::ajouter("success", "L'utilisateur a bien été créé ! Veuilez valider votre adresse email pour vous connecter.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=utilisateur&action=afficherFormulaireConnexion');
        }
        else
        {
            MessageFlash::ajouter("danger", "Vous êtes connecté, déconnectez-vous pour créer un nouveau compte.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherDetail&controleur=utilisateur&login='.rawurldecode(ConnexionUtilisateur::getLoginUtilisateurConnecte()));
        }
    }

    public static function modifierDepuisFormulaire()
    {
        if(ConnexionUtilisateur::estConnecte())
        {

            if ( (empty($_REQUEST['passwordold']) && !ConnexionUtilisateur::estAdministrateur()) || empty($_REQUEST['email']) || empty($_REQUEST['nom'])|| empty($_REQUEST['prenom']) || empty($_REQUEST['password']) || empty($_REQUEST['password2']))
            {
                MessageFlash::ajouter("warning", "Champ(s) manquant(s).");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?');
            }
            $user = (new UtilisateurRepository())->recupererParClePrimaire($_REQUEST['login']);
            if ($user == null)
            {
                MessageFlash::ajouter("danger", "Utilisateur inexistant.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?');
            }
            if(ConnexionUtilisateur::estAdministrateur() || ConnexionUtilisateur::estUtilisateur($user->getLogin()))
            {
                if ($_REQUEST['password'] != $_REQUEST['password2'])
                {
                    MessageFlash::ajouter("warning", "Mot de passe distincts.");
                    ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=utilisateur&action=afficherFormulaireModification&login=' . $user->getLogin());
                }
                if (!filter_var($_REQUEST['email'],FILTER_VALIDATE_EMAIL))
                {
                    MessageFlash::ajouter("warning", "L'email au mauvais format.");
                    ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=utilisateur&action=afficherFormulaireModification&login=' . $user->getLogin());
                }
                if(!ConnexionUtilisateur::estAdministrateur()){
                    if (!(MotDePasse::verifier($_REQUEST['passwordold'], $user->getPassword())))
                    {
                        MessageFlash::ajouter("warning", "Mot de passe incorrect.");
                        ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=utilisateur&action=afficherFormulaireModification&login=' . $user->getLogin());
                    }
                }

                $msg = MotDePasse::verifierSolidite($_REQUEST['password']);
                if(!$msg)
                {
                    MessageFlash::ajouter("warning", $msg);
                    ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=utilisateur&action=afficherFormulaireCreation');
                }

                $user->setPassword(MotDePasse::hacher($_REQUEST['password']));
                $user->setPrenom($_REQUEST['prenom']);
                $user->setNom($_REQUEST['nom']);
                $emailBefore=$user->getEmail();
                $user->setEmail($_REQUEST['email']);
                $user->setEstAdmin(0);
                if (isset($_REQUEST['estAdmin']))
                {
                    if (ConnexionUtilisateur::estAdministrateur())
                    {
                        $user->setEstAdmin(1);
                    }
                    else
                    {
                        MessageFlash::ajouter("danger", "Vous n'avez pas les droits requis.");
                        ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=utilisateur&action=afficherFormulaireModification&login=' . $user->getLogin());
                    }
                }

                if(strcmp($emailBefore,$_REQUEST['email']) !=0)
                {
                    VerificationEmail::envoiEmailValidation($user);
                }

                (new UtilisateurRepository())->mettreAJour($user);
                MessageFlash::ajouter("success", "L'utilisateur " . $user->getLogin() . " a bien été mis à jour !");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=utilisateur&action=afficherDetail&login=' . $user->getLogin());
            }
            else
            {
                MessageFlash::ajouter("danger", "Vous n'avez pas les droits requis.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?');
            }
        }
        else
        {
            MessageFlash::ajouter("warning", "Connexion requise.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherFormulaireConnexion&controleur=utilisateur');
        }
    }

    public static function supprimer()
    {
        if (ConnexionUtilisateur::estAdministrateur())
        {
            if (empty($_REQUEST['login']))
            {
                MessageFlash::ajouter("danger", "Login manquant.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?');
            }
            $user = (new UtilisateurRepository())->recupererParClePrimaire($_REQUEST['login']);
            if ($user == null)
            {
                MessageFlash::ajouter("danger", "Utilisateur inexistant.");
            }
            if ((new UtilisateurRepository())->supprimer($user->getLogin()))
            {
                MessageFlash::ajouter("success", "L'utilisateur " . $user->getLogin() . " a bien été supprimé !");
            }
            else
            {
                MessageFlash::ajouter("danger", "Erreur de suppression de l'utilisateur " . $user->getLogin() . ".");
            }
        }
        else
        {
            MessageFlash::ajouter("danger", "Vous n'avez pas les droits requis.");
        }
        ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?');
    }

    public static function validerEmail() : void
    {
        if(!isset($_REQUEST['login']) || !isset($_REQUEST['nonce']))
        {
            MessageFlash::ajouter("danger", "Email invalide.");
        }
        if(VerificationEmail::traiterEmailValidation($_REQUEST['login'],$_REQUEST['nonce']))
        {
            MessageFlash::ajouter("success", "Email validé pour " . $_REQUEST['login']." !");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=utilisateur&action=afficherFormulaireConnexion');
            MessageFlash::ajouter("warning", "Erreur : Email invalide.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=utilisateur&action=afficherFormulaireConnexion');
        }
        else
        {
            MessageFlash::ajouter("danger", "Email invalide.");
        }
        ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=utilisateur&action=afficherFormulaireConnexion');
    }

    public static function connecter(){
        if(!ConnexionUtilisateur::estConnecte())
        {
            if (empty($_REQUEST['login']) || empty($_REQUEST['password']))
            {
                MessageFlash::ajouter("danger", "Champ(s) manquant(s).");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=utilisateur&action=afficherFormulaireConnexion');
            }
            $user = (new UtilisateurRepository())->recupererParClePrimaire($_REQUEST['login']);
            if ($user == null)
            {
                MessageFlash::ajouter("danger", "Login inconnu.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=utilisateur&action=afficherFormulaireConnexion');
            }
            if (!VerificationEmail::aValideEmail($user))
            {
                MessageFlash::ajouter("warning", "Connexion impossible, email non validé.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=utilisateur&action=afficherFormulaireConnexion');
            }
            if (!MotDePasse::verifier($_REQUEST['password'], $user->getPassword()))
            {
                MessageFlash::ajouter("warning", "Mot de passe incorect.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=utilisateur&action=afficherFormulaireConnexion');
            }
            ConnexionUtilisateur::connecter($user->getLogin());
            MessageFlash::ajouter("success", "Connexion reussie !");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=utilisateur&action=afficherDetail&login='.$user->getLogin());
        }
        else
        {
            MessageFlash::ajouter("warning", "Vous êtes déjà connecté.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherDetail&controleur=utilisateur&login='.rawurldecode(ConnexionUtilisateur::getLoginUtilisateurConnecte()));
        }
    }

    public static function deconnecter(){
        if (ConnexionUtilisateur::estConnecte())
        {
            ConnexionUtilisateur::deconnecter();
            // Les message ne servent à rien car il sont stocké dan la session mais qui est détruite à la deconnexion.
            MessageFlash::ajouter("success", "Utilisateur déconnecté");
        }
        else
        {
            MessageFlash::ajouter("warning", "Aucun utilisateur à déconnecter.");
        }
        ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?');
    }

    public static function ajouterProduit(){
        ConnexionUtilisateur::ajouterProduitPanier($_REQUEST["idProduit"],$_REQUEST["nomProduit"]);
    }

}