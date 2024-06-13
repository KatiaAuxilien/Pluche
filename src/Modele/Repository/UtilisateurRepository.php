<?php

namespace App\Pluche\Modele\Repository;

use App\Pluche\Modele\DataObjects\AbstractDataObject;
use App\Pluche\Modele\DataObjects\Utilisateur;

class UtilisateurRepository extends AbstractRepository
{

    protected function getNomTable(): string
    {
        return "p_utilisateur";
    }

    protected function getNomClePrimaire(): string
    {
        return "login";
    }

    protected function getNomsColonnes(): array
    {
        return ["nom","prenom","password","estAdmin","email","emailAValider","nonce","login"];
    }

    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new Utilisateur(
            $objetFormatTableau["login"],
            $objetFormatTableau["nom"],
            $objetFormatTableau["prenom"],
            $objetFormatTableau["password"],
            $objetFormatTableau["estAdmin"],
            $objetFormatTableau["email"],
            $objetFormatTableau["emailAValider"],
            $objetFormatTableau["nonce"]);
    }

    protected function isAutoIncrement(): bool
    {
        return false;
    }
}