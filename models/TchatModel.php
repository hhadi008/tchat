<?php

/* 
 * Model Tchat
 */
class TchatModel{
    
    protected $pdo;
    
    /**
     * Init PDO
     */
    protected function initPdo(){
        $this->pdo = new PDO('mysql:dbname=tchat;host=localhost', 'root', '');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    /**
     * Authentification:
     * Si le pseudo exsite alors on s'authentifier puis on affiche la page du tchat
     * Sinon on crée un user 
     */
    public function auth(){
        $this->initPdo();
        $items = array();
        if(isset($_POST["pseudo"]) && !empty($_POST["pseudo"]) 
                && isset($_POST["password"]) && !empty($_POST["password"])){
            //pour les failles XSS
            //Empêcher le post de tout tags HTMl
            $pseudo     = strip_tags($_POST["pseudo"]);
            //$password   = crypt($_POST["password"], CRYPT_MD5);
            $password   = sha1($_POST["password"]);
            
            $result = $this->pdo->query("SELECT mot_passe FROM users WHERE pseudo = '".$pseudo."'");
            $user   = $result->fetchAll(PDO::FETCH_OBJ);
            if(count($user) > 0){
                if($user[0]->mot_passe !== $password){
                    $items['resultat']    = "password diff";
                    $items['pseudo']      = $_POST["pseudo"];
                }else{
                    $statement = $this->pdo->prepare("UPDATE users SET date_connect = NOW(), is_connect = '1' WHERE pseudo = '".$pseudo."'");
                    $statement->execute();
                    $items['resultat']    = "pseudo existe";
                    $items['pseudo']      = $_POST["pseudo"];
                }
            }else{
                $statement = "INSERT INTO users(pseudo, mot_passe, date_connect, is_connect) VALUES('".$pseudo."', '".$password."', NOW(), '1')";
                $nbLigne = $this->pdo->exec($statement);
                if($nbLigne > 0){
                    $items['resultat']    = "pseudo crée";
                    $items['pseudo']      = $_POST["pseudo"];
                }else{
                    $items['resultat']    = "erreur création compte";
                }
            }
            
        }
        
        return $items;
        
    }

    /**
     * Get Messages(tchat)
     * @return type
     */
    public function getMessages() {
        $this->initPdo();
        $statement = $this->pdo->query("SELECT * FROM 
                                    (
                                        SELECT id, pseudo, message, DATE_FORMAT(date_post, '%d/%m/%Y %H:%i:%s') as date_post 
                                        FROM `messages`
                                        WHERE message != '' AND pseudo != ''
                                        ORDER BY id DESC
                                        LIMIT 15
                                    ) MSG
                                        ORDER BY id ASC");
        return ($statement->fetchAll(PDO::FETCH_OBJ));
    }
    
    /**
     * get Connected
     * @return type
     */
    public function getConnected() {
        $this->initPdo();
        $data = array();
        $statement = $this->pdo->query("SELECT pseudo
                                        FROM `users`
                                        WHERE is_connect = '1'
                                        ORDER BY pseudo ASC");
        $data = $statement->fetchAll(PDO::FETCH_OBJ);
        
        return $data;
    }
    
    /**
     * Post du message dans le tchat
     */
    public function postMessage(){
        $data = array();
        
        if(isset($_POST["pseudo"]) && !empty($_POST["pseudo"]) && isset($_POST["message"]) && !empty($_POST["message"])){
            $this->initPdo();
            $pseudo = strip_tags($_POST["pseudo"]);
            $message = strip_tags($_POST["message"]);
            $statement  = $this->pdo->prepare("INSERT INTO messages(pseudo, message, date_post) VALUES('".$pseudo."', '".$message."', NOW())");
            
            try {
                $statement->execute();
                $statement  = $this->pdo->prepare("UPDATE users SET is_connect = '1', date_connect = NOW() WHERE pseudo = '".$pseudo."' AND is_connect != '1'");
                $statement->execute();
                return true;
            } catch (Exception $ex) {
                return false;
            }
            
        }
       
    }
    
    /**
     * Get Messages(tchat)
     * @return type
     */
    public function getMessagesForTimer() {
        $this->initPdo();
        $statement = $this->pdo->query("SELECT * FROM 
                                    (
                                        SELECT id, pseudo, message,  
                                        DATE_FORMAT(date_post, '%d/%m/%Y %H:%i:%s') as date_post 
                                        FROM `messages`
                                        WHERE message != '' AND pseudo != ''
                                        ORDER BY id DESC
                                        LIMIT 15
                                    ) MSG
                                        ORDER BY id ASC");
        $data = $statement->fetchAll(PDO::FETCH_OBJ);
        
        echo json_encode($data);
    }
    
    /**
     * listing Connected
     * @return type
     */
    public function listingConnected() {
        $this->initPdo();
        $data = array();
        $statement = $this->pdo->query("SELECT pseudo
                                        FROM `users`
                                        WHERE is_connect = '1'
                                        ORDER BY pseudo ASC");
        $data = $statement->fetchAll(PDO::FETCH_OBJ);
        
        echo json_encode($data);
    }
    
    /**
     * Si l'utilisateur n'as pas posté de message aprés une 1min de sa connexion
     * Alors il n'est plus connecté
     */
    public function UpdateTimeConnected() {
        $this->initPdo();
        $pseudo = $this->pdo->quote($_POST["pseudo"]);
        
        $statement = $this->pdo->query("SELECT TIMESTAMPDIFF(SECOND, date_connect, NOW()) as date_when_connect FROM users WHERE pseudo = " . $pseudo . " ");
        $users = $statement->fetchAll(PDO::FETCH_OBJ);
        
        $temp_passe = "";

        if (isset($users) && !empty($users)) {
            if ($users[0]->date_when_connect > 60) {
                $statement = $this->pdo->prepare("UPDATE users SET is_connect = '0' WHERE pseudo = " . $pseudo . "");
                try {
                    $statement->execute();
                    return true;
                } catch (Exception $ex) {
                    return false;
                }
            }
        }
    }
    
}
