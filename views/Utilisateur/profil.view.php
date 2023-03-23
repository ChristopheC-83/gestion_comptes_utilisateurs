<div class="page_title <?= $css ?>">

    <h1 id=titre_gsap>Page du profil de <?= $utilisateur['login'] ?></h1>

    <div class="imageEtModif">
        <div><img src="<?= URL ?>public/assets/images/<?= $utilisateur['image'] ?>" width="150px" alt="photo de profil">
        </div>
        <!-- enctype obligatoire qd données images (ou autres ? à voir) à recupérer -->
        <form action="<?= URL ?>compte/validation_modificationImage" method="post" enctype="multipart/form-data">
            <div>
                <label for="image">Changer l'image de profil</label><br>
                <input type="file" name="image" id="image" onchange="submit()">

            </div>
    </div>



    </form>

    <br>
    <div id="role">
        <p>Role : <?= $_SESSION['profil']['role'] ?></p>
    </div>
    <br>
    <div id="mail">
        <p>Mail : <?= $utilisateur['mail'] ?></p>
        <button><i class="fa-solid fa-pen" id="btnModifMail"></i></button>
    </div>
    <br>
    <div id="modificationMail" class="div_cachee">
        <form action="<?= URL ?>compte/validation_modificationMail" method="post">
            <div class="entry_formulaire">
                <label for="mail2">Nouvelle adresse :</label>

                <input type="text" placeholder="<?= $utilisateur['mail'] ?>" name="mail" id="mail2">

                <button id="btnValidationModifMail">Valider la nouvelle adresse</button>
            </div>
        </form>

    </div>

    <button id="btnModifMdp"><a href="<?= URL ?>compte/modificationPassword">Modifier le mot de passe</a></button>
    <button id="btnSuppCompte" class="btnSuppCompte">Supprimer mon compte</button>
</div>

<div id="suppCompte" class="validationSupp dnone ">
    <a href="<?= URL ?>compte/suppressionCompte">
        Veuillez confirmer la supression. <br>Action définitive et irréversible !
    </a>

</div>
   