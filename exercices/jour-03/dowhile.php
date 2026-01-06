<?php
$i = 0;

// while classique : vérifie AVANT
while ($i > 0) {
    echo $i; // N'affiche rien car $i = 0
}

// do...while : exécute PUIS vérifie
do {
    echo $i; // Affiche 0, puis s'arrête
} while ($i > 0);

// À toi : crée un générateur de code promo aléatoire
// qui génère jusqu'à trouver un code qui n'existe pas déjà
echo '<br>';
$promos = [];
do {
    $i = rand(0, 100);

    if (!in_array($i, $promos)) {
        $promos[] = $i;
        echo $i . "<br>";
    } else {
        break;
    }
} while (true);

echo count($promos).' number generated';