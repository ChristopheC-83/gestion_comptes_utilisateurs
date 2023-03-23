<nav class="navContainer">

    <ul>
        <li><a href="<?= URL ?>accueil">Accueil</a></li>
        <li><a href="<?= URL ?>page1">Page 1</a></li>
        <li><a href="<?= URL ?>page2">Page 2</a></li>


        <?php if (Securite::estConnecte() && Securite::estAdministrateur()) : ?>

            <li><a href="<?= URL ?>administration/droits">Gérer les droits</a></li>
            <li><a href="<?= URL ?>administration/page2">Admin 2</a></li>

        <?php endif ?>
    </ul>

    <?php if (!Securite::estConnecte()) : ?>
        <a href="<?= URL ?>login"><img src="<?= URL ?>public/assets/images/connexion/cadenas.png"></a>
        <a href="<?= URL ?>creerCompte"><i class="fa-regular fa-square-plus"></i></a>
    <?php else : ?>
        <a href="<?= URL ?>compte/profil"><img src="<?= URL ?>public/assets/images/connexion/buste.png"></a>
        <a href="<?= URL ?>compte/deconnexion"><img src="<?= URL ?>public/assets/images/connexion/door.jpg"></a>
    <?php endif ?>





</nav>