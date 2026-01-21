<?php
$product = [
        "name" => "T-shirt",
        "description" => "This is a shirt",
        "price" => 29.99,
        "stock" => 50,
        "category" => "Clothe",
        "brand" => "Forged in fire",
        "dateAdded" => date('m/d/Y h:i:s a', time())
];
?>
<!DOCTYPE html>
<html>
<body>

<h1><?= $product["name"] ;?></h1>
<br>
<p>Description: <?= $product["description"]?></p>
<br>
<p>Price: <?= round($product["price"] * 0.9, 2)?>$</p>
<br>
<p>There is <?= $product["stock"]?> in stock</p>
<br>
<p>Added the <?= $product["dateAdded"]?></p>
</body>
</html> 