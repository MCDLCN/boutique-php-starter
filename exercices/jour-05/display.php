<?php
require_once __DIR__ . '/../../app/data.php';
function displayBadge(string $text, string $colour) : string {
	return '<span class="badge" style="background:'.$colour.'">'.$text.'</span>';
}
function displayPrice(float $price, int $discount = 0) : string {
	return $discount>0 ? '<s>'.$price.'</s> '.$price.'$' : $price * (1 - ($discount / 100)).'$';
}
function displayStock(int $quantity) : string {
	$colour='';
	if($quantity>10){
		$colour="green";
	}elseif ($quantity<=10 && $quantity >0) {
		$colour="orange";
	}
	else{$colour="red";}
	//return '<span style="color:'.$colour.';"> There is '.$quantity.' left.';
	return $colour;
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
	<p><?= displayPrice($product['price'],$product['discount']);?></p>
	<br>
<?php endforeach; ?>
</body>
</html>