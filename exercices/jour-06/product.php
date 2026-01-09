<?php
$product = [
	1 => [
	"name" => "T-Shirt",	
	"price" => 10
	],
	2 => [
	"name" => "Jean",
	"price" => 20	
	],
	3 => [
	"name" => "amogus",
	"price" => 100	
	],
	4 => [
	"name" => "Cloud",
	"price" => 50	
	]
];

$id=$_GET['id'] ?? null;
echo $product[$id]['name'].' - '.$product[$id]['price'].'$';