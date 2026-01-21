<?php

$base = [1, 2, 3];
$extra = [4, 5, 6];

// Fusion avec spread
$tous = [...$base, ...$extra]; // [1, 2, 3, 4, 5, 6]

// On peut aussi ajouter des éléments
$complet = [0, ...$base, 10]; // [0, 1, 2, 3, 10]

// À toi : fusionne $nouveautes et $promos en un seul tableau $miseEnAvant

$news = ["This", "is", "a", "dummy", "list"];
$promos = ["wow", "only", "50%", "off"];

$forward = [...$news, ...$promos];

var_dump($forward);
