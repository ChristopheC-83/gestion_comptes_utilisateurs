<!-- il peut exister plusieurs controller -->
<!--  il peut être monter avec POO ou fonctions -->
<!-- le .controller ou .view est optionnel -->
<!-- POO plus maintenable et adaptable, on préfèrera  -->


<?php

require_once("./controllers/Toolbox.class.php");
abstract class  MainController
{
    protected function genererPage($data)
    {
        // extract explose le tableau en variables en fonction du nom de l'indice
        // on appellera l'indice pour récupérer la donnée

        extract($data);
        ob_start();
        require_once($view);
        $page_content = ob_get_clean();
        require_once($template);
    }
    protected function pageErreur($msg)
    {
        $data_page = [
            // ce tableau montre qu'on peut rajouter ce qu'on veut en variable !
            // ici "page_title" est l'indice de la valeur "titre de la page d'erreur"
            // avec extract, on obtient la variable $page_title = titre de la page d'erreur
            "page_title" => "titre de la page d'erreur",
            "page_description" => "Description de la page d'erreur",
            "msg" => $msg,
            "view" => "views/erreur.view.php",
            "template" => "views/commons/template.php"
        ];

        $this->genererPage($data_page);
    }
}
?>