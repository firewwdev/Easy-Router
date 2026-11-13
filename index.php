<?php

require __DIR__ . '/vendor/autoload.php';

use Firewwdev\EasyRouter\Router;
use Firewwdev\EasyRouter\RouterValidation;

class UserController{

    public function show(){
        return '<h1>OLÁ TUDO BEM </h1>';
    }

}

$controller = new UserController();
$router = new Router();
$val = new RouterValidation();

$router -> route('GET', '/users', [UserController::class, 'show']);
$router -> route('GET', '/users', fn () => 'teste');
