<?php

$age = 25;

// Au lieu de if/elseif/else :
$categorie = match(true) {
    $age < 18 => 'mineur',
    $age < 65 => 'adulte',
    default => 'senior',
};

// À toi : crée un système de badges stock
// stock = 0 → 'rupture'
// stock < 5 → 'dernieres_pieces'
// stock < 20 → 'stock_faible'
// default → 'en_stock'

$stocks = [0, 3, 15, 100];

foreach ($stocks as $stock) {
    $available = match(true) {
        $stock === 0 => 'out of stock',
        $stock < 5 => 'lasts',
        $stock < 20 => 'low stocks',
        default => "there's hella stock"
    };
    echo $available.'<br>';
}

usort($stocks, fn ($b, $a) => $a <=> $b);

foreach ($stocks as $stock) {
    $available = match(true) {
        $stock === 0 => 'out of stock',
        $stock < 5 => 'lasts',
        $stock < 20 => 'low stocks',
        default => "there's hella stock"
    };
    echo $available.'<br>';
}
