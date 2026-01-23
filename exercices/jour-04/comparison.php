<?php

$a = 0;
$b = '';
$c = null;
$d = false;
$e = '0';

// Compare $a avec $b, $c, $d, $e en utilisant == puis ===
// Utilise var_dump() pour chaque comparaison

var_dump($a == $b);
echo '<br>';
var_dump($a === $b);
echo '<br>';
var_dump($a == $c);
echo '<br>';
var_dump($a === $c);
echo '<br>';
var_dump($a == $d);
echo '<br>';
var_dump($a === $d);
echo '<br>';
var_dump($a == $e);
echo '<br>';
var_dump($a === $e);
echo '<br>';
