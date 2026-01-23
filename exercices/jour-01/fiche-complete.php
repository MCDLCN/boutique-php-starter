<?php
$name = 'Amogus';
$desc = 'Dis amazing plushie';
$price = 2057.6;
$tax = 20;
$quantity = 8;
$taxOnPrice = $price * ($tax / 100);
$priceTaxed = $price + $taxOnPrice;
$discount = 50;
$priceTaxedDiscounted = $priceTaxed - ($priceTaxed * ($discount / 100));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your amazing plushie</title>
</head>
<body style="background-color:pink;">
	<h1><?= $name; ?></h1>
	<div style="display:flex;flex-direction: column;align-items:center;">
	<img src="amogusTrans.png" style="display:block;margin:0 auto;" alt="Amogus plushie">
	<span style="font-size:40px;display:block;margin:0 auto;"><?= $desc; ?></span>
	</div>
	<br>
	<span style="font-size:30px;">Only <?='<b>'.round($priceTaxed, 2).'</b>';?>$!!!</span>
	<br>
	<span style="font-size:10px;">Or <?='<b>'.number_format($priceTaxedDiscounted, 2, ',', ' ').'</b>'?>$ with our amazing discount</span>
</body>
</html> 
