<?php
//page.php
require_once "helpers.php";
require_once __DIR__ . '/../../app/data.php';

$price = formatPrice(1234.5);

foreach ($products as $product) {
    echo $product['name'];
    echo '<br>';
    var_dump(isInStock($product['stock']));
    echo '<br>';
    var_dump(isOnsAle($product['discount']));
    echo '<br>';
    var_dump(isNew($product['dateAdded']));
    echo '<br>';
    var_dump(canOrder($product['stock'], 1));
    echo '<br>';
    var_dump(canOrder($product['stock'], 100));
    echo '<br>';
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
<?php foreach ($products as $product): ?>
	<p><?= $product['name'];?><p> 
	<p><?= displayBadge($product['stock'], displayStock($product['stock']));?></p> 
	<p><?= displayPrice($product['price'], $product['discount']);?></p>
	<br>
<?php endforeach; ?>
</body>
</html>