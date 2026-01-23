<?php

require_once __DIR__ .'/product.php';

$product1 = new Product(1, 'T-Shirt', 'this is a T-Shirt', 10, 50, 'clothes');
$product2 = new Product(2, 'Jean', 'this is a Jean', 9.99, 5, 'clothes');
$product3 = new Product(3, 'Socks', 'those are Socks', 1, 500, 'clothes');
$product4 = new Product(4, 'Sweatshirt', 'this is a Sweatshirt', 15, 100, 'clothes');
$product5 = new Product(5, 'Sweatpants', 'this is a Sweatpants', 20, 130, 'clothes');

$products = [$product1, $product2, $product3, $product4, $product5];
$totalCatalog = 0;
$totalStock = 0;
foreach ($products as $product) {
    echo $product->name.'<br>';
    echo $product->price.'<br>';
    echo $product->category.'<br>';
    $totalCatalog += $product->price * $product->stock;
    $totalStock += $product->stock;
    echo '<br>';
    echo '<br>';
}

echo 'Total catalog: '.$totalCatalog;
echo '<br>';
echo 'Total stock: '.$totalStock;
