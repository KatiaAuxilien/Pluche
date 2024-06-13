<?php

namespace App\Pluche\Modele\DataObjects;
use App\Pluche\Lib\MotDePasse;


class Utilisateur extends AbstractDataObject
{
    private string $login,$nom,$prenom,$email,$password,$emailAValider,$nonce;
    private int $estAdmin;

    public function __construct(string $login,string $nom, string $prenom,string $password,int $estAdmin,string $email, string $emailAValider,string $nonce){
        $this->login = $login;
        $this->nom=$nom;
        $this->prenom=$prenom;
        $this->password = $password;
        $this->estAdmin = $estAdmin;
        $this->email = $email;
        $this->emailAValider = $emailAValider;
        $this->nonce = $nonce;
    }

    public function formatTableau(): array
    {
        return array(
            "loginTag" => $this->login,
            "nomTag"=>$this->nom,
            "prenomTag"=>$this->prenom,
            "emailTag" => $this->email,
            "passwordTag" => $this->password,
            "estAdminTag" => $this->estAdmin,
            "emailAValiderTag" => $this->emailAValider,
            "nonceTag" => $this->nonce,
        );
    }

    public static function construireDepuisFormulaire (array $tableauFormulaire) : ?Utilisateur {
        return new Utilisateur($tableauFormulaire['login'],
            $tableauFormulaire['nom'],
            $tableauFormulaire['prenom'],
            MotDePasse::hacher($tableauFormulaire['password']),
            $tableauFormulaire['estAdmin'],
            "",
            $tableauFormulaire['email'],
            MotDePasse::genererChaineAleatoire());
    }


    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getEstAdmin(): int
    {
        return $this->estAdmin;
    }

    public function setEstAdmin(int $estAdmin): void
    {
        $this->estAdmin = $estAdmin;
    }
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmailAValider(): string
    {
        return $this->emailAValider;
    }

    public function setEmailAValider(string $emailAValider): void
    {
        $this->emailAValider = $emailAValider;
    }

    public function getNonce(): string
    {
        return $this->nonce;
    }

    public function setNonce(string $nonce): void
    {
        $this->nonce = $nonce;
    }


}