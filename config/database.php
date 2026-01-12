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

require_once __DIR__ . '/../app/data.php';
$stmt = $pdo->prepare("
CREATE DATABASE IF NOT EXISTS shop;
GRANT ALL PRIVILEGES ON shop.* TO 'dev'@'localhost';
FLUSH PRIVILEGES;
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;"
);
$stmt->execute();

$stmt = $pdo->prepare("

USE shop;

CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    category VARCHAR(100),
    discount INT DEFAULT 0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);");

$stmt->execute();

$stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock, category, discount, image) VALUES (?, ?, ?, ?, ?, ?, ?)");

foreach ($products as $product) {
    $stmt->execute([$product['name'], $product['description'], $product['price'], $product['stock'], $product['category'], $product['discount'], $product['image']]);
}
