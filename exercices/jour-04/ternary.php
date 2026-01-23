<?php
$products = [
    [
        'name' => 'Amogus',
        'price' => 15,
        'stock' => 100,
        'onSale' => true
    ],
    [
        'name' => 'Sus Bear',
        'price' => 65,
        'stock' => 0,
        'onSale' => true
    ],
    [
        'name' => 'Mega Sword',
        'price' => 120,
        'stock' => 5,
        'onSale' => false
    ],
    [
        'name' => 'Tiny Hat',
        'price' => 8,
        'stock' => 0,
        'onSale' => true
    ],
    [
        'name' => 'Gaming Chair',
        'price' => 249,
        'stock' => 12,
        'onSale' => false
    ],
    [
        'name' => 'Sticker Pack',
        'price' => 3,
        'stock' => 500,
        'onSale' => true
    ],
    [
        'name' => 'Collector Figure',
        'price' => 55,
        'stock' => 2,
        'onSale' => false
    ]
];
$sale = 20;
?>
<!DOCTYPE html>
<html>
<head>
	<style>
		.stocked {font-style: green}
		.notStocked {font-style: red}
	</style>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
<?php foreach ($products as $product): ?>
<?php $statut = $product['stock'] > 0 ? 'stocked' : 'notStocked';?>
<div class="<?= $statut ?>" >
    <h3><?= $product['name'] ?>: <?= $product['onSale'] ? '🔥 PROMO' : '' ;?>
    <?= $product['onSale'] ? '<s>'.$product['price'].'</s> '.round($product['price'] * 0.8, 2) : $product['price'];?>$
    </h3>

</div>
<?php endforeach; ?>
</div>
</body>
</html>
