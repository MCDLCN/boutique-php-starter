<?php
$possibleStatus = ['standby','validated','shipped','delivered','canceled'];
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
	<?php foreach ($possibleStatus as $status) {
	    switch ($status) {
	        case 'standby':
	            echo '<span style="color: orange">Order in standby</span>';
	            break;
	        case 'validated':
	            echo '<span style="color: green">Order validated</span>';
	            break;
	        case 'shipped':
	            echo '<span style="color: blue">Order is being shipped</span>';
	            break;
	        case 'delivered':
	            echo '<span style="color: pink">You should have received the order</span>';
	            break;
	        case 'canceled':
	            echo '<span style="color: red">Order canceled</span>';
	            break;

	        default:
	            break;
	    }
	    echo '<br>';
	}
?>
<br>
	<?php foreach ($possibleStatus as $status) {
	    $val = match ($status) {
	        'standby' => '<span style="color: orange">Order in standby</span>',
	        'validated' => '<span style="color: green">Order validated</span>',
	        'shipped' => '<span style="color: blue">Order is being shipped</span>',
	        'delivered' => '<span style="color: pink">You should have received the order</span>',
	        'canceled' => '<span style="color: red">Order canceled</span>',
	        default => null,
	    };
	    echo $val.'<br>';
	}
?>
</body>
</html>
