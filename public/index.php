<?php

require '../helpers.php';
require basePath('Framework/Database.php');
require basePath('Framework/Router.php');


// instantiating the router
$router = new Router();

// get routes
$routes = require basePath('routes.php');

//get uri and http method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// route the request
$router->route($uri, $method);