<?php

require_once("./controllers/MainController.controller.php");
require_once("./models/Administrateur/Administrateur.model.php");

class AdministrateurController extends MainController
{

    private $administrateurManager;

    public function __construct()
    {
        $this->administrateurManager = new AdministrateurManager();
    }

    public function pageErreur($msg)
    {
        // ici, pas besoin d'un affichage spécifique, nous reprenons l'affichage de la classe parent.
        parent::pageErreur($msg);
    }

    public function droits()
    {

        $utilisateurs = $this->administrateurManager->getUtilisateurs();

        $data_page = [
            "page_title" => "Page d'administration",
            "page_description" => "Description de la page d'admin",
            "view" => "views/Administrateur/droits.view.php",
            "template" => "views/commons/template.php",
            "utilisateurs" => $utilisateurs,
            "css" => "droits",
            "js" => ['app.js', 'admin.js'],
        ];

        $this->genererPage($data_page);
    }
    public function validation_modificationValidation($login, $validation)
    {
        if ($this->administrateurManager->bdModificationValidationUser($login, $validation)) {
            Toolbox::ajouterMessageAlerte("Modification validation effectuée.", Toolbox::COULEUR_VERTE);
        } else {
            Toolbox::ajouterMessageAlerte("Modification validation n'est pas effectuée.", Toolbox::COULEUR_ROUGE);
        }
        header("Location:" . URL . "administration/droits");
    }
    public function validation_modificationRole($login, $role)
    {
        if ($this->administrateurManager->bdModificationRoleUser($login, $role)) {
            Toolbox::ajouterMessageAlerte("Modification role effectuée.", Toolbox::COULEUR_VERTE);
            header("Location:" . URL . "administration/droits");
        } else {
            Toolbox::ajouterMessageAlerte("Modification role n'est pas effectuée.", Toolbox::COULEUR_ROUGE);
        }
        header("Location:" . URL . "administration/droits");
    }
}
