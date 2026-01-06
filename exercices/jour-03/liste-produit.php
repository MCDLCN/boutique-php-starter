<?php
require_once __DIR__ . '/../../app/data.php';
?>
<!DOCTYPE html>
<html>
<body>
<?php foreach ($products as $key): ?>
    <div class="product">
        <h3><?= $key["name"] ?></h3>
        <p class="price"><?= $key["price"] ?>$</p>
        <p class="stock">Stock :<?= $key["stock"] ?></p>
    </div>
    <br>
<?php endforeach; ?>
</body>
</html> 