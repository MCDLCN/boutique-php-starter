<?php
require_once __DIR__.'/../vendor/autoload.php';

use App\Controller\HomeController;
use App\Router;
use App\Controller\ProductController;
use App\Controller\TestController;

// echo $_SERVER['REQUEST_URI'];
// echo $_SERVER['REQUEST_METHOD'];


$router = new router();

// $router->get('/test',[TestController::class,'index']);

$router->get('/',[HomeController::class,'index']);

$router->get('/products', [ProductController::class,'index']);
$router->get('/product/{id}', [ProductController::class,'show']);


$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
?>
<!-- <br>
<a href="/products" class=""brn">Products</a>
<form><input href="/product" name="id"></input></form> -->