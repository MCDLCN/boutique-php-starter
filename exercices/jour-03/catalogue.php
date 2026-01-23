<?php
$products = [
    [
        'name'  => 'T-shirt',
        'price' => 19.99,
        'stock' => 25,
        'image' => 'https://via.placeholder.com/300x300?text=T-shirt'
    ],
    [
        'name'  => 'Glasses',
        'price' => 89.99,
        'stock' => 0,
        'image' => 'https://via.placeholder.com/300x300?text=Glasses'
    ],
    [
        'name'  => 'Sneakers',
        'price' => 129.99,
        'stock' => 12,
        'image' => 'https://via.placeholder.com/300x300?text=Sneakers'
    ],
    [
        'name'  => 'Backpack',
        'price' => 59.99,
        'stock' => 8,
        'image' => 'https://via.placeholder.com/300x300?text=Backpack'
    ],
    [
        'name'  => 'Watch',
        'price' => 249.99,
        'stock' => 3,
        'image' => 'https://via.placeholder.com/300x300?text=Watch'
    ],
    [
        'name'  => 'Cap',
        'price' => 14.99,
        'stock' => 0,
        'image' => 'https://via.placeholder.com/300x300?text=Cap'
    ],
    [
        'name'  => 'Jacket',
        'price' => 179.99,
        'stock' => 6,
        'image' => 'https://via.placeholder.com/300x300?text=Jacket'
    ],
    [
        'name'  => 'Socks',
        'price' => 5.99,
        'stock' => 40,
        'image' => 'https://via.placeholder.com/300x300?text=Socks'
    ],
];
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        .grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .product { border: 1px solid #ddd; padding: 15px; }
        .outOfStock { color: red; }
        .stocked { color: green; }
    </style>
</head>
<body>
    <div class="grid">
        <?php foreach ($products as $product): ?>
            <div class="product">
                <p>Image: <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>"></p>
                <p><?= $product['name'];?></p>
                <p><?php echo round($product['price'], 2); ?>$</p>
                <?php if ($product['stock'] > 0) {
                    echo '<p class="stocked"> available </p>';
                } else {
                    echo '<p class="outOfStock"> unavailable </p>';
                } ?>
            </div>
        <?php endforeach; ?>
    </div>
<p><?php echo count($products); ?> produits affichés</p>
</body>
</html>