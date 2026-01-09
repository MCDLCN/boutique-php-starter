<?php
$name = $_GET['name'] ?? 'visitor'; 
echo "Hello $name";
echo '<br>';
$age = $_GET['age'] ?? 0;
echo "You are $age years old";