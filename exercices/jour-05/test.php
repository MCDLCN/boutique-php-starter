<?php

// Fonction classique
function double($n)
{
    return $n * 2;
}

// Fonction fléchée
$ddouble = fn ($n) => $n * 3;
echo $ddouble(5);
echo double(5); // 10
