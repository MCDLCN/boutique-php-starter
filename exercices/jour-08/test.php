<?php
$files = glob(__DIR__ . '/entities/*.php');

foreach ($files as $file) {
    require_once($file);
}

$user = new User("Cloudz", "cloud@gmail.com", "1234", strtotime("now"));
echo '1';
$category = new Category(1, "Clothes", "clothes here");
echo '2';
$product = new Product(1, "T-Shirt", "this is a shirt", 10, 1, "Clothes", 10, "image", strtotime("now"));

?>
<html>
<body>
	<h1>User</h1>
	<p>Username: <?= $user->name ?></p>
	<p>Email: <?= $user->email ?></p>
	<p>Password: <?= $user->hashedPassword ?></p>
	<h1>Category</h1>
	<p>Name: <?= $category->getName() ?></p>
	<p>Description: <?= $category->getDescription() ?></p>
	<h1>Product</h1>
	<p>Name: <?= $product->name ?></p>
	<p>Price: <?= $product->getPrice() ?></p>
	<p>Category: <?= $product->category ?></p>
</body>