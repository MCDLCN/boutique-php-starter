<?php
$person = [
    "name" => "Alice",
    "age"  => 28,
    "city" => "Paris",
    "job"  => "Web Developer"
];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
</head>
<body>
<?php foreach ($person as $key => $value) {
    echo '<strong>'.$key.'</strong>: '.$value.'<br>';
}
?>
</body>
</html>