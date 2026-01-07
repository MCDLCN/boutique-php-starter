<?php
// Comparaisons simples
echo 1 <=> 2; // -1 (1 < 2)
echo 2 <=> 2; // 0 (égaux)
echo 3 <=> 2; // 1 (3 > 2)

// Utile pour usort()
$produits = [
    ['nom' => 'B', 'prix' => 30],
    ['nom' => 'A', 'prix' => 10],
    ['nom' => 'C', 'prix' => 20],
];

usort($produits, fn($a, $b) => $a['prix'] <=> $b['prix']);
// Maintenant triés par prix croissant

// À toi : trie par prix DÉCROISSANT (inverse $a et $b)
// Puis : trie par nom alphabétique

usort($produits, fn($b, $a) => $a['prix'] <=> $b['prix']);
var_dump($produits);
echo '<br>';
usort($produits, fn($a, $b) => $a['nom'] <=> $b['nom']);
var_dump($produits);