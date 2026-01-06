<?php
// Fonction classique : charge TOUT en mémoire
function getNumbers($max) {
    $result = [];
    for ($i = 0; $i < $max; $i++) {
        $result[] = $i;
    }
    return $result; // Tableau de $max éléments en RAM
}

// Générateur : produit les valeurs à la demande
function generateNumbers($max) {
    for ($i = 0; $i < $max; $i++) {
        yield $i; // Retourne $i et "pause"
    }
}

// Utilisation identique avec foreach !
foreach (generateNumbers(1000000) as $n) {
    // $n est généré à la volée, pas tout stocké
}

// À toi : crée un générateur qui lit un fichier ligne par ligne
function readCsvRows(string $path, string $delimiter = ","): Generator
{
    $handle = fopen($path, "r");
    if ($handle === false) {
        throw new RuntimeException("Cannot open file: $path");
    }

    $headers = fgetcsv($handle, 0, $delimiter);
    if ($headers === false) {
        fclose($handle);
        return; // empty file
    }

    while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
        if ($row === [null] || $row === []) {
            continue;
        }

        // If row has fewer columns, pad; if more, trim
        $row = array_slice(array_pad($row, count($headers), null), 0, count($headers));

        yield array_combine($headers, $row);
    }

    fclose($handle);
}


foreach (readCsvRows("bigfile.csv") as $i => $row) {
    if ((int)$row["stock"] > 0) {
        echo $row["name"] . " " . $row["stock"] . "<br>";
    }
}