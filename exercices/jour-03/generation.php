<?php
$products = [];

for ($i = 1; $i <= 11; $i++) {
    $products[] = [
        'name'  => 'Product '.$i,
        'price' => rand(10, 100),
        'stock' => rand(0, 50)
    ];
}

var_dump($products);
echo '<br>';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
<?php foreach ($products as $product) {
    echo '<strong>'.$product['name'].'</strong><br>Price: '.$product['price'].'$. '.$product['stock'].' in stock <br>';
}
?>
</body>
</html>