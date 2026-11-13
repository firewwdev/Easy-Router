<?php

namespace Firewwdev\EasyRouter;

use Firewwdev\EasyRouter\Exceptions\InvalidRouteActionException;
use Firewwdev\EasyRouter\RouterValidation;

class Router
{

    private RouterValidation $validator;

    public function __construct()
    {
        $this -> validator = $validator ?? new RouterValidation;
    }

    public function dispatch(){

    }

    public function route(
        string $http_method, // -> Ex: 'GET', 'POST', 'PUT', 'PATCH'...
        string $route, // -> Ex: '/users/{id}' or /admin'
        array|callable $action // -> Ex: [UserController::class, 'show'] 
    ): mixed
    {

        // Route:
        $requested_method = $this -> getMethod(); // The method of the requisition

        if($requested_method !== $http_method){ // Validates whether the request method matches the route method
            return null;
        }

        $uri_parts  = $this -> explode($this -> getUri());
        $route_parts = $this -> explode($route); 

        $validate = $this -> validator -> validateRoute($route_parts, $uri_parts);

        if($validate['is_valid'] === false){
            return null;
        }

        // Method:

        if(is_array($action)){

            [$class, $method] = $action;

            if(!class_exists($class)){
                throw new InvalidRouteActionException("Controller not found: {$class}");
            };

            $classObject = new $class;

            if(!is_callable([$classObject, $method])){
                throw new InvalidRouteActionException("Method not found or not acessible: {$method}");
            }

            return $classObject -> $method(...$validate['parameters']);

        }else{

            if(is_callable($action)){
                return $action(...$validate['parameters']);
            }

            return null;

        }

    }

    // -> Get
    public function get(string $route, array|callable $action){
        return $this -> route('GET', $route, $action);
    }

    // -> Post
    public function post(string $route, array|callable $action){
        return $this -> route('POST', $route, $action);
    }

    // -> PUT
    public function put(string $route, array|callable $action){
        return $this -> route('PUT', $route, $action);
    }

    // -> PATCH
    public function patch(string $route, array|callable $action){
        return $this -> route('PATCH', $route, $action);
    }

    // -> DELETE
    public function delete(string $route, array|callable $action){
        return $this -> route('DELETE', $route, $action);
    }

    // -> OPTIONS
    public function options(string $route, array|callable $action){
        return $this -> route('OPTIONS', $route, $action);
    }

    private function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD']; // -> // Returns the requested method, ex: 'GET', 'POST', 'PUT', 'PATCH'...
    }

    private function getUri(): string // -> Returns the requestes uri, ex: /users
    {
        return $_SERVER['REQUEST_URI'];
    } 

    public function explode(string $uri): array // -> Converts the URI string into an array
    {
        return explode('/', trim($uri, '/')); 
    }

    // Ex:
    // /user/12
    // {
    //     0 => 'user',
    //     1 => '12'
    // }

}