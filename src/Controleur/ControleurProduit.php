<?php

namespace App\Pluche\Controleur;

use App\Pluche\Configuration\Configuration;
use App\Pluche\Lib\ConnexionUtilisateur;
use App\Pluche\Lib\MessageFlash;
use App\Pluche\Modele\Repository\ProduitRepository;
use App\Pluche\Modele\DataObjects\Produit;

class ControleurProduit extends ControleurGenerique
{

// --------- Affichage --------- //
    public static function afficherListe(): void
    {
        ControleurProduit::afficherVue('vueGenerale.php', [
            "produits" => (new ProduitRepository())->recuperer(),
            "pagetitle" => "Ensemble des produits.",
            "cheminVueBody" => "produit/liste.php"
        ]);
    }

    public static function afficherDetail(): void
    {
        if (empty($_REQUEST['id']))
        {
            MessageFlash::ajouter("danger", "Id du produit manquant.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherListe&controleur=produit');
        }
        $produit = (new ProduitRepository())->recupererParClePrimaire($_REQUEST['id']);
        if ($produit == null)
        {
            MessageFlash::ajouter("danger", "Produit inexistant.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherListe&controleur=produit');
        }
        else
        {
            ControleurProduit::afficherVue('vueGenerale.php', [
                "produitEnParametre" => $produit,
                "pagetitle" => "{$produit->getNom()}.",
                "cheminVueBody" => "produit/detail.php"
            ]);
        }
    }

    public static function afficherFormulaireCreation(): void
    {
        if(ConnexionUtilisateur::estAdministrateur())
        {
            ControleurProduit::afficherVue('vueGenerale.php', [
                "pagetitle" => "Création d'un produit.",
                "cheminVueBody" => "produit/formulaireCreation.php"
            ]);
        }
        else
        {
            MessageFlash::ajouter("danger", "Vous n'avez pas les droits d'accès.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherListe&controleur=produit');
        }
    }

    public static function afficherFormulaireModification(): void
    {
        if(ConnexionUtilisateur::estAdministrateur())
        {
            if (empty($_REQUEST['id']))
            {
                MessageFlash::ajouter("danger", "Id du produit manquant.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherListe&controleur=produit');
            }
            $produit = (new ProduitRepository())->recupererParClePrimaire($_REQUEST['id']);
            if ($produit == null)
            {
                MessageFlash::ajouter("danger", "Produit inexistant.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherListe&controleur=produit');
            }
            else
            {
                ControleurProduit::afficherVue('vueGenerale.php', [
                    "produitEnParametre" => $produit,
                    "pagetitle" => "Modification du produit n°{$produit->getId()}.",
                    "cheminVueBody" => "produit/formulaireModification.php"
                ]);
            }
        }
        else
        {
            MessageFlash::ajouter("danger", "Vous n'avez pas les droits requis.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=produit&action=afficherListe');
        }
    }

// --------- Fonction --------- //

    public static function creerDepuisFormulaire(): void
    {
        if(ConnexionUtilisateur::estAdministrateur())
        {
            if (empty($_REQUEST['nom']) || empty($_REQUEST['prix']) || empty($_REQUEST['description']))
            {
                MessageFlash::ajouter("warning", "Informations manquantes. (Nom, description, prix.)");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=produit&action=afficherFormulaireCreation');
            }
            if (!is_numeric($_REQUEST['prix']))
            {
                MessageFlash::ajouter("warning", "Le prix doit être un nombre.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=produit&action=afficherFormulaireCreation');
            }
            if (floatval($_REQUEST['prix']) < 0)
            {
                MessageFlash::ajouter("warning", "Prix négatif.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=produit&action=afficherFormulaireCreation');
            }
            if(floatval($_REQUEST['prix']) < 0.01)
            {
                MessageFlash::ajouter("warning", "Prix trop petit, prix minimum de 0.01 €.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=produit&action=afficherFormulaireCreation');
            }
            if (floatval($_REQUEST['prix']) > 99999999.99)
            {
                MessageFlash::ajouter("warning", "Prix trop grand, prix maximum de 99999999.99 €.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=produit&action=afficherFormulaireCreation');
            }
            $produit = new Produit($_REQUEST['nom'], $_REQUEST['prix'], $_REQUEST['description']);
            (new ProduitRepository())->sauvegarder($produit);
            MessageFlash::ajouter("success", "Le produit a bien été créé !");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=produit&action=afficherListe');
        }
        else
        {
            MessageFlash::ajouter("danger", "Vous n'avez pas les droits requis.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=produit&action=afficherListe');
        }
    }

    public static function modifierDepuisFormulaire(): void
    {
        if(ConnexionUtilisateur::estAdministrateur())
        {
            if (empty($_REQUEST['id']) || empty($_REQUEST['nom']) || empty($_REQUEST['prix']) || empty($_REQUEST['description']))
            {
                MessageFlash::ajouter("warning", "Informations manquantes. (id, nom, description, prix.)");

                // Ce if est pour le confort à l'utilisation
                if (!empty($_REQUEST['id'])){ // Si on connait l'id, on appel appel afficherFormulaireModification avec son ID
                    ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=produit&action=?controleur=produit&action=afficherFormulaireModification&id=' . $_REQUEST['id']);
                } else { // Si on connais pas l'id du produit qu'on voulais modifier
                    ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=produit&action=afficherListe');
                }
            }
            $produit = (new ProduitRepository())->recupererParClePrimaire($_REQUEST['id']);
            if ($produit == null)
            {
                MessageFlash::ajouter("danger", "Produit inexistant.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=produit&action=afficherListe');
            }
            if (!is_numeric($_REQUEST['prix']))
            {
                MessageFlash::ajouter("warning", "Le prix doit être un nombre.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=produit&action=?controleur=produit&action=afficherFormulaireModification&id=' . $_REQUEST['id']);
            }
            if (floatval($_REQUEST['prix']) < 0)
            {
                MessageFlash::ajouter("warning", "Prix négatif.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=produit&action=?controleur=produit&action=afficherFormulaireModification&id=' . $_REQUEST['id']);
            }
            if(floatval($_REQUEST['prix']) < 0.01)
            {
                MessageFlash::ajouter("warning", "Prix trop petit, prix minimum de 0.01 €.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=produit&action=?controleur=produit&action=afficherFormulaireModification&id=' . $_REQUEST['id']);
            }
            if (floatval($_REQUEST['prix']) > 99999999.99)
            {
                MessageFlash::ajouter("warning", "Prix trop petit, prix maximum de 99999999.99 €.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=produit&action=?controleur=produit&action=afficherFormulaireModification&id=' . $_REQUEST['id']);
            }
            $newProduit = new Produit($_REQUEST['nom'],$_REQUEST['prix'],$_REQUEST['description'],$_REQUEST['id']);
            (new ProduitRepository())->mettreAJour($newProduit);
            MessageFlash::ajouter("success", "Le produit a bien été mis à jour !");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=produit&action=afficherListe');
        }
        else
        {
            MessageFlash::ajouter("danger", "Vous n'avez pas les droits requis.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=produit&action=afficherListe');
        }
    }

    public static function supprimer(): void
    {
        if(ConnexionUtilisateur::estAdministrateur())
        {
            if (empty($_REQUEST['id']))
            {
                MessageFlash::ajouter("danger", "Id du produit manquant.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=produit&action=afficherListe');
            }
            $produit = (new ProduitRepository())->recupererParClePrimaire($_REQUEST['id']);
            if ($produit == null)
            {
                MessageFlash::ajouter("danger", "Produit inexistant.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=produit&action=afficherListe');
            }

            if ((new ProduitRepository())->supprimer($_REQUEST['id']))
            {
                MessageFlash::ajouter("success", "Le produit n°{$produit->getId()} a bien été supprimé !");
            } else {
                MessageFlash::ajouter("danger", "Echec de la suppression, erreur dans la base de donnée.");
            }
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=produit&action=afficherListe');
        }
        else
        {
            MessageFlash::ajouter("danger", "Vous n'avez pas les droits requis.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=produit&action=afficherListe');
        }
    }
}

