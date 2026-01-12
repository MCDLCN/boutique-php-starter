<?php
require_once __DIR__ . "/../../app/helpers.php";

//var_dump($_SERVER["REQUEST_METHOD"]);

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

// CREATE
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "add") {
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock, category) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_POST["nameAdd"], $_POST["descriptionAdd"], $_POST["priceAdd"], $_POST["stockAdd"], $_POST["categoryAdd"]]);
    header("Location: admin-products.php");
    exit;
}

// DELETE
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? '') === "delete") {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_POST["idDelete"]]);
    header("Location: admin-products.php");
    exit;
}

// UPDATE
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "update") {
    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, category = ? WHERE id = ?");
    $stmt->execute([$_POST["nameUpdate"], $_POST["descriptionUpdate"], $_POST["priceUpdate"], $_POST["stockUpdate"], $_POST["categoryUpdate"], $_POST["idUpdate"]]);
    header("Location: admin-products.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <th>Action</th>
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
                        <input type="hidden" name="idUpdate" value='<?= $product["id"] ?>'>
                        <input type="hidden" name="action" value="update">
                        <label for="name">Name</label>
                        <input type="text" name="nameUpdate" value='<?= e($product["name"]) ?>'>
                        <label for="price">Price</label>
                        <input type="number" name="priceUpdate" step="0.01" min="0" value='<?= e($product["price"]) ?>'>
                        <label for="stock">Stock</label>
                        <input type="number" name="stockUpdate" value='<?= e($product["stock"]) ?>'>
                        <label for="description">Description</label>
                        <input type="text" name="descriptionUpdate" value='<?= e($product["description"]) ?>'>
                        <label for="category">Category</label>
                        <input type="text" name="categoryUpdate" value='<?= e($product["category"]) ?>'>
                        <button type="submit">Update</button>
                    </form>
                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                        <input type="hidden" name="idDelete" value="<?= $product["id"] ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<form method="POST">
    <input type="hidden" name="action" value="add">
    <label for="name">Name</label>
    <input type="text" name="nameAdd">
    <label for="price">Price</label>
    <input type="number" step="0.01" min="0" name="priceAdd">
    <label for="stock">Stock</label>
    <input type="number" name="stockAdd">
    <label for="description">Description</label>
    <input type="text" name="descriptionAdd">
    <label for="category">Category</label>
    <input type="text" name="categoryAdd">
    <button type="submit">Add</button>
</form>