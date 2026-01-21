<?php
$names = ["Julie", "Kelsie", "Justin", "Dominique","Tifanny"];
$counter = 1;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
<ul>
<?php foreach ($names as $name) {
    echo '<li>'.$counter.'. '.$name.'</li>';
    $counter++;
}
?>
</ul>
</body>
</html>