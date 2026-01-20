<?php
// config/routes.php
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Router;
use App\Controller\HomeController;
$router = new Router();

// Pages publiques
$router->get('/', [HomeController::class, 'index']);
$router->get('/produits', [ProductController::class, 'index']);
$router->get('/produit', [ProductController::class, 'show']);

// Panier
$router->get('/panier', [CartController::class, 'index']);
$router->post('/panier/ajouter', [CartController::class, 'add']);
$router->post('/panier/supprimer', [CartController::class, 'remove']);

// Contact
$router->get('/contact', [ContactController::class, 'index']);
$router->post('/contact', [ContactController::class, 'send']);

return $router;

