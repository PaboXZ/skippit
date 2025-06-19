<?php

declare(strict_types=1);

namespace Framework;

use Framework\Router;

class App {

    private Router $router;
    private Container $container;

    public function __construct(){
        $this->router = new Router();
        $this->container = new Container();

        $containerDefinitions = include(__DIR__ . "/../App/container-definitions.php");
        $this->container->addDefinitions($containerDefinitions);
    }

    public function run(){
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        $this->router->dispatch($path, $method, $this->container);
    }

    public function get(string $path, array $controller, array $middlewares = []){
        $this->router->add($path, 'GET', $controller, $middlewares);
        return $this;
    }
    public function post(string $path, array $controller, array $middlewares = []){
        $this->router->add($path, 'POST', $controller, $middlewares);
        return $this;
    }
    public function delete(string $path, array $controller, array $middlewares = []){
        $this->router->add($path, 'DELETE', $controller, $middlewares);
        return $this;
    }

    public function addMiddleware(string $middleware){
        $this->router->addMiddleware($middleware);
        return $this;
    }

    public function addRouteMiddleware(string $middleware){
        $this->router->addRouteMiddleware($middleware);
        return $this;
    }
}