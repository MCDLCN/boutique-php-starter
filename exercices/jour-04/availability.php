<?php
// $stock=0;
// $active=false;
// $notInSale="2024-12-31";
// $currentlyInSale="2026-12-31";

$product1 = [
    "stock"  => 0,                       
    "active" => true,
    "promo"  => "2024-05-01"
];

$product2 = [
    "stock"  => 15,
    "active" => false,
    "promo"  => "2026-03-15"
];

$product3 = [
    "stock"  => 8,
    "active" => true,
    "promo"  =>"2026-12-31"
];

$product4 = [
    "stock"  => 20,
    "active" => true,
    "promo"  => "2025-01-10"
];

$products=[$product1,$product2,$product3,$product4];

foreach ($products as $product) {
	if ($product["stock"]>0 && $product["active"]===true) {
		echo "product is available <br>";
	}else{echo 'product is unavailable <br>'; continue;}
	if (strtotime($product["promo"]) > strtotime("now")) {
		echo 'and on sale! <br>';
	} else {echo 'but not on sale <br>';}
}