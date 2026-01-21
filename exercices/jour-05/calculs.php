<?php

function calculateVAT(float $priceExcludingTax, float $rate): float
{
    return $priceExcludingTax * ($rate / 100);
}

function calculateIncludingTax(float $priceExcludingTax, float $rate): float
{
    return $priceExcludingTax + calculateVAT($priceExcludingTax, $rate);
}

function calculateDiscount(float $price, float $percentage): float
{
    return $price * (1 - ($percentage / 100));
}

$product = [
    "priceExcludingTax" => 100,
    "rate" => 20,
    "discount" => 10
];

echo $product['priceExcludingTax'];
echo '<br>';
echo 'VAT: '.calculateVAT($product['priceExcludingTax'], $product['rate']);
echo '<br>';
echo 'Tax included: '.calculateIncludingTax($product['priceExcludingTax'], $product['rate']);
echo '<br>';
echo 'Discount of: '.$product['discount'];
echo '<br>';
echo 'Final price: '.calculateDiscount(calculateIncludingTax($product['priceExcludingTax'], $product['rate']), $product['discount']);
