<?php

function formatPrice(float $amount, string $currency = '$', int  $decimals = 2): string
{
    return round($amount, $decimals).$currency;
}
echo formatPrice(99.999);
echo '<br>';
echo formatPrice(99.99999999, '€');
echo '<br>';
echo formatPrice(99.9999, '$', 0);
