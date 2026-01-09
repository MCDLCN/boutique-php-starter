<?php
require_once __DIR__ .'/../../app/helpers.php';
echo '<form method="POST" action="result.php">
    <input type="text" name="name">
    <input type="text" name="email">
    <input type="text" name="message">
    <button type="submit">Send the stuff</button>
</form>';

$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

$errors = [];
$error = '';

if (empty($email)) {
    $errors[] = "Email required";
}elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	$errors[] = "Invalid email";
}



if (empty($message)) {
	$errors[] = "Message required";
} elseif (strlen($message) < 10) {
	$errors[] = "Message too short";
}

if (empty($name)) {
	$errors[] = "Name required";
}



foreach ($errors as $error) {
	echo $error . '<br>';
}

if (empty($errors)) {
	echo e($name) . '<br>';
	echo e($email) . '<br>';
	echo e($message) . '<br>';
}