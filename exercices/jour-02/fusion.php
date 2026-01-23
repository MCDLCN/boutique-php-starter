<?php

$clothes = ['T-shirt', 'Jean', 'Sweatshirt'];
$accessories = ['Belt', 'Watch', 'Glasses'];
$catalog = array_merge($clothes, $accessories);
echo count($catalog);
array_unshift($catalog, "WOW I'M FIRST");
print_r($catalog);
