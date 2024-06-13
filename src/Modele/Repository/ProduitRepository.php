<?php
namespace App\Pluche\Modele\Repository;

use App\Pluche\Modele\ConnexionBaseDeDonnee;
use App\Pluche\Modele\DataObjects\AbstractDataObject;
use App\Pluche\Modele\DataObjects\Produit;
use App\Pluche\Modele\Repository\AbstractRepository;


class ProduitRepository extends AbstractRepository {

    protected function getNomTable(): string
    {
        return "p_produit";
    }

    protected function getNomClePrimaire(): string
    {
        return "id";
    }

    protected function isAutoIncrement(): bool
    {
        return true;
    }

    protected function getNomsColonnes(): array
    {
        return ["nom","prix","description","id"];
    }

    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        $peluche = new Produit($objetFormatTableau['nom'],$objetFormatTableau['prix'],$objetFormatTableau['description'],$objetFormatTableau['id']);
        return $peluche;
    }
}