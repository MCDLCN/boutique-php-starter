<?php

class Product
{
    public function __construct(
        public readonly int $id,
        public readonly string $createdAt,
        public string $name,    // Celle-ci peut changer
        public float $price     // Celle-ci aussi
    ) {
    }
}

$product = new Product(1, '2024-01-15', 'T-shirt', 29.99);
$product->name = 'Polo';    // ✅ OK
$product->price = 34.99;    // ✅ OK
// $product->id = 999;      // ❌ ERREUR ! readonly

// À toi : crée une classe Order avec des propriétés readonly appropriées
// Lesquelles ne doivent jamais changer ? Lesquelles peuvent évoluer ?
class Order
{
    public function __construct(
        public readonly int $id,
        public readonly string $createdAt,
        public Product $product
    ) {
    }
}

$order = new Order(1, '2024-01-15', $product);
try {
    $order->id = 999;
    $order->createdAt = '2024-01-16';
} catch (Exception $e) {
    $e->getMessage();
}
