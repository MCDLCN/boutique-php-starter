<!-- views/products/index.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Your products</title>
</head>
<body>
<ul>
<?php foreach ($products as $p) :?>
<li><?= $p->getName() ?></li>
<?php endforeach; ?>
</ul>
</body>
</html>