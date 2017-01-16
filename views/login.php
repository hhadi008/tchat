<?php

if (isset($items) && !empty($items)) {
    if($items["resultat"] == "password diff"){?>
        <p>
            Erreur de mot passe..<br />
            Veuillez recommencer avec le bon mot de passe !!!
        </p>
    <?php }
}
?>
<form action="index.php?option=tchat" method="post">
    <div class="form-group">
        <label for="pseudo">Pseudonyme</label>
        <input type="text" name="pseudo" value="<?php echo (isset($_SESSION["pseudo"]) && !empty($_SESSION["pseudo"]))? $_SESSION["pseudo"] : '';?>" id="pseudo" class="form-control" required="true">
    </div>
    <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password" class="form-control" required="true">
    </div>
    <button type="submit" class="btn btn-primary form-control">Se connecter</button>
</form>


