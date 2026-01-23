<?php

//helpers.php
function formatPrice(float $amount, string $currency = '€'): string
{
    return number_format($amount, 2, ',', ' ').$currency;
}

function isInStock(int $stock): bool
{
    return $stock > 0;
}
function isOnSale(int $discount): bool
{
    return $discount > 0;
}
function isNew(string $dateAdded): bool
{
    return strtotime($dateAdded) > strtotime('now - 30 day');
}
function canOrder(int $stock, int $quantity): bool
{
    return $stock > $quantity;
}
function displayBadge(string $text, string $colour): string
{
    return '<span class="badge" style="background:'.$colour.'">'.$text.'</span>';
}
function displayPrice(float $price, int $discount = 0): string
{
    return $discount > 0 ? '<s>'.$price.'</s> '.$price.'$' : $price * (1 - ($discount / 100)).'$';
}
function displayStock(int $quantity): string
{
    $colour = '';
    if ($quantity > 10) {
        $colour = 'green';
    } elseif ($quantity <= 10 && $quantity > 0) {
        $colour = 'orange';
    } else {
        $colour = 'red';
    }
    //return '<span style="color:'.$colour.';"> There is '.$quantity.' left.';
    return $colour;
}
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
