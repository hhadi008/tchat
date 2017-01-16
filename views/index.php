<?php
if (isset($items) && !empty($items)) {
    if (isset($items["pseudo"]) && !empty($items["pseudo"])) {
        ?>
        <p>Vous êtes connecté en tant que <b><?php echo $items["pseudo"]; ?></b>,
            <?php
            if (isset($items["resultat"]) && !empty($items["resultat"])) {
                if ($items["resultat"] == "pseudo crée") {
                    ?>
                    un compte a été crée avec ce pseudonyme, 
                    <?php
                }
            }
            ?>
            vous pouvez alors tchater...</p>        
        <?php
    }
}
?>
<div class="row">
    <div class="col-md-8">
        <legend>Les messages postés : </legend>
        <div class="form-group">
            <div class="form-control" id="tchat" style="width: 100%;height: 100%;margin-left: auto;margin-right: auto;">
                <?php
                if (isset($messages) && !empty($messages)) {
                    foreach ($messages as $msg) {
                        ?>
                        <p><b><?php echo $msg->pseudo; ?></b> ( <?php echo $msg->date_post; ?> ) : <?php echo $msg->message; ?></p>
                    <?php
                    }
                }
                ?>
            </div>
        </div>
        <div class="form-group">
            <form action="#" method="post" id="tchatForm">
                <div class="form-group">
                    <textarea class="form-control" rows="3" cols="10" id="message" required></textarea>
                </div>
                <input type="hidden" id="pseudo_h" value="<?php echo (isset($items["pseudo"]) && !empty($items["pseudo"])) ? $items["pseudo"] : ''; ?>"/>
                <div class="form-group">
                    <button type="submit" class="form-control btn btn-primary" id="envoyer">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-4">
        <legend>Liste des utilisateurs connectés</legend>
        <div class="form-control" id="listing" style="width: 100%;height: 100%;margin-left: auto;margin-right: auto;">
            <?php
            if (isset($connected) && !empty($connected)) {
                foreach ($connected as $connect) {
                    ?>
                    <p><b><?php echo $connect->pseudo; ?></b></p>
                <?php }
                }
                ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="views/assets/tchat.js"></script>  
