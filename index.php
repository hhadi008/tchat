<?php 
if (isset($_POST["pseudo"]) && !empty($_POST["pseudo"]) && isset($_POST["password"]) && !empty($_POST["password"])) {
    session_start();
    $_SESSION["pseudo"] = $_POST["pseudo"];
}

/**
 * Dispatcher
 */
define("ROOT", str_replace('index.php', '', $_SERVER["SCRIPT_FILENAME"]));
define("WEBROOT", str_replace('index.php', '', $_SERVER["SCRIPT_NAME"]));

//Inclure le controller
require(ROOT . 'controllers/TchatController.php');
require(ROOT . 'models/TchatModel.php');

$controller = new TchatController();
$action = "";

//Si pas d'option alors on se dirige vers la page de connexion
if (!isset($_GET['option']) && empty($_GET['option'])) {
    $action = 'login';
    $controller->$action();
    
}else {
    $action = $_GET['option'];
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        echo 'Erreur 400';
    }
}