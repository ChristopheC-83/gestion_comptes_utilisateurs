<!-- cette page est le centre de notre structure mvc -->
<!-- pour accéder à une page, il faudrait ecrire en url -->
<!--  index.php?page=accueil -->
<!-- on améliore avec htaccess et une modif -->

<?php

session_start();

define("URL", str_replace("index.php", "", (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER["PHP_SELF"]));

require_once("./controllers/Securite.class.php");
require_once("./controllers/Toolbox.class.php");
require_once("./controllers/Visiteur/Visiteur.controller.php");
require_once("./controllers/Utilisateur/Utilisateur.controller.php");
require_once("./controllers/Administrateur/Administrateur.controller.php");
$visiteurController = new VisiteurController();
$utilisateurController = new UtilisateurController();
$administrateurController = new AdministrateurController();

try {

    if (empty($_GET['page'])) {
        $page  = "accueil";
    } else {
        $url = explode("/", $_GET['page'], FILTER_SANITIZE_URL);
        $page = $url[0];
    }

    switch ($page) {

            // Pour Tous les visiteurs
        case "accueil":
            $visiteurController->accueil();
            break;
        case "page1":
            $visiteurController->page1();
            break;
        case "page2":
            $visiteurController->page2();
            break;
        case "login":
            $visiteurController->login();
            break;
        case "validation_login":
            if (empty($_POST['login']) && empty($_POST['password'])) {
                Toolbox::ajouterMessageAlerte("Login et Password non renseignés", Toolbox::COULEUR_ROUGE);
                header('Location:' . URL . 'login');
            } else if (empty($_POST['login'])) {
                Toolbox::ajouterMessageAlerte("Login non renseigné", Toolbox::COULEUR_ROUGE);
                header('Location:' . URL . 'login');
            } else if (empty($_POST['password'])) {
                Toolbox::ajouterMessageAlerte("Password non renseigné", Toolbox::COULEUR_ROUGE);
                header('Location:' . URL . 'login');
            } else {
                $login = Securite::secureHTML($_POST['login']);
                $password = Securite::secureHTML($_POST['password']);
                // on est déjà utilisateur et non plus visiteur !
                $utilisateurController->validation_login($login, $password);
            }
            break;

        case "creerCompte":
            $visiteurController->creerCompte();
            break;
        case "validation_creerCompte":
            if (!empty($_POST['login']) && !empty($_POST['password']) && !empty($_POST['mail'])) {
                $login = Securite::secureHTML($_POST['login']);
                $password = Securite::secureHTML($_POST['password']);
                $mail = Securite::secureHTML($_POST['mail']);
                $utilisateurController->validation_creerCompte($login, $password, $mail);
            } else {
                Toolbox::ajouterMessageAlerte("Les 3 informations sont obligatoires", Toolbox::COULEUR_ROUGE);
                header('Location:' . URL . 'creerCompte');
            }
            break;

        case "renvoyerMailValidation":
            $utilisateurController->renvoyerMailValidation($url[1]);
            break;
        case "validationMail":
            $utilisateurController->validation_mailCompte($url[1], $url[2]);
            break;

            // Pour les comptes utilisateurs enregistrés

        case "compte":
            if (!Securite::estConnecte()) {
                Toolbox::ajouterMessageAlerte("Veuillez vous connecter.", Toolbox::COULEUR_ROUGE);
                header('Location:' . URL . 'login');
            } elseif (!Securite::checkCookieConnexion()) {
                Toolbox::ajouterMessageAlerte("Session expirée, veuillez vous reconnecter.", Toolbox::COULEUR_ROUGE);
                setcookie(Securite::COOKIE_NAME, "", time() - 3600);
                unset($_SESSION['profil']);
                header('Location:' . URL . 'login');
            } else {
                //on regénère le cookie de connexion pour que l'utilisateur ne se délogge pas toutes les 20 minutes.
                Securite::genererCookieConnexion();
                switch ($url[1]) {
                    case "profil":
                        $utilisateurController->profil();
                        break;
                    case "deconnexion":
                        $utilisateurController->deconnexion();
                        break;
                    case "validation_modificationMail":
                        $utilisateurController->validation_modificationMail(Securite::secureHTML($_POST['mail']));
                        break;
                    case "modificationPassword":
                        $utilisateurController->modificationPassword();
                        break;
                    case "validation_modificationPassword":
                        if (!empty($_POST['ancienPassword']) && !empty($_POST['nouveauPassword']) && !empty($_POST['confirmNouveauPassword'])) {
                            $ancienPassword = Securite::secureHTML($_POST['ancienPassword']);
                            $nouveauPassword = Securite::secureHTML($_POST['nouveauPassword']);
                            $confirmNouveauPassword = Securite::secureHTML($_POST['confirmNouveauPassword']);
                            $utilisateurController->validation_modificationPassword($ancienPassword, $nouveauPassword, $confirmNouveauPassword);
                        } else {
                            Toolbox::ajouterMessageAlerte("Vous n'avez pas renseigné toutes les informations requises !", Toolbox::COULEUR_ROUGE);
                            header('Location:' . URL . 'compte/modificationPassword');
                        }
                        break;
                    case "suppressionCompte":
                        $utilisateurController->validation_suppressionCompte();
                        break;
                    case "validation_modificationImage":
                        if ($_FILES['image']['size'] > 0) {
                            $utilisateurController->validation_modificationImage($_FILES['image']);
                        } else {
                            Toolbox::ajouterMessageAlerte("Vous n'avez pas modifié l'image.", Toolbox::COULEUR_ROUGE);
                            header('Location:' . URL . 'compte/profil');
                        }
                        // $utilisateurController->validation_suppressionCompte();
                        break;
                    default:
                        throw new Exception("La page n'existe pas !");
                }
            }
            break;

            // Pour les comptes administration

        case "administration":
            if (!Securite::estConnecte()) {
                Toolbox::ajouterMessageAlerte("Veuillez vous connecter.", Toolbox::COULEUR_ROUGE);
                header('Location:' . URL . 'login');
            } else if (!Securite::estAdministrateur()) {
                Toolbox::ajouterMessageAlerte("Vous n'avez pas le droit d'être ici.", Toolbox::COULEUR_ROUGE);
                header('Location:' . URL . 'accueil');
            } else {
                switch ($url[1]) {
                    case "droits":
                        $administrateurController->droits();
                        break;
                    case "validation_modificationValidation":
                        $administrateurController->validation_modificationValidation($_POST['login'], $_POST['est_valide']);
                        break;
                    case "validation_modificationRole":
                        $administrateurController->validation_modificationRole($_POST['login'], $_POST['role']);
                        break;
                    default:
                        throw new Exception("La page n'existe pas !");
                }
            }
            break;

        default:
            throw new Exception("Ce n'est peut être pas une erreur 404, mais tu sembles perdu là ! 😆");
    }
} catch (Exception $e) {

    $visiteurController->pageErreur($e->getMessage());
}
