<?php
$prix = [10, 20, 30];

// SANS référence : $p est une copie, le tableau n'est pas modifié
foreach ($prix as $p) {
    $p *= 1.2; // Ne modifie PAS le tableau !
}

// AVEC référence (&) : $p pointe vers l'élément original
// foreach ($prix as &$p) {
//     $p *= 1.2; // Modifie directement le tableau
// }
//unset($p); // IMPORTANT : libérer la référence !

// À toi : applique une remise de 15% à tous tes produits
// Attention au unset() après la boucle !

foreach ($prix as &$p) {
    $p =round($p * 0.85, 2); // Modifie directement le tableau
}
unset($p); 

var_dump($prix);