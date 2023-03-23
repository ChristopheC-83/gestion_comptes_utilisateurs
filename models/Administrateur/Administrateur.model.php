<?php
require_once("./models/MainManager.model.php");

class AdministrateurManager extends MainManager
{

    public function getUtilisateurs()
    {
        $req = $this->getBdd()->prepare("SELECT * FROM utilisateur");
        $req->execute();
        $datas = $req->fetchAll(PDO::FETCH_ASSOC);
        $req->closeCursor();
        return $datas;
    }
    public function bdModificationValidationUser($login, $est_valide)
    {
        $req = "UPDATE utilisateur set est_valide= :est_valide WHERE login = :login";
        $stmt = $this->getBDD()->prepare($req);
        $stmt->bindValue(":login", $login, PDO::PARAM_STR);
        $stmt->bindValue(":est_valide", $est_valide, PDO::PARAM_INT);
        $stmt->execute();
        $estModifie = ($stmt->rowCount() > 0);
        $stmt->closeCursor();
        return $estModifie;
    }
    public function bdModificationRoleUser($login, $role)
    {
        $req = "UPDATE utilisateur set role= :role WHERE login = :login";
        $stmt = $this->getBDD()->prepare($req);
        $stmt->bindValue(":login", $login, PDO::PARAM_STR);
        $stmt->bindValue(":role", $role, PDO::PARAM_STR);
        $stmt->execute();
        $estModifie = ($stmt->rowCount() > 0);
        $stmt->closeCursor();
        return $estModifie;
    }
}
