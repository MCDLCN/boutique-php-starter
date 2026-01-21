<?php

$ages = [5,20,35,70,-97];
$stage = "";
foreach ($ages as $age) {
    if ($age < 18 && $age >= 0) {
        $stage = "minor";
    } elseif ($age >= 18 && $age <= 25) {
        $stage = "Young adult";
    } elseif ($age >= 26 && $age <= 64) {
        $stage = "Adult";
    } elseif ($age > 64) {
        $stage = "Senior";
    } else {
        $stage = "You're not real";
    }
    echo $stage;
    echo '<br>';
}
