<?php
namespace App\Pluche\Modele;
use App\Pluche\Configuration\Configuration as Configuration;
use PDO;

class ConnexionBaseDeDonnee{
    private static ?ConnexionBaseDeDonnee $instance = null;
    private PDO $pdo;

    public static function getPdo(): PDO {
        return ConnexionBaseDeDonnee::getInstance()->pdo;
    }

    public function __construct()
    {
        $hostname = Configuration::getHostname();
        $port = Configuration::getPort();
        $databaseName = Configuration::getDatabase();
        $login = Configuration::getLogin();
        $password = Configuration::getPassword();
        $this->pdo = new PDO("mysql:host=$hostname;port=$port;dbname=$databaseName", $login, $password,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    }

    private static function getInstance() : ConnexionBaseDeDonnee {
        if (is_null(ConnexionBaseDeDonnee::$instance))
            ConnexionBaseDeDonnee::$instance = new ConnexionBaseDeDonnee();
        return ConnexionBaseDeDonnee::$instance;
    }
}
