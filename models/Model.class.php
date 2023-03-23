<?php

abstract class Model
{
    // abstract : la classe n'est jamais utilisée telle quelle
    // elle sert de base à la fabrication d'une autre classe par héritage

    // on crée des variables, plus facile à modifier au besoin

    private static $dbhost = "localhost";
    private static $dbname = "gestion_comptes";
    private static $dbUser = "root";
    private static $dbUserPassword = "";
    private static $pdo;
    

    private static function setBdd()
    {
        self::$pdo = new PDO(
            "mysql:host=" . self::$dbhost . ";
            dbname=" . self::$dbname,
            self::$dbUser,
            self::$dbUserPassword
        );
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    protected function getBdd()
    {
        if (self::$pdo === null) {
            self::setBdd();
        }
        return self::$pdo;
    }
}
