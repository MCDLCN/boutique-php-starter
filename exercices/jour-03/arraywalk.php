<?php
$produits = [
    ['nom' => 'A', 'prix' => 10],
    ['nom' => 'B', 'prix' => 20]
];

// array_walk modifie le tableau en place
array_walk($produits, function(&$p, $key, $tva) {
    $p['prixTTC'] = $p['prix'] * (1 + $tva / 100);
}, 20); // 20 = TVA passée en 3ème argument

var_dump($produits);
// À toi : utilise array_walk pour ajouter un champ 'slug' à chaque produit
// Le slug = nom en minuscules avec tirets au lieu des espaces
array_walk($produits, function(&$p, $key){
    $p['slug'] = strtolower($p['nom']);
});
echo '<br>';
var_dump($produits);