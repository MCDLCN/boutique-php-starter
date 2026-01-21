<?php
$name = "Amogus";
$price = 100;
$stock = 8;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $name; ?></title>
</head>
<body>
    <h1><?= $name; ?></h1>
    <p><?= $price.'$';?></p>
    <?php if ($stock > 0):?> 
	<span>Available</span>
    <?php else: ?> 
	<span>Not available</span>
    <?php endif ?>
</body>
</html>
