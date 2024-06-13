<?php

namespace App\Pluche\Modele\HTTP;
use App\Pluche\Configuration\Configuration;

class Cookie
{

    public static function enregistrer(string $cle, mixed $valeur, ?int $dureeExpiration = null): void
    {
        $valeurSerialize = serialize($valeur);
        $expiration = 0;
        if ($dureeExpiration != null) {
            $expiration = time() + $dureeExpiration;
        }
        setcookie($cle, $valeurSerialize, $expiration);
    }

    public static function lire(string $cle): mixed
    {
        if(isset($_COOKIE[$cle]) && !empty($_COOKIE[$cle])){
            return unserialize($_COOKIE[$cle]);
        }
        return "";
    }

    public static function contient(string $cle): bool
    {
        return array_key_exists($cle, $_COOKIE);
    }

    public static function supprimer($cle): void
    {
        unset($_COOKIE[$cle]);
        setcookie($cle, "",1);
    }
}

