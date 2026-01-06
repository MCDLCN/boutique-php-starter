<?php
$cart = ["T-shirt", "Jean"];

// Ajouter à la fin
$cart[] = "Casquette";
// ou
array_push($cart, "Chaussettes");

// Modifier
$cart[0] = "Polo";

// Supprimer
unset($cart[1]);

// Compter
echo count($cart); // 3
