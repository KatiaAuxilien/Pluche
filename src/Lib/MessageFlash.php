<?php
namespace App\Pluche\Lib;

use App\Pluche\Modele\HTTP\Session;

class MessageFlash
{

    // Les messages sont enregistrés en session associée à la clé suivante
    private static string $cleFlash = "_messagesFlash";

    // $type parmi "success", "info", "warning" ou "danger"
    public static function ajouter(string $type, string $message): void
    {
        $session = Session::getInstance();
        $messagesFlash = [];
        if ($session->contient(MessageFlash::$cleFlash)) {
            $messagesFlash = $session->lire(MessageFlash::$cleFlash);
        }
        $messagesFlash[$type][] = $message;
        $session->enregistrer(MessageFlash::$cleFlash, $messagesFlash);
    }

    public static function contientMessage(string $type): bool
    {
        $session = Session::getInstance();
        if ($session->contient(MessageFlash::$cleFlash) !== null) {
            $messageFlash = $session->lire(MessageFlash::$cleFlash);
            if ($messageFlash[$type] !== null){
                return true;
            }
        }
        return false;
    }

    public static function lireMessages(string $type): array
    {
        $session = Session::getInstance();
        if (MessageFlash::contientMessage($type)) {
            $messageFlash = $session->lire(MessageFlash::$cleFlash);
            $messagesFlash[$type] = [];
            $session->enregistrer(MessageFlash::$cleFlash, $messagesFlash);
            return $messageFlash[$type];
        }
        return [];
    }

    public static function lireTousMessages() : array
    {
        $session = Session::getInstance();
        if ($session->contient(MessageFlash::$cleFlash)) {
            $list = $session->lire(MessageFlash::$cleFlash);
            $session->supprimer(MessageFlash::$cleFlash);
            return $list;
        }
        return [];
    }

}

