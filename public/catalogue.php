<?php
// starter-project/public/catalogue.php
require_once __DIR__ . '/../app/data.php';
// $products est maintenant disponible
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
                <p>Image: <img src="<?= $product["image"] ?>" alt="<?= htmlspecialchars($product["name"]) ?>"></p>
                <p><?= $product["name"];?></p>
                <p><?php echo round($product["price"], 2); ?>$</p>
                <?php if ($product["stock"]>0){echo '<p class="stocked"> available </p>';}
                else {echo '<p class="outOfStock"> unavailable </p>';} ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>