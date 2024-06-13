<?php
namespace App\Pluche\Controleur;

use App\Pluche\Configuration\Configuration;
use App\Pluche\Lib\MessageFlash;
use App\Pluche\Lib\PanierControleur;
use App\Pluche\Modele\Repository\ProduitRepository;

class ControleurGenerique {

    /**
     * @param string $cheminVue
     * @param array $parametres
     * @return void
     */
    protected static function afficherVue(string $cheminVue,
                                          array  $parametres = []): void
    {
        extract($parametres);
        require __DIR__ . "/../vue/$cheminVue";
    }

    public static function redirectionVersURL(string $url):void
    {
        header("Location: $url");
        exit();
    }

    public static function panier() : void
    {
        if(!PanierControleur::existe())
        {
            PanierControleur::enregistrer();
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=panier');
        }
        $panier = PanierControleur::lire();
        ControleurGenerique::afficherVue('vueGenerale.php', [
            "pagetitle" => "Panier.",
            "cheminVueBody" => "panier.php",
            "panier" => $panier
        ]);
    }


    public static function enregistrerProduit() :void
    {
        if(!empty($_REQUEST['id']))
        {
            $id = $_REQUEST['id'];
            $produit = (new ProduitRepository())->recupererParClePrimaire($_REQUEST['id']);
            if ($produit == null)
            {
                MessageFlash::ajouter("danger", "Produit inexistant.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherListe&controleur=produit');
            }
            if(!PanierControleur::existe())
            {
                PanierControleur::enregistrer();
            }else{
                $panier = PanierControleur::lire();
                if(in_array($id,$panier)){
                    MessageFlash::ajouter("warning", "Produit déjà dans le panier.");
                    ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=panier');
                }
            }
            PanierControleur::enregistrerProduit($id);
            MessageFlash::ajouter("success", "Produit ajouté au panier.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=panier');
        }
        MessageFlash::ajouter("danger", "Echec de l'ajout au panier.");
        ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=panier');
    }

    public static function retirerProduit() : void
    {
        if(!empty($_REQUEST['id']))
        {
            $id = $_REQUEST['id'];
            if(PanierControleur::existeProduit($id))
            {
                PanierControleur::supprimerProduit($id);
                MessageFlash::ajouter("success", "Produit retiré du panier.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=panier');
            }
            MessageFlash::ajouter("danger", "Produit non présent dans le panier.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=panier');
        }
        MessageFlash::ajouter("danger", "Echec de la suppression du panier.");
        ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=panier');
    }
}