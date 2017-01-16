<?php

/* 
 * Controller Tchat
 */
class TchatController{
    
    protected $layout  = 'default';
    protected $model;
    
    /**
     * Authentification au tchat
     */
    public function tchat(){
        $this->initModel();
        $items = $this->model->auth();
        if(isset($items) && !empty($items)){
            if($items["resultat"] == "password diff"){
                $this->render('login', $items);
            }else{
                $this->render('index', $items);
            }
        }
    }

    /**
     * Renvoi la vue demander par le controleur
     */
    protected function render($views, $items = []){
        ob_start();
        extract($items);
        $messages   = $this->getMessages();
        if(isset($messages) && !empty($messages)){
            extract($messages);
        }
        $connected   = $this->getConnected();
        if(isset($connected) && !empty($connected)){
            extract($connected);
        }
        require(ROOT.'views/'.$views.'.php');
        $content = ob_get_clean();
        require(ROOT.'views/layouts/'.$this->layout.'.php');
    }
    
    /**
     * initiation Model
     */
    protected function initModel(){
        $this->model = new TchatModel();
    }
    
    /**
     * Se connecter au tchat
     */
    public function login(){
        $this->render('login');
    }
    
    /**
     * Get Messages(tchat)
     */
    public function getMessages(){
        $this->initModel();
        $messages = $this->model->getMessages();
        return $messages;
    }
    
    /**
     * listing connectés
     */
    public function getConnected(){
        $this->initModel();
        return $this->model->getConnected();
    }
    
    /**
     * Post du message
     */
    public function postMessage(){
        $this->initModel();
        return $this->model->postMessage();
    }
    
    /**
     * get message 
     */
    public function getMessagesForTimer(){
        $this->initModel();
        return $this->model->getMessagesForTimer();
    }
    
    /**
     * listing connectés
     */
    public function listingConnected(){
        $this->initModel();
        return $this->model->listingConnected();
    }
    
    /**
     * Si l'utilisateur n'as pas posté de message aprés d'une 1min de sa connexion
     * Alors il n'est plus connecté
     */
    public function UpdateTimeConnected(){
        $this->initModel();
        $this->model->UpdateTimeConnected();
    }
    
}
