<?php

namespace App\Pluche\Modele\DataObjects;

class Commande extends AbstractDataObject
{
    private int $id;
    private string $date; //format YYYY-MM-DD
    private string $acheteurId;
    private int $produitId;
    private float $prix;
    private string $etat;
    private string $adresseLivraison;

    //Valeurs de l'etat de la commande :
        const ETAT_EN_ATTENTE_PREPARATION = 'En attente de préparation';
        const ETAT_EN_PREPRATION = 'En préparation';
        const ETAT_LIVRAISON_EN_COURS = 'Livraison en cours';
        const ETAT_LIVREE = 'Livrée';
        const ETAT_ANNULEE = 'Annulée';
        const ETAT_ABANDONNEE = 'Abandonnée';
        const ETAT_EN_COURS_DE_RETOUR = 'En cours de retour';
        const ETAT_REMBOURSEE = 'Remboursée';
    private string $dateFinalisation; //format YYYY-MM-DD

    private bool $terminee = false;

    public function isTerminee(): bool
    {
        return $this->terminee;
    }

    public function setTerminee(bool $terminee): void
    {
        $this->terminee = $terminee;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    public function getAcheteurId(): string
    {
        return $this->acheteurId;
    }

    public function setAcheteurId(string $acheteurId): void
    {
        $this->acheteurId = $acheteurId;
    }

    public function getProduitId(): int
    {
        return $this->produitId;
    }

    public function setProduitId(int $produitId): void
    {
        $this->produitId = $produitId;
    }

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): void
    {
        $this->prix = $prix;
    }

    public function getAdresseLivraison(): string
    {
        return $this->adresseLivraison;
    }

    public function setAdresseLivraison(string $adresseLivraison): void
    {
        $this->adresseLivraison = $adresseLivraison;
    }

    public function getEtat(): string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): void
    {
        if(in_array($etat,[
            self::ETAT_EN_ATTENTE_PREPARATION,
            self::ETAT_EN_PREPRATION,
            self::ETAT_LIVRAISON_EN_COURS,
            self::ETAT_LIVREE,
            self::ETAT_ANNULEE,
            self::ETAT_ABANDONNEE,
            self::ETAT_REMBOURSEE,
            self::ETAT_EN_COURS_DE_RETOUR
        ])) {
            $this->etat = $etat;
            if($etat == self::ETAT_LIVREE || $etat == self::ETAT_REMBOURSEE){
                $this->setTerminee(true);
            }
        }
    }

    public function getDateReception(): string
    {
        return $this->dateFinalisation;
    }

    //format YYYY-MM-DD
    public function setDateReception(string $dateFinalisation): void
    {
        $this->dateFinalisation = $dateFinalisation;
    }

    public function __construct(string $date,string $acheteurId, int $produitId,float $prix,string $etat,string $dateFinalisation,string $adresseLivraison ,int $id=-1) //Quand id est à -1 c'est uniquement lors de la création d'une commande. A l'avenir dans la bdd il sera redéfini !!!
    {
        $this->id=$id;
        $this->date = $date;
        $this->acheteurId=$acheteurId;
        $this->produitId=$produitId;
        $this->prix=$prix;
        $this->etat = $etat;
        $this->dateFinalisation = $dateFinalisation;
        $this->adresseLivraison = $adresseLivraison;
    }
    public function formatTableau(): array
    {
        return array(
            "idTag" => $this->id,
            "dateTag"=>$this->date,
            "acheteurIdTag"=>$this->acheteurId,
            "produitIdTag"=>$this->produitId,
            "prixTag"=>$this->prix,
            "etatTag"=>$this->etat,
            "dateFinalisationTag"=>$this->dateFinalisation,
            "adresseLivraisonTag"=>$this->adresseLivraison
        );
    }

}