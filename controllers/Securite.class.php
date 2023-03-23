<?php


class Securite
{
    public const COOKIE_NAME = "timer";
    // ce cookie sert à sécuriser un peu plus la connexion contre un hack.
    public static function secureHTML($chaine)
    {
        return htmlentities($chaine);
    }
    public static function estConnecte()
    {
        return (!empty($_SESSION['profil']));
    }
    public static function estUtilisateur()
    {
        return (!empty($_SESSION['profil']['role'] === "utilisateur"));
    }
    public static function estAdministrateur()
    {
        return (!empty($_SESSION['profil']['role'] === "administrateur"));
    }
    public static function genererCookieConnexion()   // qd validation connexion
    {
        // ticket => valeur du cookie, on le veut complexe
        $ticket = session_id() . microtime() . rand(0, 999999);
        $ticket = hash("sha512", $ticket);

        //  nom, du cookie, sa valeur, son temps d'expiration(ici instant t + 60sec x 20 minutes)
        setcookie(self::COOKIE_NAME, $ticket, time() + (60*20));

        // on veut également conserver le cookie sur la partie serveur en le stockant dans la session de l'utilisateur
        $_SESSION['profil'][self::COOKIE_NAME] = $ticket;

        // il y a comparaison entre les 2 cookies pour voir si l'utilisateur a le droit d'être connecté sur ses pages réservées...

    }

    public static function checkCookieConnexion()
    {
        // il y a comparaison entre les 2 cookies pour voir si l'utilisateur a le droit d'être connecté sur ses pages réservées.
        return $_COOKIE[self::COOKIE_NAME] === $_SESSION['profil'][self::COOKIE_NAME];
    }
}
