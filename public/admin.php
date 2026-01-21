<?php
require_once __DIR__ . '/../app/helpers.php';
$files = glob(__DIR__ . '/../app/Entity/*.php');

foreach ($files as $file) {
    require_once($file);
}

// Repositories
$repoFiles = glob(__DIR__ . '/../app/Repository/*.php');
foreach ($repoFiles as $file) {
    require_once($file);
}

require_once __DIR__ .'/../config/Database.php';

$pdo = Database::getInstance();

$categoryRepo = new CategoryRepository($pdo);
$productRepo  = new ProductRepository($pdo, $categoryRepo);

$products = $productRepo->findAll();
$categories = $categoryRepo->findAll();

$action = $_POST['action'] ?? '';

if ($action === 'add') {
    var_dump($_POST);
    $productRepo->save(new Product(0, $_POST['name'], $_POST['description'], $_POST['price'], $_POST['stock'], $categoryRepo->find($_POST['category']), $_POST['discount'], $_POST['image'], date("Y-m-d", time())));
    header('Location: admin.php');
    exit;
}

if ($action === 'update') {
    $productRepo->update(new Product($_POST['idUpdate'], $_POST['name'], $_POST['description'], $_POST['price'], $_POST['stock'], $categoryRepo->find($_POST['category']), $_POST['discount'], $_POST['image'], date("Y-m-d", time())));
    header('Location: admin.php');
    exit;
}

if ($action === 'delete') {
    $productRepo->delete($_POST['idDelete']);
    header('Location: admin.php');
    exit;
}
?> 
<html>
    <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Category</th>
            <th>Discount</th>
            <th>Created at</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product) : ?>
            <form method="POST" autocomplete="off" action="admin.php">
            <tr>
                <td><?= $product->getId() ?></td>
                <input type="hidden" name="idUpdate" value="<?= $product->getId() ?>">
                <td><input name="name" value="<?= $product->getName() ?>"></td>
                <td><input name="description" value="<?= $product->getDescription() ?>"></td>
                <td><input type="number" name="price" value="<?= $product->getPrice() ?>"></td>
                <td><input name="stock" value="<?= $product->getStock() ?>"></td>
                <td>
                    <select name="category">
                        <?php foreach ($categories as $category): ?>
                            <option
                                value="<?= $category->getId() ?>"
                                <?= $category->getId() === $product->getCategory()->getId() ? 'selected=' : '' ?>
                            >
                                <?= $category->getName() ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><input type="number" name="discount" value="<?= $product->getDiscount() ?>"></td>
                <td><input name="image" value="<?= $product->getImage() ?>"></td>
                <td><?= $product->getDateAdded() ?></td>
                <td style='display: flex; gap: 8px;'>
                    <input type="hidden" name="action" value="update">
                    <button type="submit">update</button>
                
            </form>
                <form method="POST" action="admin.php" onsubmit="return confirm('Delete this item?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="idDelete" value="<?= (int)$product->getId() ?>">
                    <button type="submit" title="Delete">Delete</button>
                </form> 
                </td>       
            </tr>
            
        <?php endforeach; ?>
    </tbody>
</table>
<br>
<form method="POST" action="admin.php">
    <input type="hidden" name="action" value="add">
    <input name="name">
    <input name="description">
    <input name="price">
    <input name="stock">
    <select name="category">
        <?php foreach ($categories as $category) : ?>
        <option value="<?= $category->getId() ?>"><?= $category->getName()?></option>
        <?php endforeach; ?>
    </select>
    <input name="discount">
    <input name="image">
    <button type="submit">Add</button>
</form>
</html>