<?php
session_start();
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=boutique;charset=utf8mb4",
        "dev",
        "dev",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✅ Succesful log in !";
} catch (PDOException $e) {
    echo "❌ Error : " . $e->getMessage();
}
echo '<br>';



if (isset($_POST['idRemove'])) {
	$id = $_POST['idRemove'];		
	unset($_SESSION['cart'][$id]);
}

if (isset($_POST['emptyCart'])) {
unset($_SESSION['cart']);	
}

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
	$products= $_SESSION["cart"];
	$ids = array_keys($_SESSION['cart']);
	$placeholders = implode(',', array_fill(0, count($ids), '?'));
	$stmt = $pdo->prepare(
    "SELECT id, name, price, stock FROM products WHERE id IN ($placeholders)"
);

$stmt->execute($ids);
$productsInCart = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (isset($_POST['idUpdate'])) {
	$stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
	$stmt->execute([(int)$_POST['idUpdate']]);
	$stock = (int)$stmt->fetchColumn();
	$quantity = ($_POST["quantity"] ?? 0);
	$currentQuantity = $_SESSION['cart'][$_POST['idUpdate']];
	if ($quantity + $currentQuantity <= $stock){
		$id = $_POST['idUpdate'];	
		$quantity = $_POST['quantity'];
		$_SESSION['cart'][$id] = $quantity;
	}else{
		echo "alert ('Not enough stock')";
	}
}

//var_dump($productsInCart);
echo '<br>';
//var_dump($products);
if (!isset($_SESSION["totalItemsCart"])) {
    $_SESSION["totalItemsCart"] = 0;
}

$_SESSION["totalItemsCart"] = 0;
foreach ($_SESSION["cart"] as $key => $value) {
         $_SESSION["totalItemsCart"] += $value ;}


$_SESSION['totalCart'] = 0;


//var_dump($products);
echo '<br>';
echo 'There is '.$_SESSION["totalItemsCart"].' product(s) in your cart';
echo '<br>';
echo '<table>';
echo '<tr>';
echo '<th>Name</th>';
echo '<th>Price unit</th>';
echo '<th>quantity</th>';
echo '<th>Price total</th>';
echo '<th>Remove</th>';
echo '</tr>';
foreach ($productsInCart as $product) {
    echo '<tr>';
    echo '<td>' . $product['name'] . '</td>';
    echo '<td>' . $product['price'] . '$</td>';
    echo '<td><form method="POST">
    <input type="hidden" name="idUpdate" value='. $product["id"].'>
    <input type="number" name="quantity" min="1" value="' . $products[$product['id']] . '">
    <input type="submit" value="update quantity"></form></td>';
    echo '<td>' . $product['price']*$products[$product['id']] . '$</td>';
    echo '<td><form method="POST"><input type="hidden" name="idRemove" value='. $product["id"].'><input type="submit" value="Remove"></form></td>';
    $_SESSION['totalCart'] += $product['price']*$products[$product['id']];
    echo '</tr>';
}
echo '</table>';
echo '<br>';
echo 'Total cart: '.$_SESSION['totalCart'].'$';
echo '<br>';
echo '<form method="POST"><input type="submit" name="emptyCart" value=Empty cart></form>';
echo '<br>';

} else {
	echo 'There is no product in your cart';
}
echo '<br>';
echo '<a href="catalog-cart.php">Back to catalog</a>';