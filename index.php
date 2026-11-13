<?php

require __DIR__ . '/vendor/autoload.php';

use Firewwdev\EasyRouter\Router;
use Firewwdev\EasyRouter\RouterValidation;

class UserController{

    public function show(){
        return 'Gabriel Gomes';
    }

}

$controller = new UserController();
$router = new Router();
$val = new RouterValidation();

$response = $router -> route('GET', '/users', [UserController::class, 'show']);
echo $response;
$response2 = $router -> route('GET', '/users', fn () => 'teste');
echo $response2;

echo $_SERVER['REQUEST_URI'];
echo '</br>';
var_dump($router -> explode($_SERVER['REQUEST_URI']));
echo '</br>';
echo $_SERVER['REQUEST_METHOD'];
