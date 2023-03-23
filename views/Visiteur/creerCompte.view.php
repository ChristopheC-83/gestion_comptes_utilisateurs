<div class="page_title <?= $css ?>">

    <h1>Création de compte</h1>

    <!-- actino est la page vers laquelle les infos sont envoyées qd on submit. -->
    <form action="validation_creerCompte" method="post">
        <div class="entry_formulaire">
            <label for="login">Login</label>
            <br>
            <!-- le "name" est le nom de l'information transmise -->
            <input type="text" id="login" name="login" placeholder="Votre pseudo">
        </div>
        <div class="entry_formulaire">
            <label for="password">Password :</label>
            <br>
            <input type="password" id="password" name="password" placeholder="Votre mot de passe">
        </div>
        <div class="entry_formulaire">
            <label for="mail">Mail :</label>
            <br>
            <input type="mail" id="mail" name="mail" placeholder="Votre adresse mail valide">
        </div>

        <button type="submit">Validation</button>



    </form>








</div>