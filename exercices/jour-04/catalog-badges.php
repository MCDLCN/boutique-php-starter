<?php
$products = [
    [
        'name'  => 'T-shirt',
        'price' => 19.99,
        'stock' => 25,
        'image' => 'https://via.placeholder.com/300x300?text=T-shirt',
        'new' => false,
        'discount' => 70
    ],
    [
        'name'  => 'Glasses',
        'price' => 89.99,
        'stock' => 0,
        'image' => 'https://via.placeholder.com/300x300?text=Glasses',
        'new' => true,
        'discount' => 0
    ],
    [
        'name'  => 'Sneakers',
        'price' => 129.99,
        'stock' => 12,
        'image' => 'https://via.placeholder.com/300x300?text=Sneakers',
        'new' => false,
        'discount' => 30
    ],
    [
        'name'  => 'Backpack',
        'price' => 59.99,
        'stock' => 8,
        'image' => 'https://via.placeholder.com/300x300?text=Backpack',
        'new' => true,
        'discount' => 10
    ],
    [
        'name'  => 'Watch',
        'price' => 249.99,
        'stock' => 3,
        'image' => 'https://via.placeholder.com/300x300?text=Watch',
        'new' => false,
        'discount' => 0
    ],
    [
        'name'  => 'Cap',
        'price' => 14.99,
        'stock' => 0,
        'image' => 'https://via.placeholder.com/300x300?text=Cap',
        'new' => true,
        'discount' => 0
    ],
    [
        'name'  => 'Jacket',
        'price' => 179.99,
        'stock' => 6,
        'image' => 'https://via.placeholder.com/300x300?text=Jacket',
        'new' => true,
        'discount' => 20
    ],
    [
        'name'  => 'Socks',
        'price' => 5.99,
        'stock' => 3,
        'image' => 'https://via.placeholder.com/300x300?text=Socks',
        'new' => false,
        'discount' => 50
    ],
];
$inStock = 0;
$onSale = 0;
$outOfStock = 0;

foreach ($products as $product) {
    $product['stock'] > 0 ? $inStock++ : $outOfStock++;
    if ($product['discount'] > 0) {
        $onSale++;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .product { border: 1px solid #ddd; padding: 15px; }
        .outOfStock { color: red; }
        .stocked { color: green; }
    </style>
</head>
<body>
    <?= $inStock.' products in Stock. '.$onSale.' products on sale. '.$outOfStock.' products out of stock<br>';?>
    <div class="grid">
        <?php foreach ($products as $product): ?>
            <div class="product">
                <p>Image: <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>"></p>
                <p><?= $product['name'];?></p>
                <p><?= $product['discount'] > 0 ? round($product['price'] * (1 - ($product['discount'] / 100)), 2) : round($product['price'], 2); ?>$</p>
                <?php if ($product['stock'] > 0) {
                    echo '<p class="stocked"> available </p>';
                } else {
                    echo '<p class="outOfStock"> unavailable </p>';
                } ?>
                <?php if ($product['new']) {
                    echo '<span class="badge badge-pill bg-primary"> New </span>';
                }
            if ($product['discount'] > 0) {
                echo '<span class="badge badge-pill bg-primary"> On sale! </span>';
            }
            if ($product['stock'] < 5 && $product['stock'] > 0) {
                echo '<span class="badge badge-pill bg-primary"> Running out </span>';
            }
            if ($product['stock'] === 0) {
                echo '<span class="badge badge-pill bg-primary"> Out of stock </span>';
            }
            $canBuy = $product['stock'] > 0 ? ' <button type="button">Buy!</button> ' : ' <button type="button" disabled>Buy!</button> ';
            echo $canBuy;
            ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>