<div class="page_title <?= $css ?>">

    <h1>Page de Connexion</h1>

    <!-- actino est la page vers laquelle les infos sont envoyées qd on submit. -->
    <form action="<?=URL?>validation_login" method="post">
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

        <button type="submit">Connexion</button>



    </form>




  



</div>