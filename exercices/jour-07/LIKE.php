<?php
$search = $_GET['q'] ?? '';

// DANGER : si $search contient "%" ou "_", résultats inattendus !
// Exemple : rechercher "50%" pourrait matcher beaucoup trop de résultats

// Solution : échapper les caractères spéciaux
function escapeLike(string $str): string {
    // À toi : remplace % par \% et _ par \_
    // Indice : utilise str_replace avec des tableaux
    return str_replace(['\\', '_', '%'], ['\\\\', '\\_', '\\%'], $str);
}

$searchEscaped = escapeLike($search);
$stmt = $pdo->prepare("SELECT * FROM produits WHERE nom LIKE ?");
$stmt->execute(['%' . $searchEscaped . '%']);