<?php

$priceExcludingTax = 100;
$vat = 20;
$quantity = 3;

echo 'Montant TVA : '.$vat."% \n";
echo '<br />';
$prixUnitaire = $priceExcludingTax + $priceExcludingTax * ($vat / 100);
echo 'Prix TTC unitaire : '.$prixUnitaire.'$';
echo '<br />';
$total = $quantity * $prixUnitaire;
echo 'Prix total : '.$total.'$';
