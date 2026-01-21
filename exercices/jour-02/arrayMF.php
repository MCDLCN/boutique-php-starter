<?php

$prixHT = [10, 20, 30];

// Transformer : tous les prix en TTC
$prixTTC = array_map(fn ($p) => $p * 1.2, $prixHT);
// Résultat : [12, 24, 36]

// Filtrer : garder seulement les prix > 15
$chers = array_filter($prixHT, fn ($p) => $p > 15);
// Résultat : [20, 30]

// À toi :
// 1. Extrais tous les noms de produits de ton tableau
// 2. Filtre les produits avec stock > 0

$products = [
    ["name" => "T-shirt", "price" => 29.99, "stock" => 50],
    ["name" => "Glasses", "price" => 9.99, "stock" => 15],
    ["name" => "Will to live", "price" => 9999999, "stock" => 0],
    ["name" => "AAAA", "price" => 2.99, "stock" => 5],
    ["name" => "Amogus", "price" => 299, "stock" => 500],
    ["name" => "BBBB", "price" => 1.00, "stock" => 0],
];

function getProductNamesInStock(array $products): array
{
    $result = [];

    foreach ($products as $product) {
        if ($product["stock"] > 0) {
            $result[] = $product["name"];
        }
    }

    return $result;
}

$stocked = getProductNamesInStock($products);
var_dump($stocked);
