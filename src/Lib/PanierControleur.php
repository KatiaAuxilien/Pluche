<?php

namespace App\Pluche\Lib;

use App\Pluche\Configuration\Configuration;
use App\Pluche\Modele\HTTP\Cookie;

class PanierControleur
{
    private static string $clePreference = "panierControleur";

    public static function enregistrer() : void
    {
        $panier = array();
        Cookie::enregistrer(PanierControleur::$clePreference,$panier,Configuration::getTimeout());
    }

    public static function lire(): array
    {
        if(!PanierControleur::existe()){
            PanierControleur::enregistrer();
        }
        if(is_array(Cookie::lire(PanierControleur::$clePreference))){
            return Cookie::lire(PanierControleur::$clePreference);
        }
        return [];
    }

    public static function existe(): bool
    {
        return Cookie::contient(PanierControleur::$clePreference);
    }

    public static function supprimer(): void
    {
        Cookie::supprimer(PanierControleur::$clePreference);
    }

/*---- Gestion de produits dans le cookie panierControleur ----*/

    public static function nbProduits() : int
    {
        $panier=PanierControleur::lire();
        return count($panier);
    }

    public static function enregistrerProduit(string $id): void
    {
        $panier = PanierControleur::lire();
        $panier[$id] = $id;
        Cookie::enregistrer(PanierControleur::$clePreference, $panier);
    }

    public static function existeProduit($id): bool
    {
        $panier = PanierControleur::lire();
        foreach ($panier as $produit)
        {
            if($produit == $id)
            {
                return true;
            }
        }
        return false;
    }

    public static function supprimerProduit(int $id): void
    {
        if(PanierControleur::existeProduit($id))
        {
            $panier = PanierControleur::lire();
            unset($panier[$id]);
//essayer en définissant un nouveau tableau
            $panier2= [];
            foreach ($panier as $produit){
                if($produit!=$id){
                    $panier2[]=$produit;
                }
            }
            PanierControleur::supprimer();
            if(count($panier2)!=0){
                Cookie::enregistrer(PanierControleur::$clePreference, $panier2);
            }
        }
    }
}

