<?php

namespace App\Pluche\Lib;

use App\Pluche\Modele\DataObjects\Utilisateur;
use App\Pluche\Modele\HTTP\Session;
use App\Pluche\Modele\Repository\UtilisateurRepository;

class ConnexionUtilisateur
{
    private static string $cleConnexion = "_utilisateurConnecte";

    public static function connecter(string $loginUtilisateur): void
    {
        $session = Session::getInstance();
        $session->enregistrer(self::$cleConnexion, $loginUtilisateur);
    }

    public static function estConnecte(): bool
    {
        $session = Session::getInstance();
        return $session->contient(self::$cleConnexion);
    }

    public static function deconnecter(): void
    {
        $session = Session::getInstance();
        if (self::estConnecte()) {
            $session->supprimer(self::$cleConnexion);
        }
        $session = Session::getInstance();
        $session->detruire();
    }

    public static function getLoginUtilisateurConnecte(): ?string
    {
        $session = Session::getInstance();
        if ($session->contient(self::$cleConnexion)) {
            return $session->lire(self::$cleConnexion);
        } else {
            return null;
        }
    }

    public static function estUtilisateur($login): bool
    {
        if (self::getLoginUtilisateurConnecte() == $login) {
            return true;
        } else {
            return false;
        }
    }

    public static function estAdministrateur(): bool
    {
        $login = self::getLoginUtilisateurConnecte();
        if ($login != null) {
            $Utilisateur = (new UtilisateurRepository())->recupererParClePrimaire($login);
            return $Utilisateur->getEstAdmin();
        } else {
            return false;
        }
    }

    public static function ajouterProduitPanier($idProduit, $nomProduit)
    {
        $session = Session::getInstance();
        $session->enregistrer($idProduit, $nomProduit);
    }

    public static function supprimerProduitPanier($idProduit)
    {
        $session = Session::getInstance();
        $session->supprimer($idProduit);
    }
}
