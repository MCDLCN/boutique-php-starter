<?php

$products = [
    [
        'name' => 'T-shirt',
        'price' => 29.99,
        'stock' => 50
    ],
    [
        'name' => 'Glasses',
        'price' => 9.99,
        'stock' => 15
    ],
    [
        'name' => 'Will to live',
        'price' => 9999999,
        'stock' => 0
    ],
    [
        'name' => 'AAAA',
        'price' => 2.99,
        'stock' => 5
    ],
    [
        'name' => 'Amogus',
        'price' => 299,
        'stock' => 500
    ]
];
echo $products[2]['name'];
echo '<br>';
echo $products[0]['price'];
echo '<br>';
echo $products[count($products) - 1]['stock'];
echo '<br>';
$products[1]['stock'] = $products[1]['stock'] + 10;
echo $products[1]['stock'];
echo '<br>';
