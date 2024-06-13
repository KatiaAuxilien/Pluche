<?php
namespace App\Pluche\Modele\Repository;
use App\Pluche\Modele\DataObjects\AbstractDataObject;
use App\Pluche\Modele\ConnexionBaseDeDonnee;

abstract class AbstractRepository
{
    protected abstract function getNomTable(): string;
    protected abstract function getNomClePrimaire(): string;
    protected abstract function getNomsColonnes(): array;
    protected abstract function isAutoIncrement(): bool;
    protected abstract function construireDepuisTableau(array $objetFormatTableau) : AbstractDataObject;

    public function recuperer(): array
    {
        $nom = $this->getNomTable();
        $sql = "SELECT * FROM {$nom}";
        $pdoStatement = ConnexionBaseDeDonnee::getPdo()->query($sql);

        $AbstractDataObject = [];
        foreach ($pdoStatement as $ObjectFormatTableau) {
            $AbstractDataObject[] = $this->construireDepuisTableau($ObjectFormatTableau);
        }
        return $AbstractDataObject;
    }

    public function recupererParClePrimaire(string $valeurClePrimaire): ?AbstractDataObject
    {
        $nomTable = $this->getNomTable();
        $nomClePrimaire = $this->getNomClePrimaire();
        $sql = "SELECT * from {$nomTable} WHERE {$nomClePrimaire} = :ClePrimaire";
        $pdoStatement = ConnexionBaseDeDonnee::getPdo()->prepare($sql);

        $values = array(
            "ClePrimaire" => $valeurClePrimaire
        );
        $pdoStatement->execute($values);
        $objectFormatTableau = $pdoStatement->fetch();
        if ($objectFormatTableau == 0) {
            return null;
        }
        return $this->construireDepuisTableau($objectFormatTableau);
    }

    public function supprimer(string $valeurClePrimaire) : bool {
        if( $this->recupererParClePrimaire($valeurClePrimaire) == null)
        {
            return false;
        }
        $nomClePrimaire = $this->getNomClePrimaire();
        $nomTable= $this->getNomTable();
        $sql = "DELETE FROM {$nomTable} WHERE {$nomClePrimaire}=:valeurClePrimaire";
        $pdoStatement = ConnexionBaseDeDonnee::getPdo()->prepare($sql);
        $values = array(
            "valeurClePrimaire" => $valeurClePrimaire,
        );
        $pdoStatement->execute($values);
        return true;
    }

    public function mettreAJour(AbstractDataObject $object): void
    {
        $arr = $this->getNomsColonnes();
        $sql = "UPDATE {$this->getNomTable()} SET ";

        $count = count($arr);
        if($this->isAutoIncrement()){
            $count = $count - 1;
        }

        for ($i = 0; $i < $count ; $i ++)
        {
            $sql = $sql . $arr[$i]."=:".$arr[$i]."Tag";
            if($i+1 < $count){
                $sql = $sql . ", ";
            }
        }

        $sql = $sql." WHERE {$this->getNomClePrimaire()} = :{$arr[count($arr)-1]}Tag";

        $pdoStatement = ConnexionBaseDeDonnee::getPdo()->prepare($sql);
        $values = $object->formatTableau();

        $pdoStatement->execute($values);
    }

    public function sauvegarder(AbstractDataObject $object): void
    {

        $arr = $this->getNomsColonnes();
        $sql = "INSERT INTO {$this->getNomTable()} (";
        $count = count($arr);
        if($this->isAutoIncrement()){
           $count = $count - 1;
        }

        for ($i = 0; $i < $count ; $i ++)
        {
            $sql = $sql . $arr[$i];
            if($i+1 < $count){
                $sql = $sql . ", ";
            }
        }

        $sql = $sql.") VALUES (";
        for ($i = 0; $i < $count ; $i ++)
        {
            $sql = $sql .":" . $arr[$i]."Tag";
            if($i+1 < $count){
                $sql = $sql . ", ";
            }
        }
        $sql = $sql.")";
        $pdoStatement = ConnexionBaseDeDonnee::getPdo()->prepare($sql);

        $values = $object->formatTableau();
        if($this->isAutoIncrement())
        {
            $pk = $this->getNomClePrimaire() ;
            $pk = $pk."Tag";
            unset($values[$pk]);
        }

        $pdoStatement->execute($values);
    }
}