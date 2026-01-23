<?php

$product = ['T-shirt', 29.99, 50];

// Au lieu de :
$name = $product[0];
$price = $product[1];
$stock = $product[2];

// Tu peux écrire :
[$name, $price, $stock] = $product;

// Avec un tableau associatif :
$data = ['name' => 'Jean', 'price' => 79.99];
// À toi de trouver la syntaxe pour extraire 'name' dans $n et 'price' dans $p
$n = $data['name'];
echo $n;
echo '<br>';
$p = $data['price'];
echo $p;
