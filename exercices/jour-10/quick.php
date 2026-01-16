<?php
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=shop;charset=utf8mb4",
        "dev",
        "dev",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
        $e->getMessage();
}

$stmt = $pdo->prepare('ALTER TABLE products ADD category_id INT NOT NULL, ADD CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id);');
$stmt->execute();