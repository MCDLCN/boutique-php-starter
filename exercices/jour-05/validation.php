<?php

function isInStock(int $stock): bool
{
    return $stock > 0;
}
function isOnSale(int $discount): bool
{
    return $discount > 0;
}
function isNew(string $dateAdded): bool
{
    return strtotime($dateAdded) > strtotime('now - 30 day');
}
function canOrder(int $stock, int $quantity): bool
{
    return $stock > $quantity;
}

$products = [
    [
        'name' => 'Leather',
        'price' => 29.99,
        'stock' => 150,
        'image' => 'https://via.placeholder.com/300x300?text=Leather',
        'discount' => 0,
        'new' => true,
        'category' => 'material',
        'dateAdded' => '2025-12-31'
    ],
    [
        'name' => 'Glasses',
        'price' => 9.99,
        'stock' => 15,
        'image' => 'https://via.placeholder.com/300x300?text=Glasses',
        'discount' => 80,
        'new' => false,
        'category' => 'accessories',
        'dateAdded' => '2024-10-01'
    ],
    [
        'name' => 'Will to live',
        'price' => 9999999,
        'stock' => 0,
        'image' => 'https://via.placeholder.com/300x300?text=Will to live',
        'discount' => 0,
        'new' => false,
        'category' => 'idk',
        'dateAdded' => '2026-01-06'
    ],
    [
        'name' => 'AAAA',
        'price' => 2.99,
        'stock' => 5,
        'image' => 'https://via.placeholder.com/300x300?text=AAAA',
        'discount' => 15,
        'new' => true,
        'category' => 'AAAA',
        'dateAdded' => '2023-02-15'
    ]];

foreach ($products as $product) {
    echo $product['name'];
    echo '<br>';
    var_dump(isInStock($product['stock']));
    echo '<br>';
    var_dump(isOnsAle($product['discount']));
    echo '<br>';
    var_dump(isNew($product['dateAdded']));
    echo '<br>';
    var_dump(canOrder($product['stock'], 1));
    echo '<br>';
    var_dump(canOrder($product['stock'], 100));
    echo '<br>';
}
