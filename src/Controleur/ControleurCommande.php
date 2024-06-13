<?php
namespace App\Pluche\Controleur;
use App\Pluche\Configuration\Configuration;
use App\Pluche\Lib\ConnexionUtilisateur;
use App\Pluche\Lib\MessageFlash;
use App\Pluche\Lib\PanierControleur;
use App\Pluche\Modele\Repository\CommandeRepository;
use App\Pluche\Modele\DataObjects\Commande;
use App\Pluche\Modele\Repository\ProduitRepository;
use App\Pluche\Modele\DataObjects\Produit;
use DateTime;


class ControleurCommande extends ControleurGenerique{

    const ETAT_EN_ATTENTE_PREPARATION = 'En attente de préparation';
    const ETAT_EN_PREPRATION = 'En préparation';
    const ETAT_LIVRAISON_EN_COURS = 'Livraison en cours';
    const ETAT_LIVREE = 'Livrée';
    const ETAT_ANNULEE = 'Annulée';
    const ETAT_ABANDONNEE = 'Abandonnée';
    const ETAT_EN_COURS_DE_RETOUR = 'En cours de retour';
    const ETAT_REMBOURSEE = 'Remboursée';



// --------- Affichages --------- //

    public static function afficherListe(): void
    {
        if (ConnexionUtilisateur::estConnecte())
        {
            ControleurCommande::afficherVue('vueGenerale.php', [
                "commandesEnParametre" => (new CommandeRepository())->recuperer(),
                "pagetitle" => "Historique des commandes.",
                "cheminVueBody" => "commande/liste.php"
            ]);
        }
        else
        {
            MessageFlash::ajouter("warning", "Connexion requise.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherFormulaireConnexion&controleur=utilisateur');
        }
    }

    public static function afficherDetail(): void
    {
        if (ConnexionUtilisateur::estConnecte())
        {
            if(empty($_REQUEST['id']))
            {
                MessageFlash::ajouter("danger", "Id de la commande manquant.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherListe&controleur=commande');
            }
            $commande = (new CommandeRepository())->recupererParClePrimaire($_REQUEST['id']);
            if ($commande == null)
            {
                MessageFlash::ajouter("danger", "Commande inexistante.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherListe&controleur=commande');
            }
            if (ConnexionUtilisateur::estAdministrateur() || ConnexionUtilisateur::estUtilisateur($commande->getAcheteurId()))
            {
                $produit = (new ProduitRepository())->recupererParClePrimaire($commande->getProduitId());
                ControleurCommande::afficherVue('vueGenerale.php', [
                    "commandeEnParametre" => $commande,
                    "produitEnParametre" => $produit,
                    "pagetitle" => "Commande n°{$commande->getId()}.",
                    "cheminVueBody" => "commande/detail.php"
                ]);
            }
            else
            {
                MessageFlash::ajouter("danger", "Vous n'avez pas les droits d'accès.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?');
            }
        }
        else
        {
            MessageFlash::ajouter("warning", "Connexion requise.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherFormulaireConnexion&controleur=utilisateur');
        }
    }

    public static function afficherFormulaireMiseAJour(): void
    {
        if(ConnexionUtilisateur::estConnecte())
        {
            if(empty($_REQUEST['id']))
            {
                MessageFlash::ajouter("danger", "Id de la commande manquant.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?');
            }
            $commande = (new CommandeRepository())->recupererParClePrimaire($_REQUEST['id']);
            if ($commande == null)
            {
                MessageFlash::ajouter("danger", "Commande inexistante.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?');
            }
            if (ConnexionUtilisateur::estAdministrateur())
            {
                ControleurCommande::afficherVue('vueGenerale.php', [
                    "commandeEnParametre" => $commande,
                    "pagetitle" => "Mise à jour de la commande n°{$commande->getId()}.",
                    "cheminVueBody" => "commande/formulaireMiseAJour.php"
                ]);
            }
            else
            {
                MessageFlash::ajouter("danger", "Vous n'avez pas les droits d'accès.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?');
            }
        }
        else
        {
            MessageFlash::ajouter("warning", "Connexion requise.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherFormulaireConnexion&controleur=utilisateur');
        }
    }

// --------- Fonctions --------- //

    public static function creerDepuisPanier(): void
    {
        if(ConnexionUtilisateur::estConnecte()){
            if(PanierControleur::existe() && PanierControleur::nbProduits() != 0){
                if(isset($_REQUEST['adresse'])) {
                    $acheteurId = ConnexionUtilisateur::getLoginUtilisateurConnecte();
                    $panier = PanierControleur::lire();
                    $commandeCount = 0;
                    $adresse = $_REQUEST['adresse'];
                    foreach ($panier as $produitId) {
                        $produit = (new ProduitRepository())->recupererParClePrimaire($produitId);
                        if ($produit == null) {
                            MessageFlash::ajouter("danger", "Produit inexistant.");
                            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherListe&controleur=produit');
                        }

                        $dateActuelle = new DateTime();
                        $dateCreation = $dateActuelle->format('Y-m-d');

                        $dateActuelle->add(new \DateInterval('P7D'));
                        $dateFinalisation = $dateActuelle->format('Y-m-d');

                        $prix = $produit->getPrix();

                        $commande = new Commande($dateCreation, $acheteurId, $produitId, $prix, self::ETAT_EN_ATTENTE_PREPARATION, $dateFinalisation, $adresse);
                        (new CommandeRepository())->sauvegarder($commande);
                        $commandeCount++;
                    }
                    PanierControleur::supprimer();
                    MessageFlash::ajouter("success", "{$commandeCount} commande(s) créée(s), livraison sous 7 jours.");
                    ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherListe&controleur=commande');
                }
                MessageFlash::ajouter("warning", "Adresse de livraison requise.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=panier');
            }
            MessageFlash::ajouter("warning", "Panier vide.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=panier');
        }
        else{
            MessageFlash::ajouter("warning", "Connexion requise.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherFormulaireConnexion&controleur=utilisateur');
        }
    }

    public static function supprimer(): void
    {
        if(ConnexionUtilisateur::estConnecte())
        {
            if (ConnexionUtilisateur::estAdministrateur())
            {
                if (!empty($_REQUEST['id']))
                {
                    $id = $_REQUEST['id'];
                    $commande= (new CommandeRepository())->supprimer($id);
                    if ($commande)
                    {
                        MessageFlash::ajouter("success", "Commande n°{$id} supprimée !");
                        ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherListe&controleur=commande');
                    }
                    else
                    {
                        MessageFlash::ajouter("danger", "Commande inexistante.");
                        ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=commande&action=afficherListe');
                    }
                }
                MessageFlash::ajouter("danger", "Id de la commande manquant.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=commande&action=afficherListe');
            }
            else
            {
                MessageFlash::ajouter("danger", "Vous n'avez pas les droits requis.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?');
            }
        }
        else
        {
            MessageFlash::ajouter("warning", "Connexion requise.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherFormulaireConnexion&controleur=utilisateur');
        }
    }

    public static function mettreAJour(): void
    {
        if(ConnexionUtilisateur::estConnecte())
        {
            if (ConnexionUtilisateur::estAdministrateur())
            {
                if ( !empty($_REQUEST['id']) && !empty($_REQUEST['etat']) && !empty($_REQUEST['dateFinalisation']))
                {
                    $id = $_REQUEST['id'];
                    $commande = (new CommandeRepository())->recupererParClePrimaire($id);

                    $etat = $_REQUEST['etat'] ?? $commande->getEtat();
                    $dateSaisie = date('Y-m-d');

                    if(in_array($etat,[
                        self::ETAT_EN_ATTENTE_PREPARATION,
                        self::ETAT_EN_PREPRATION,
                        self::ETAT_LIVRAISON_EN_COURS,
                        self::ETAT_LIVREE,
                        self::ETAT_ANNULEE,
                        self::ETAT_ABANDONNEE,
                        self::ETAT_REMBOURSEE,
                        self::ETAT_EN_COURS_DE_RETOUR
                    ]))
                    {
                        $commande->setEtat($etat);
                    }
                    else
                    {
                        MessageFlash::ajouter("warning", "Veuillez sélectionner un état valide.");
                        ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=commande&action=afficherFormulaireMiseAJour&id='.$id);
                    }
                    if($etat == self::ETAT_ANNULEE)
                    {
                        $dateSaisie = new DateTime();
                        $dateSaisie->add(new \DateInterval('P7D'));
                        $dateFinalisation = $dateSaisie->format('Y-m-d');
                        $commande->setDateReception($dateFinalisation);
                    }
                    if(!$commande->isTerminee())
                    {
                        $dateSaisie = $_REQUEST['dateFinalisation'] ?? $commande->getDateReception();
                        $dateActuelle = date('Y-m-d');

                        if($dateSaisie < $dateActuelle)
                        {
                            MessageFlash::ajouter("warning", "Veuillez sélectionner une date future.");
                            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=commande&action=afficherFormulaireMiseAJour&id='.$id);
                        }
                    }

                    $commande->setDateReception($dateSaisie);
                    (new CommandeRepository())->mettreAJour($commande);

                    MessageFlash::ajouter("success", "La commande n°{$id} a été mise à jour.");
                    $produit = (new ProduitRepository())->recupererParClePrimaire($commande->getProduitId());
                    ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherDetail&controleur=commande&id='.$id);
                }
            }
            else
            {
                MessageFlash::ajouter("danger", "Vous n'avez pas les droits d'accès.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?');
            }
        }
        else
        {
            MessageFlash::ajouter("warning", "Connexion requise.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherFormulaireConnexion&controleur=utilisateur');
        }
    }

    public static function abandonner() : void {
        if(ConnexionUtilisateur::estConnecte())
        {
            if(empty($_REQUEST['id']))
            {
                $id = $_REQUEST['id'];
                $commande = (new CommandeRepository())->recupererParClePrimaire($id);
                if($commande)
                {
                    $etat = $commande->getEtat();
                    if(ConnexionUtilisateur::estUtilisateur($commande->getAcheteurId()))
                    {
                        if($etat == 'En attente de préparation' || $etat == 'En préparation'){
                            $commande->setEtat(self::ETAT_ABANDONNEE);
                                $dateActuelle = new DateTime();
                                $dateActuelle->add(new \DateInterval('P7D'));
                                $dateFinalisation = $dateActuelle->format('Y-m-d');
                            $commande->setDateReception($dateFinalisation);
                            (new CommandeRepository())->mettreAJour($commande);

                            MessageFlash::ajouter("success", "Commande n°{$commande->getId()} abandonnée, le remboursement aura lieu sous 7 jours.");
                            $produit = (new ProduitRepository())->recupererParClePrimaire($commande->getProduitId());
                            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherDetail&controleur=commande&id='.$id);
                        }
                        else
                        {
                            MessageFlash::ajouter("warning", "Vous ne pouvez pas abandonner une commande d'état {$etat}.");
                            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherDetail&controleur=commande&id='.$id);
                        }
                    }
                    else
                    {
                        MessageFlash::ajouter("danger", "Vous n'avez pas les droits requis.");
                        ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherListe&controleur=commande');
                    }
                }
                else
                {
                    MessageFlash::ajouter("danger", "Commande inexistante.");
                    ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherListe&controleur=commande');
                }
            }
            else
            {
                MessageFlash::ajouter("danger", "Id de la commande manquant.");
                ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?controleur=commande&action=afficherListe');
            }
        }
        else
        {
            MessageFlash::ajouter("info", "Connexion requise.");
            ControleurUtilisateur::redirectionVersURL(Configuration::getURLAbsolue() . '?action=afficherFormulaireConnexion&controleur=utilisateur');
        }
    }

}