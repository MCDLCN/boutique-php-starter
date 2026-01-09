<?php
require_once __DIR__ . '/../../app/helpers.php';

$errors = [];
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirmationPassword = $_POST['confirmationPassword'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!ctype_alnum($username)) {
        $errors['username'][] = 'Username can only contain letters and numbers';
    }

    if (empty($username)) {
		$errors['username'][] = 'Username is required';		
	}

    if (strlen($username) < 3) {
        $errors['username'][] = 'Username must be at least 3 characters long';
    }
    if (strlen($username) > 20) {
        $errors['username'][] = 'Username must be at most 20 characters long';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'][] = 'Email is not valid';
    }


    if (empty($email)) {
	 $errors['email'][] = 'Email is required';	
	}
    if (strlen($password) < 8) {
        $errors['password'][] = 'Password must be at least 8 characters long';
    }

    if ($password !== $confirmationPassword) {
        $errors['confirmationPassword'][] = 'Passwords do not match';
    }
}
?>

<form method="POST">
    <input type="text" name="username" value="<?= e($username) ?>">
    <?php if (isset($errors['username'])) foreach ($errors['username'] as $e) echo "<small>$e</small><br>"; ?>
    <br>
    <input type="text" name="email" value="<?= e($email) ?>">
    <?php if (isset($errors['email'])) foreach ($errors['email'] as $e) echo "<small>$e</small><br>"; ?>
    <br>
    <input type="password" name="password">
    <?php if (isset($errors['password'])) foreach ($errors['password'] as $e) echo "<small>$e</small><br>"; ?>
	<br>
    <input type="password" name="confirmationPassword">
    <?php if (isset($errors['confirmationPassword'])) foreach ($errors['confirmationPassword'] as $e) echo "<small>$e</small><br>"; ?>
    <br>
    <button type="submit">Send the stuff</button>
</form>