<?php
// starter-project/public/catalogue.php
require_once __DIR__ . '/../app/data.php';
require_once __DIR__ . '/../app/helpers.php';
$inStock = 0;
$onSale = 0;
$outOfStock = 0;

foreach ($products as $product){
    $product["stock"]>0 ? $inStock++ : $outOfStock++;
    if ($product["discount"]>0)  $onSale++;
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
                <p>Image: <img src="<?= $product["image"] ?>" alt="<?= htmlspecialchars($product["name"]) ?>"></p>
                <p><?= $product["name"];?></p>
                <p><?= isOnSale($product["discount"]) ? formatPrice(calculateDiscounted($product["price"],$product["discount"])) : formatPrice($product["price"]); ?></p>
                <?php if (isNew($product['dateAdded'])){
                echo '<span class="badge badge-pill bg-primary"> New </span>';}

                if (isOnSale($product["discount"])){
                echo displayBadge("on sale!", "pink");}

                [$text, $colour] = displayStock($product["stock"]);
                echo displayBadge($text, $colour);

                echo '<br>';

                echo $product["stock"]>0 ? ' <button type="button">Buy!</button> ' : ' <button type="button" disabled>Buy!</button> ';
                ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>