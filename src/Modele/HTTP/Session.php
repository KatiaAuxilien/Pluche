<?php

namespace App\Pluche\Modele\HTTP;

use App\Pluche\Configuration\Configuration;
use Exception;

class Session
{
    private static ?Session $instance = null;

    /**
     * @throws Exception
     */
    private function __construct()
    {
        if (session_start() === false) {
            throw new Exception("La session n'a pas réussi à démarrer.");
        }
    }

    public static function verifierDerniereActivite(): void
    {
        $dureeExpiration = Configuration::getTimeout();
        if (isset($_SESSION['derniereActivite']) && (time() - $_SESSION['derniereActivite'] > ($dureeExpiration))) {
            session_unset();
        }
        $_SESSION['derniereActivite'] = time();
    }

    public static function getInstance(): Session
    {
        if (is_null(Session::$instance))
            Session::$instance = new Session();
        Session::verifierDerniereActivite();
        return Session::$instance;
    }

    public function contient($nom): bool
    {
        if (array_key_exists($nom, $_SESSION)) {
            return true;
        } else {
            return false;
        }
    }

    public function enregistrer(string $nom, mixed $valeur): void
    {
        $_SESSION[$nom] = $valeur;
    }

    public function lire(string $nom): mixed
    {
        if ($this->contient($nom)) {
            return $_SESSION[$nom];
        } else {
            return false;
        }
    }

    public function supprimer($nom): void
    {
        if ($this->contient($nom)) {
            unset($_SESSION[$nom]);
        }
    }

    public function detruire(): void
    {
        session_unset();
        session_destroy();
        Cookie::supprimer(session_name());
        $instance = null;
    }
}
