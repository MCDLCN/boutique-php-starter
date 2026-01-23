<?php

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=boutique;charset=utf8mb4',
        'dev',
        'dev',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo '✅ Succesful log in !';
} catch (PDOException $e) {
    echo '❌ Error : ' . $e->getMessage();
}

$stmt = $pdo->prepare('SELECT * FROM products');
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
var_dump($products);
echo '<br>';
echo '<table>';
echo '<tr>';
echo '<th>ID</th>';
echo '<th>Name</th>';
echo '<th>Price</th>';
echo '</tr>';
foreach ($products as $product) {
    echo '<tr>';
    echo '<td>' . $product['id'] . '</td>';
    echo '<td>' . $product['name'] . '</td>';
    echo '<td>' . $product['price'] . '</td>';
    echo '</tr>';
}
echo '</table>';
