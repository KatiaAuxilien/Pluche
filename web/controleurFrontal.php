<?php
require_once __DIR__ . '/../src/Lib/Psr4AutoloaderClass.php';

$loader = new App\Pluche\Lib\Psr4AutoloaderClass();
$loader->register();

$loader->addNamespace('App\Pluche', __DIR__ . '/../src');
use App\Pluche\Controleur\ControleurProduit;

$action ="";
$controleur="";
$nomDeClasseControleur="";
$controleurs = array("produit","utilisateur","commande"); //Ajouter les controleurs au fur et à mesure.
    if(isset($_REQUEST['controleur']) && in_array($_REQUEST['controleur'],$controleurs,true)
)
    {
        $controleur = $_REQUEST['controleur'];
    }
    else
    {
        $controleur="produit";
    }

    $nomDeClasseControleur= "App\Pluche\Controleur\Controleur".ucfirst($controleur);
    $exist = class_exists($nomDeClasseControleur);

    if(!$exist)
    {
        $action = "afficherErreur";
    }
    else
    {

        if(isset($_REQUEST['action']))
        {
            $class = get_class_methods($nomDeClasseControleur);


            if(in_array($_REQUEST['action'],$class,true))
            {
                $action = $_REQUEST['action'];
            }
            else
            {
                $action = "afficherErreur";
            }
        }
        else
        {
            $action = "afficherListe";
        }
    }

$nomDeClasseControleur::$action();
?>