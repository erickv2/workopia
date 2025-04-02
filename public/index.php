<?php

require '../helpers.php';


spl_autoload_register(function ($class) {
    $path = basePath('Framework/' . $class . '.php');

    if(file_exists($path)) {
        require $path;
    }
});


// instantiating the router
$router = new Router();

// get routes
$routes = require basePath('routes.php');

//get uri and http method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// route the request
$router->route($uri, $method);