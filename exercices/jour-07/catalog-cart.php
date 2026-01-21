<?php
session_start();

require_once __DIR__ . "/../../app/helpers.php";

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

$stmt = $pdo->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!isset($_SESSION["totalCart"])) {
    $_SESSION["totalCart"] = 0;
}

if (isset($_POST["idCart"])) {
    $id = $_POST["idCart"];
    $quantity = ($_POST["quantity"] ?? 0);
    $currentQuantity = $_SESSION["cart"][$id] ?? 0;
    if ($quantity + $currentQuantity <= $products[$_POST["idCart"]]["stock"]) {
        if (!isset($_SESSION["cart"][$id])) {
            $_SESSION["cart"][$id] = intval($quantity);
            echo "<script>alert ('Added to cart')</script>";
        } else {
            $_SESSION["cart"][$id] = $_SESSION["cart"][$id] + $quantity;
            echo "<script>alert ('Added to cart')</script>";
        }
    } else {
        echo "<script>alert ('Not enough stock')</script>";
    }
}

if (!isset($_SESSION["totalItemsCart"])) {
    $_SESSION["totalItemsCart"] = 0;
}
if (isset($_SESSION["cart"])) {
    $_SESSION["totalItemsCart"] = 0;
    foreach ($_SESSION["cart"] as $key => $value) {
        $_SESSION["totalItemsCart"] += $value ;
    }
} else {
    $_SESSION["totalItemsCart"] = 0;
}

?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Category</th>
            <th>Created at</th>
            <th>Quantity</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product) : ?>
            <tr>
                <td><?= $product["id"] ?></td>
                <td><?= $product["name"] ?></td>
                <td><?= $product["description"] ?></td>
                <td><?= $product["price"] ?></td>
                <td><?= $product["stock"] ?></td>
                <td><?= $product["category"] ?></td>
                <td><?= $product["created_at"] ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="idCart" value="<?= $product["id"] ?>">
                        <input type ="number" name="quantity" min="1" value="1">
                        <button type="submit">Add to cart</button>
                    </form>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<br>
<?php if (isset($_SESSION["cart"])) : ?>
<a>Current cart <?= var_dump($_SESSION["cart"]) ?></a>
<br>
<?php endif; ?>
<a href="cart.php">To the cart</a>