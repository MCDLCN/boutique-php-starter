<?php

$groceries = ['Tomato', 'A will to live', 'Banana', 'Shirt', 'Idk a fifth thing'];

echo $groceries[0];
echo '<br>';
echo $groceries[count($groceries) - 1];
echo '<br>';
echo count($groceries);
echo '<br>';

array_push($groceries, 'A reason to continue');
array_push($groceries, 'Candies :DDDD');
echo '<br>';
unset($groceries[2]);
echo '<br>';
var_dump($groceries);
