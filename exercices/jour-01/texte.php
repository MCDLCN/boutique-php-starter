<?php

$brand = 'Nike';
$model = 'Air Max';

echo "$brand $model";
echo '<br />';
echo $brand.' '.$model;
echo '<br />';
$brandModel = '%s %s';
echo sprintf($brandModel, $brand, $model);
$price = 99.99;
echo '<br />';
echo "Prix : $price €";  // Que s'affiche-t-il ?
echo '<br />';
echo 'Prix : $price €';  // Et là ?
