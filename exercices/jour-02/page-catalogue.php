<?php
require_once __DIR__ . '/../../app/data.php';
?>
<!DOCTYPE html>
<html>
<body>
<?php foreach ($products as $key): ?>
    <div class="product">
        <h2><?= $key["name"] ?></h2>
        <p class="prix"><?= $key["price"] ?> €</p>
        <p class="stock">Stock : <?= $key["stock"] ?></p>
    </div>
    <br>
<?php endforeach; ?>
</body>
</html> 