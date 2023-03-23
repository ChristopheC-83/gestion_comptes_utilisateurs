<?php
require_once("./models/MainManager.model.php");

class UtilisateurManager extends MainManager
{

    public function getUtilisateurs()
    {
        $req = $this->getBdd()->prepare("SELECT * FROM utilisateur");
        $req->execute();
        $datas = $req->fetchAll(PDO::FETCH_ASSOC);
        $req->closeCursor();
        return $datas;
    }

    private function getPasswordUser($login)
    {
        // fonction privée pour être utilisée ici seulement, pas depuis un autre fichier !
        $req = "SELECT password FROM utilisateur WHERE login = :login";
        $stmt = $this->getBDD()->prepare($req);
        $stmt->bindValue(":login", $login, PDO::PARAM_STR);
        $stmt->execute();
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $resultat['password'];
    }

    public function isCombinaisonValide($login, $password)
    {
        $passwordBD = $this->getPasswordUser($login);
        // echo $passwordBD;   // <= mdp crypté apparait !
        // fonction native "password_verify" commpare le pwd au pwd crypté => retourne un boulean
        return password_verify($password, $passwordBD);
    }
    public function estCompteActive($login)
    {
        $req = "SELECT est_valide FROM utilisateur WHERE login = :login";
        $stmt = $this->getBDD()->prepare($req);
        $stmt->bindValue(":login", $login, PDO::PARAM_STR);
        $stmt->execute();
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return ((int)$resultat['est_valide'] === 1) ? true : false;
    }
    public function getUserInformation($login)
    {
        $req = "SELECT * FROM utilisateur WHERE login = :login";
        $stmt = $this->getBDD()->prepare($req);
        $stmt->bindValue(":login", $login, PDO::PARAM_STR);
        $stmt->execute();
        $datas = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $datas;
    }
    public function verifLoginDispo($login)
    {
        // on appelle le login, s'il n'existe pas dans la bdd, c'est bon, on continue !
        $utilisateur = $this->getUserInformation($login);
        return empty($utilisateur); // <= signifie "retourne une information si $utilisateur est vide
        
    }
    public function bdCreerCompte($login, $passwordCrypte, $mail, $clef, $image, $role)
    {
        $req = "INSERT INTO utilisateur (login, password, mail, est_valide, role, clef, image)
        VALUES(:login, :password, :mail, 0, :role, :clef, :image)
        ";
        $stmt = $this->getBDD()->prepare($req);
        $stmt->bindValue(":login", $login, PDO::PARAM_STR);
        $stmt->bindValue(":password", $passwordCrypte, PDO::PARAM_STR);
        $stmt->bindValue(":mail", $mail, PDO::PARAM_STR);
        $stmt->bindValue(":clef", $clef, PDO::PARAM_INT);
        $stmt->bindValue(":image", $image, PDO::PARAM_STR);
        $stmt->bindValue(":role", $role, PDO::PARAM_STR);
        $stmt->execute();
        $estModifie = ($stmt->rowCount()>0);
        $stmt->closeCursor();
        return $estModifie;  //retourne si la requête a fonctionné ou pas
        
    }
    public function bdValidationMailCompte($login, $clef)
    {
        $req = "UPDATE utilisateur set est_valide= 1 WHERE login = :login and clef = :clef";
        $stmt = $this->getBDD()->prepare($req);
        $stmt->bindValue(":login", $login, PDO::PARAM_STR);
        $stmt->bindValue(":clef", $clef, PDO::PARAM_INT);
        $stmt->execute();
        $estModifie = ($stmt->rowCount()>0);
        $stmt->closeCursor();
        return $estModifie;
        
    }
    public function bdModificationMailUser($login,$mail )
    {
        $req = "UPDATE utilisateur set mail= :mail WHERE login = :login";
        $stmt = $this->getBDD()->prepare($req);
        $stmt->bindValue(":login", $login, PDO::PARAM_STR);
        $stmt->bindValue(":mail", $mail, PDO::PARAM_STR);
        $stmt->execute();
        $estModifie = ($stmt->rowCount()>0);
        $stmt->closeCursor();
        return $estModifie;
        
    }
    public function bdModificationPassword($login, $password)
    {
        $req = "UPDATE utilisateur set password= :password WHERE login = :login";
        $stmt = $this->getBDD()->prepare($req);
        $stmt->bindValue(":login", $login, PDO::PARAM_STR);
        $stmt->bindValue(":password", $password, PDO::PARAM_STR);
        $stmt->execute();
        $estModifie = ($stmt->rowCount()>0);
        $stmt->closeCursor();
        return $estModifie;
        
    }

    public function dbSupressionCompte($login){
        $req = "DELETE FROM utilisateur WHERE login = :login";
        $stmt = $this->getBDD()->prepare($req);
        $stmt->bindValue(":login", $login, PDO::PARAM_STR);
        $stmt->execute();
        $estModifie = ($stmt->rowCount()>0);
        $stmt->closeCursor();
        return $estModifie;
    }
    public function bdAjoutImage($login, $image){
        $req = "UPDATE utilisateur set image = :image WHERE login = :login";
        $stmt = $this->getBDD()->prepare($req);
        $stmt->bindValue(":login", $login, PDO::PARAM_STR);
        $stmt->bindValue(":image", $image, PDO::PARAM_STR);
        $stmt->execute();
        $estModifie = ($stmt->rowCount()>0);
        $stmt->closeCursor();
        return $estModifie;
    }

    public function getImageUtilisateur($login){
    
        $req = "SELECT image FROM utilisateur WHERE login = :login";
        $stmt = $this->getBDD()->prepare($req);
        $stmt->bindValue(":login", $login, PDO::PARAM_STR);
        $stmt->execute();
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $resultat['image'];
    
    }
}