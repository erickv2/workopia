<?php

require '../helpers.php';
require basePath('Database.php');
require basePath('Router.php');


// instantiating the router
$router = new Router();

// get routes
$routes = require basePath('routes.php');

//get uri and http method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// route the request
$router->route($uri, $method);