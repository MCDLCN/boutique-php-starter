<?php
// CRÉER un cookie (expire dans 30 jours)
setcookie(
    'theme',
    'dark',
    [
        'expires' => time() + 30 * 24 * 60 * 60,
        'path' => '/',
        'httponly' => true,   // Inaccessible en JavaScript (sécurité XSS)
        'samesite' => 'Lax'   // Protection CSRF
    ]
);

// LIRE un cookie
$theme = $_COOKIE['theme'] ?? 'light';

// SUPPRIMER un cookie (expire dans le passé)
setcookie('theme', '', time() - 3600, '/');

// À toi : crée un cookie pour garder le panier de l'utilisateur
// Attention : que mettre dans le cookie ? IDs des produits ? JSON ?
$cartData= json_encode($_COOKIE['cart'] ?? []);
setcookie(
    'cart',
    $cartData,   
 [
    'expires' => time() + 30 * 24 * 60 * 60,   
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Lax'
 ]
 );

$cart = json_decode($_COOKIE['cart'] ?? '[]', true);