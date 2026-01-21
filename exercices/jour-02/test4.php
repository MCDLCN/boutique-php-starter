<?php

$products = [
    [
        "name" => "T-shirt",
        "price" => 29.99,
        "stock" => 50
    ],
    [
        "name" => "Jean",
        "price" => 79.99,
        "stock" => 30
    ],
    [
        "name" => "Casquette",
        "price" => 19.99,
        "stock" => 100
    ]
];

// Accéder au premier produit
echo $products[0]["name"]; // T-shirt

// Accéder au prix du deuxième produit
echo $products[1]["price"]; // 79.99
