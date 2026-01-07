<?php
$products = [
    [
        "name" => "Amogus",
        "price" => 15,
        "stock" => 100,
        "category" => "Plushie"
    ],
    [
        "name" => "Sus Bear",
        "price" => 65,
        "stock" => 0,
        "category" => "Plushie"
    ],
    [
        "name" => "Mega Sword",
        "price" => 120,
        "stock" => 5,
        "category" => "Weapon"
    ],
    [
        "name" => "Tiny Hat",
        "price" => 8,
        "stock" => 0,
        "category" => "Accessory"
    ],
    [
        "name" => "Gaming Chair",
        "price" => 249,
        "stock" => 12,
        "category" => "Furniture"
    ],
    [
        "name" => "Sticker Pack",
        "price" => 3,
        "stock" => 500,
        "category" => "Merch"
    ],
    [
        "name" => "Collector Figure",
        "price" => 55,
        "stock" => 2,
        "category" => "Figure"
    ],
    [
        "name" => "Pixel Mug",
        "price" => 18,
        "stock" => 30,
        "category" => "Kitchen"
    ],
    [
        "name" => "Limited Hoodie",
        "price" => 89,
        "stock" => 0,
        "category" => "Clothing"
    ],
    [
        "name" => "Desk Lamp",
        "price" => 42,
        "stock" => 9,
        "category" => "Home"
    ]
];

$count=0;
foreach ($products as $product) {
	if ($product["stock"]>0 && $product["price"]<50){
		echo $product["name"].'<br>';
		$count++;
	}
}
echo $count.' displayed items out of '.count($products);