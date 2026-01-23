<?php

$pdo = new PDO(
    'mysql:host=localhost;dbname=boutique;charset=utf8mb4',
    'dev',
    'dev',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
// Compter le nombre de products
$stmt = $pdo->query('SELECT COUNT(*) as total FROM products');
$total = $stmt->fetch()['total'];

// price moyen, min, max
$stmt = $pdo->query('SELECT 
    AVG(price) as moyenne,
    MIN(price) as minimum,
    MAX(price) as maximum,
    SUM(stock) as stock_total
    FROM products');
$stats = $stmt->fetch();

echo 'price moyen : ' . round($stats['moyenne'], 2) . ' €';

// Grouper par catégorie
// À toi : écris une requête qui compte les products par catégorie
// SELECT categorie, COUNT(*) as nb FROM products GROUP BY categorie
$stmt = $pdo->query('SELECT category, COUNT(*) as nb FROM products GROUP BY category');
$categories = $stmt->fetchAll();
//var_dump($categories);

echo '<ul>';
foreach ($categories as $category) {
    echo "<li>{$category['category']} : {$category['nb']}</li>";
}
echo '</ul>';
