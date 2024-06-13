<?php
namespace App\Pluche\Modele\Repository;

use App\Pluche\Modele\ConnexionBaseDeDonnee;
use App\Pluche\Modele\DataObjects\AbstractDataObject;
use App\Pluche\Modele\DataObjects\Commande;
use App\Pluche\Modele\Repository\AbstractRepository;

class CommandeRepository extends AbstractRepository
{

    protected function getNomTable(): string
    {
        return "p_commande";
    }

    protected function getNomClePrimaire(): string
    {
        return "id";
    }

    protected function getNomsColonnes(): array
    {
        return ['date','acheteurId','produitId','prix','etat','dateFinalisation','adresseLivraison','id'];
    }

    protected function isAutoIncrement(): bool
    {
        return true;
    }

    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new Commande($objetFormatTableau['date'],
            $objetFormatTableau['acheteurId'],
            $objetFormatTableau['produitId'],
            $objetFormatTableau['prix'],
            $objetFormatTableau['etat'],
            $objetFormatTableau['dateFinalisation'],
            $objetFormatTableau['adresseLivraison'],
            $objetFormatTableau['id']);
    }
}