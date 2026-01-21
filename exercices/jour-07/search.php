<?php
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

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM products WHERE name LIKE :search";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['search' => '%' . $search . '%']);
    $products = $stmt->fetchAll();
}

?>

<form action="search.php" method="get">
	<input type="text" name="search">
	<button type="submit">Search</button>
</form>

<?php if (!empty($products)) { ?>
<table>
<tr>
	<th>ID</th>
	<th>Name</th>
	<th>Price</th>
</tr>
<?php foreach ($products as $product) {
    echo '<tr>';
    echo '<td>' . $product['id'] . '</td>';
    echo '<td>' . $product['name'] . '</td>';
    echo '<td>' . $product['price'] . '$</td>';
    echo '</tr>';
} ?>
</table>
<?php } ?>
<?php if (empty($products)) { ?>
	<p>No products found</p>
<?php } ?>