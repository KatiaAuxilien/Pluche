<?php
namespace App\Pluche\Configuration;

class Configuration {

    static private array $databaseConfiguration = array(
        'hostname' => 'webinfo.iutmontp.univ-montp2.fr',
        'database' => 'coulonm',
        'port' => '3316',
        'login' => 'coulonm',
        'password' => 'jAaF9SsnX2HL'
    );

    static public function getLogin() : string {
        return Configuration::$databaseConfiguration['login'];
    }

    static public function getHostname() : string {
        return Configuration::$databaseConfiguration['hostname'];
    }
    static public function getPort() : string {
        return Configuration::$databaseConfiguration['port'];
    }
    static public function getDatabase() : string {
        return Configuration::$databaseConfiguration['database'];
    }
    static public function getPassword() : string {
        return Configuration::$databaseConfiguration['password'];
    }


    static private int $dureeExpiration = 300; //En secondes.
    static public function getTimeout(): int {
        return Configuration::$dureeExpiration;
    }

    static public function getURLAbsolue() : string
    {
        return "https://webinfo.iutmontp.univ-montp2.fr/~auxilienk/projetdevweb/web/controleurFrontal.php";
    }

    static public function getDebug() : bool
    {
        return true;
    }


}

