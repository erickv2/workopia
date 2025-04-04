<?php

namespace Framework;

use App\Controllers\ErrorController;

class Router {
    protected $routes = [];


    /**
     * add a new route
     * 
     * @param $method
     * @param string $Uri
     * @param string $action
     * @return void
     */

    public function registerRoute($method, $uri, $action) {

        list($controller, $controllerMethod) = explode('@', $action);

        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod
        ];
    }

    /**
     * load error page
     * 
     * @param int $httpCode
     * 
     * @return void
     */

    /**
     * add a GET route
     * 
     * @param string $uri
     * @param string $controller
     * @return void
     */

    public function get($uri, $controller) {
        $this->registerRoute('GET', $uri, $controller);
    }

        /**
     * add a POST route
     * 
     * @param string $uri
     * @param string $controller
     * @return void
     */

     public function post($uri, $controller) {
        $this->registerRoute('POST', $uri, $controller);
     }

         /**
     * add a PUT route
     * 
     * @param string $uri
     * @param string $controller
     * @return void
     */

    public function put($uri, $controller) {
        $this->registerRoute('PUT', $uri, $controller);
    }

        /**
     * add a DELETE route
     * 
     * @param string $uri
     * @param string $controller
     * @return void
     */

     public function delete($uri, $controller) {
        $this->registerRoute('DELETE', $uri, $controller);
     }

        /**
     * route the request
     * 
     * @param string $uri
     * @param string $method
     * @return void
     */

    public function route($uri, $method) {
        foreach($this->routes as $route) {
            if($route['uri'] === $uri && $route['method'] === $method) {
            
            //extract controller and controller method

            $controller = 'App\\Controllers\\' . $route['controller'];
            $controllerMethod = $route['controllerMethod'];

            // instantiate the controller and call the method

            $controllerInstance = new $controller();
            $controllerInstance->$controllerMethod();

            return;
            }
        }

        ErrorController::notFound();

    }
}