<?php

require_once("./controllers/MainController.controller.php");
require_once("./models/Utilisateur/Utilisateur.model.php");

class UtilisateurController extends MainController
{

    private $utilisateurManager;

    public function __construct()
    {
        $this->utilisateurManager = new UtilisateurManager();
    }

    public function validation_login($login, $password)
    {
        if ($this->utilisateurManager->isCombinaisonValide($login, $password)) {
            if ($this->utilisateurManager->estCompteActive($login)) {
                Toolbox::ajouterMessageAlerte("Bon retour sur le site " . $login . " !", Toolbox::COULEUR_VERTE);
                $_SESSION['profil'] = [
                    "login" => $login
                ];

                // generation d'un cookie de sécurité
                Securite::genererCookieConnexion();

                header("Location:" . URL . "compte/profil");
            } else {
                $msg = "Le compte " . $login . " n'a pas été activé. Regardez votre boite mail ou ";
                $msg .= "<a href='renvoyerMailValidation/" . $login . "'>&nbsp Redemander un mail de validation</a>";;
                Toolbox::ajouterMessageAlerte($msg, Toolbox::COULEUR_ROUGE);
                // renvoyer mail activation
                header("Location:" . URL . "login");
            }
        } else {
            Toolbox::ajouterMessageAlerte("Combinaison Login / Password non valide", Toolbox::COULEUR_ROUGE);
            header("Location:" . URL . "login");
        }
    }


    public function pageErreur($msg)
    {
        // ici, pas besoin d'un affichage spécifique, nous reprenons l'affichage de la classe parent.
        parent::pageErreur($msg);
    }
    public function deconnexion()
    {
        Toolbox::ajouterMessageAlerte("Déconnexion effectuée.", Toolbox::COULEUR_VERTE);
        unset($_SESSION['profil']);
        setcookie(Securite::COOKIE_NAME, "", time() - 3600); //expiration forcée du cookie
        header("Location:" . URL . "accueil");
    }
    public function profil()
    {
        $datas = $this->utilisateurManager->getUserInformation($_SESSION['profil']['login']);
        $_SESSION['profil']['role'] = $datas['role'];


        $data_page = [
            "page_title" => "Page de profil",
            "page_description" => "Description de la page de profil",
            "view" => "views/Utilisateur/profil.view.php",
            "template" => "views/commons/template.php",
            "utilisateur" => $datas,
            "css" => "profil",
            "js" => ['profil.js', 'app.js'],
        ];

        $this->genererPage($data_page);
    }


    public function validation_creerCompte($login, $password, $mail)
    {
        if ($this->utilisateurManager->verifLoginDispo($login)) {
            $passwordCrypte = password_hash($password, PASSWORD_DEFAULT);
            $clef = rand(0, 9999);
            if ($this->utilisateurManager->bdCreerCompte($login, $passwordCrypte, $mail, $clef, "profils/profil.png", "utilisateur")) {
                $this->sendMailValidation($login, $mail, $clef);
                Toolbox::ajouterMessageAlerte("Compte créé avec succés. Vous avez reçu un mail pour le valider.", Toolbox::COULEUR_VERTE);
                header('Location:' . URL . 'login');
            } else {
                Toolbox::ajouterMessageAlerte("Erreur lors de la création du compte. Veuillez réessayer !", Toolbox::COULEUR_ROUGE);
                header('Location:' . URL . 'creerCompte');
            }
        } else {
            Toolbox::ajouterMessageAlerte("Login déjà utilisé !", Toolbox::COULEUR_ROUGE);
            header('Location:' . URL . 'creerCompte');
        }
    }

    private function sendMailValidation($login, $mail, $clef)
    {
        $urlVerification = URL . "validationMail/" . $login . "/" . $clef;
        $sujet = "Validation création compte sur le site MonSite.fr";
        $message = "Pour valider votre compte, veuillez cliquer sur le lien suivant : " . $urlVerification;
        Toolbox::sendMail($mail, $sujet, $message);
    }
    public function validation_modificationMail($mail)
    {
        if ($this->utilisateurManager->bdModificationMailUser($_SESSION['profil']['login'], $mail)) {
            Toolbox::ajouterMessageAlerte("La modification est effectuée", Toolbox::COULEUR_VERTE);
        } else {
            Toolbox::ajouterMessageAlerte("Aucune modification effectuée.", Toolbox::COULEUR_ROUGE);
        }
        header('Location:' . URL . 'compte/profil');
    }

    public function renvoyerMailValidation($login)
    {
        $utilisateur = $this->utilisateurManager->getUserInformation($login);
        $this->sendMailValidation($login, $utilisateur['mail'], $utilisateur['clef']);
        header('Location:' . URL . 'login');
    }

    public function validation_mailCompte($login, $clef)
    {

        if ($this->utilisateurManager->estCompteActive($login)) {
            Toolbox::ajouterMessageAlerte("Le compte a déjà été activé.", Toolbox::COULEUR_ORANGE);
            $_SESSION['profil'] = [
                "login" => $login
            ];
            header("Location:" . URL . "compte/profil");
        } else {
            if ($this->utilisateurManager->bdValidationMailCompte($login, $clef)) {
                Toolbox::ajouterMessageAlerte("Activation de votre compte réussie!", Toolbox::COULEUR_VERTE);
                $_SESSION['profil'] = [
                    "login" => $login
                ];
                header("Location:" . URL . "compte/profil");
            } else {
                Toolbox::ajouterMessageAlerte("Activation échouée", Toolbox::COULEUR_ROUGE);
                header('Location:' . URL . 'login');
            }
        }
    }

    public function modificationPassword()
    {
        $data_page = [
            "page_title" => "Page de modification du password",
            "page_description" => "Description de la page de modification du password",
            "view" => "views/Utilisateur/modificationPassword.view.php",
            "template" => "views/commons/template.php",
            "css" => "profil",
            "js" => ['app.js', 'modifMDP.js'],
        ];

        $this->genererPage($data_page);
    }

    public function validation_modificationPassword($ancienPassword, $nouveauPassword, $confirmNouveauPassword)
    {
        if ($nouveauPassword === $confirmNouveauPassword) {
            if ($this->utilisateurManager->isCombinaisonValide($_SESSION['profil']['login'], $ancienPassword)) {
                $passwordCrypte = password_hash($nouveauPassword, PASSWORD_DEFAULT);
                if ($this->utilisateurManager->bdModificationPassword($_SESSION['profil']['login'], $passwordCrypte)) {
                    Toolbox::ajouterMessageAlerte("Modification du password effectuée. Ne l'oubliez pas ! 😜", Toolbox::COULEUR_VERTE);
                    header('Location:' . URL . 'compte/profil');
                } else {
                    Toolbox::ajouterMessageAlerte("LA modification du password a échouée... 😥", Toolbox::COULEUR_ROUGE);
                    header('Location:' . URL . 'compte/modificationPassword');
                }
            } else {
                Toolbox::ajouterMessageAlerte("Combinaison Login /Ancien password invalide.❌", Toolbox::COULEUR_ROUGE);
                header('Location:' . URL . 'compte/modificationPassword');
            }
        } else {
            Toolbox::ajouterMessageAlerte("Votre nouveau mot de passe doit être parfaitement recopié.", Toolbox::COULEUR_ROUGE);
            header('Location:' . URL . 'compte/modificationPassword');
        }
    }

    public function validation_suppressionCompte()
    {
        $this->dossierSuppressionImageUtilisateur($_SESSION['profil']['login']);
        rmdir("public/assets/images/profils/" . $_SESSION['profil']['login']);

        if ($this->utilisateurManager->dbSupressionCompte($_SESSION['profil']['login'])) {
            // self::deconnexion();
            Toolbox::ajouterMessageAlerte("La suppression du compte est effectuée.", Toolbox::COULEUR_VERTE);
            $this->deconnexion();
        } else {
            Toolbox::ajouterMessageAlerte("La suppression du compte a échoué. Contactez votre administrateur.", Toolbox::COULEUR_ROUGE);
            header('Location:' . URL . 'compte/profil');
        }
    }
    public function validation_modificationImage($file)
    // on ne reçoit pas une image mais un tableau d'informations, donc file et pas image pour le nom du paramètre
    {
        try {

            // chaque user aura son répertoire, on y ajoute l'image
            $repertoire = "public/assets/images/profils/" . $_SESSION['profil']['login'] . "/";
            $nomImage = Toolbox::ajoutImage($file, $repertoire);
            // suppression ancienne image
            $this->dossierSuppressionImageUtilisateur($_SESSION['profil']['login']);
            // ajout nouvelle image en bd
            $nomImageBD = "profils/" . $_SESSION['profil']['login'] . "/" . $nomImage;
            if ($this->utilisateurManager->bdAjoutImage(($_SESSION)['profil']['login'], $nomImageBD)) {
                Toolbox::ajouterMessageAlerte("La modification de l'image est effectuée ! 😍", Toolbox::COULEUR_VERTE);
            } else {
                Toolbox::ajouterMessageAlerte("La modification de l'image n'a pas été effectuée 💀", Toolbox::COULEUR_ROUGE);
            }
        } catch (Exception $e) {
            Toolbox::ajouterMessageAlerte($e->getMessage(), Toolbox::COULEUR_ROUGE);
        }
        header('Location:' . URL . 'compte/profil');
    }
    private function  dossierSuppressionImageUtilisateur($login)
    {

        $ancienneImage = $this->utilisateurManager->getImageUtilisateur($_SESSION['profil']['login']);
        if ($ancienneImage !== "profils/profil.png") {
            unlink("public/assets/images/" . $ancienneImage);
        }
    }
}
