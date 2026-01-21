<?php
declare(strict_types=1);
require_once __DIR__.'/../config/constants.php';
require_once __DIR__ . '/../../app/data.php';
require_once __DIR__ . '/../../app/helpers.php';

$page = $_GET['page'] ?? 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= WEBSITE_NAME ?></title>
</head>
<body style="background-color:pink;">
	<h1><?= $name; ?></h1>
	<div style="display:flex;flex-direction: column;align-items:center;">
	<img src="amogusTrans.png" style="display:block;margin:0 auto;" alt="Amogus plushie">
	<span style="font-size:40px;display:block;margin:0 auto;"><?= $desc; ?></span>
	</div>
	<br>
	<span style="font-size:30px;">Only <?='<b>'.round($priceTaxed, 2).CURRENCY.'</b>';?>!!!</span>
	<br>
	<span style="font-size:10px;">Or <?='<b>'.number_format(priceTaxedDiscounted($priceTaxed, $discount), 2, ",", " ").CURRENCY.'</b>';?> with our amazing discount</span>
	<br>
	<span style="font-size: 25px">There's <span style= "font-size: 60px;"><?= '<b>'.$quantity.'</b>';?></span> plushies in stock</span>
	<br>
	<form method="get">
		<input type="text" name="valueButton">
		<button type="submit">Send</button>
	</form>
	<?php $value = trim($_GET['valueButton'] ?? '');
echo $value === '' ? 'This is empty' : $value;?>
</body>
</html> 
