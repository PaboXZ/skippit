<?php

declare(strict_types=1);

namespace Framework;

class Router{
    private array $routes = [];
    private array $middlewares = [];

    public function dispatch(string $path, string $method, ?Container $container = null){

        foreach($this->routes as $route){

            if(isset($_POST['_METHOD'])){
                $method = strtoupper($_POST['_METHOD']);
            }

            if(!preg_match($route['regexPath'], $path) || $route['method'] != $method){
                continue;
            }

            preg_match_all('#{([^/]+)}#', $route['path'], $keys);
            preg_match_all($route['regexPath'], $path, $values);

            array_shift($keys);
            array_shift($values);

            $params = [];

            if(isset($values[0])){
                $params = array_combine($keys[0], $values[0]);
            }


            [$controller, $function] = $route['controller'];

            $controllerInstance = $container ? $container->resolve($controller) : new $controller;

            $action = fn () => $controllerInstance->$function($params);

            $allMiddlewares = [...$route['middlewares'], ...$this->middlewares];

            foreach($allMiddlewares as $middleware){
                $middlewareInstance = $container ? $container->resolve($middleware) : new $middleware;
                $action = fn () => $middlewareInstance->process($action);
            }

            $action();
        }
    }

    public function add(string $path, string $method, array $controller, array $middlewares = []){

        $regexPath = preg_replace('#{[^\/]+}#','([^/]+)', $path);

        $this->routes[] = [
            'path' => $path,
            'method' => $method,
            'controller' => $controller,
            'middlewares' => $middlewares,
            'regexPath' => "#^{$regexPath}$#"
        ];
        return $this;
    }

    public function addMiddleware(string $middleware){
        $this->middlewares[] = $middleware;
    }

    public function addRouteMiddleware(string $middleware){
        $lastRouteKey = array_key_last($this->routes);
        $this->routes[$lastRouteKey]['middlewares'][] = $middleware;
    }
}