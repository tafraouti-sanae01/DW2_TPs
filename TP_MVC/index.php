<?php

// Chemin vers index.php
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));
require_once ROOT . 'Controllers/Controller.php';
require_once ROOT . 'Models/Model.php';

// récupérer les info de l URL
$url = [];
if (isset($_GET['url'])) {
    $url = explode('/', $_GET['url']);
}

//instanciation de l objet contrôleur
if (!empty($url) && $url[0] != '') {
    $controller = ucfirst($url[0]);
    // Tester si le Controleur exist
    if (file_exists(ROOT . 'Controllers/' . $controller . '.php')) {
        if (isset($url[1])) {
            $action = $url[1];
        } else {
            $action =  'index';
        }
        require_once ROOT . 'Controllers/' . $controller . '.php';

        if (method_exists($controller, $action)) {            
            $controller = new $controller;
            unset($url[0]);
            unset($url[1]);
            //call_user_func_array permet d'appeler une fonction en lui faisant passer des paramètres sous forme de tableau.
            call_user_func_array([$controller, $action], $url);
        }
    } else {
        require ROOT . 'Views/404.php';
    }
} else {
    require_once(ROOT . 'controllers/Main.php');
    $controller = new Main();
    $controller->index();
}
