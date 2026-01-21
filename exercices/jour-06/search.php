<?php

require_once __DIR__ . '/../../app/data.php';
require_once __DIR__ . '/../../app/helpers.php';

echo '<form method="GET" action="search.php">
    <input type="text" name="searching">
    </form>';

$searching = $_GET['searching'] ?? '';
$results = [];
foreach ($products as $product) {
    if (stripos($product['name'], $searching) !== false) {
        $results[] = $product['name'] . '<br>';
    }
}

if (empty($results)) {
    $results[] = 'Nothing found';
}

echo implode($results);
