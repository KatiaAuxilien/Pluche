<?php

namespace App\Pluche\Modele\DataObjects;

use App\Pluche\Modele\ConnexionBaseDeDonnee;
use App\Pluche\Modele\DataObjects\AbstractDataObject;

class Produit extends AbstractDataObject {

    private int $id;
    private float $prix;
    private string $nom;
    private string $description;

    public function getId(): int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function setId(int $id) : void {
        $this->id = $id;
    }

    public function setPrix(float $prix): void
    {
        $this->prix = $prix;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }


    public function __construct(string $nom,float $prix,string $description, int $id =-1) //Quand id est à -1 c'est uniquement lors de la création d'un produit. A l'avenir dans la bdd il sera redéfini !!!
    {
        $this->setNom($nom);
        $this->setPrix($prix);
        $this->setDescription($description);
        $this->setId($id);
    }


    public function formatTableau(): array
    {
        return array(
            "nomTag" => $this->nom,
            "prixTag" => $this->prix,
            "descriptionTag" => $this->description,
            "idTag" => $this->id
        );
    }
}