<?php

$price = [29.99, 9.99, 49.99, 19.99];

sort($price);  // Tri croissant → [9.99, 19.99, 29.99, 49.99]
rsort($price); // Tri décroissant

// Pour les tableaux associatifs, utilise asort() ou ksort()
// À toi de tester la différence !

//asort() sorts by the items where ksort() sorts by key, both keep the association between key and item

// Pour un tri personnalisé, utilise usort() avec l'opérateur <=>
// Exemple : trier des produits par price


usort($price, function($a, $b) {
    return $a<=> $b;

});
var_dump($price);