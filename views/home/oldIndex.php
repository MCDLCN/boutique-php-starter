<?php
// views/products/index.php
$title = "Our products";

ob_start();
?>
<!-- views/home/index.php -->
<!DOCTYPE html>
<html>
<head>
    <title><?= e($title) ?></title>
</head>
<body>
    <h1><?= e($title) ?></h1>
    <p>Discover our products!</p>
    <a href="/catalog">See the catalog</a>
</body>
</html>
<?php
$content = ob_get_clean(); // Récupère le HTML capturé
require __DIR__ . '/../layout.php'; // Injecte dans le layout
