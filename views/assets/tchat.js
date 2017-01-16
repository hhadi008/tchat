$(function(){
    //Actualisation des messages postés 
    var timerMessages = setInterval(getMessageForTimer, 2000);
    
    //Actualisation de la liste des users connectés 
    var timerConnected = setInterval(listingConnected, 2000);
    
    //Mise à jour des users en fonction du temps passé après le post du dernier message
    var timerUpdateTimeConnected = setInterval(UpdateTimeConnected, 5000);
    
    $("#tchatForm").submit(function(){
        var message = $("#message").val();
        var pseudo = $("#pseudo_h").val();
        
        data = "&pseudo="+pseudo+"&message="+message;
        $.ajax({
            type: "POST",
            url: "index.php?option=postMessage"+data,
            data: data,
            success: function (res) {
                $("#message").val('');
            }
        });
        return false; 
    });
    /**
     * Actualisation des messages
     * @returns {undefined}
     */
    function getMessageForTimer(){
        $.ajax({
            type: "POST",
            url: "index.php?option=getMessagesForTimer",
            success: function (res) {
                var msgs = JSON.parse(res);
                $("#tchat").empty();
                $.each(msgs, function( index, value ) {
                    $("#tchat").append("<p><b>"+value["pseudo"]+"</b> ( "+value["date_post"]+" ) : "+value["message"]+"</p>");
                });
            }
        });
    }
    /**
     * Liste les utilisateurs connectés
     * @returns {undefined}
     */
    function listingConnected(){
        $.ajax({
            type: "POST",
            url: "index.php?option=listingConnected",
            success: function (res) {
                var listingConnected = JSON.parse(res);
                $("#listing").empty();
                $.each(listingConnected, function( index, value ) {
                    $("#listing").append("<p><b>"+value["pseudo"]+"</b></p>");
                });
            }
        });
    }
    /**
     * Mettre à jour la table users 
     * Si l'utilisateur n'as pas posté de message aprés d'une 1min de sa connexion 
     * alors il n'est plus connecté
     * @returns {undefined}
     */
    function UpdateTimeConnected(){
        var pseudo = $("#pseudo_h").val();
        var data = "&pseudo="+pseudo;
        $.ajax({
            type: "POST",
            url: "index.php?option=UpdateTimeConnected"+data,
            data: data,
            success: function (res) {}
        });
    }
    
});
