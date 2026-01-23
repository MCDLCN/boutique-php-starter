<?php

// config/routes.php
//require_once __DIR__ . '/../vendor/autoload.php';
use App\Controller\CartController;
use App\Controller\HomeController;
use App\Controller\ProductController;
use App\Controller\UserController;
use App\Router;

$router = new Router();

// Public pages
$router->get('/', [HomeController::class, 'index']);
$router->get('/catalog', [ProductController::class, 'index']);
$router->get('/product/{id}', [ProductController::class, 'show']);
$router->post('/product/{id}/review', [ProductController::class, 'submitReview']);

// Panier
$router->get('/cart', [CartController::class, 'index']);
$router->post('/cart/add', [CartController::class, 'add']);
$router->post('/cart/remove', [CartController::class, 'remove']);
$router->post('/cart/update', [CartController::class, 'update']);
$router->post('/cart/empty', [CartController::class,'empty']);

// User Authentication
$router->post('/auth/register', [UserController::class, 'registerAction']);
$router->post('/auth/login', [UserController::class, 'loginAction']);
$router->get('/auth/logout', [UserController::class, 'logoutAction']);

// Contact
// $router->get('/contact', [ContactController::class, 'index']);
// $router->post('/contact', [ContactController::class, 'send']);

return $router;

