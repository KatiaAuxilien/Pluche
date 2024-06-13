<?php

namespace App\Pluche\Lib;

class MotDePasse
{
    private static string $poivre = "kjGV9vZTQcdcbvl3pEEumV";

    public static function hacher(string $mdpClair): string
    {
        $mdpPoivre = hash_hmac("sha256", $mdpClair, MotDePasse::$poivre);
        $mdpHache = password_hash($mdpPoivre, PASSWORD_DEFAULT);
        return $mdpHache;
    }

    public static function verifier(string $mdpClair, string $mdpHache): bool
    {
        $mdpPoivre = hash_hmac("sha256", $mdpClair, MotDePasse::$poivre);
        return password_verify($mdpPoivre, $mdpHache);
    }

    public static function genererChaineAleatoire(int $nbCaracteres = 22): string
    {
        $octetsAleatoires = random_bytes(ceil($nbCaracteres * 6 / 8));
        return substr(base64_encode($octetsAleatoires), 0, $nbCaracteres);
    }

    public static function verifierSolidite(string $mdp) : string {
        $length = strlen($mdp);
        if($length < 8)
        {
            return "Le mot de passe est trop court.";
        }
        if($length > 32)
        {
            return "Le mot de passe est trop long.";
        }

        for($i=0;$i <$length;$i++)
        {
            if(!preg_match("#[0-9]+#",$mdp) || !preg_match("#[a-zA-Z]+#",$mdp) || !preg_match("#[^a-zA-Z0-9]+#",$mdp)) //Utilisation d'expressions régulières.
            {
                return "Le mot de passe doit contenir au moins un chiffre, une lettre majuscule, une lettre minuscule et un caractère spécial.";
            }

            $motsDePasseCourants = array('password','123456','azerty','qwerty');
            if(in_array($mdp,$motsDePasseCourants)){
                return "Ce mot de passe est trop courant. Veuillez en choisir un autre.";
            }

        }
        return true;
    }
}