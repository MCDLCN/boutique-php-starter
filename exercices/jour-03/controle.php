<?php

$products = [
    ["name" => "T-shirt",   "stock" => 12,	"price" => 15],
    ["name" => "Glasses",   "stock" => 0,	"price" => 25],
    ["name" => "Hat",       "stock" => 7,	"price" => 16],
    ["name" => "Shoes",     "stock" => 25,	"price" => 46],
    ["name" => "Watch",     "stock" => 0,	"price" => 17],
    ["name" => "Backpack",  "stock" => 4,	"price" => 79],
    ["name" => "Jacket",    "stock" => 18,	"price" => 1],
    ["name" => "Socks",     "stock" => 0,	"price" => 100],
    ["name" => "Belt",      "stock" => 9,	"price" => 7],
    ["name" => "Cap",       "stock" => 2,	"price" => 197],
    ["name" => "BWA",       "stock" => 6,	"price" => 97]
];
foreach ($products as $product) {
    if ($product["stock"] == 0) {
        continue;
    }
    if ($product["price"] > 100) {
        break;
    }
    echo $product["name"];
    echo '<br>';

}
