<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.gc_maxlifetime', 3600);
    session_start();
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    if ($_POST['username'] === 'admin' && $_POST['password'] === '1234') {
        $_SESSION['username'] = $_POST['username'];
        header('Location: dashboard.php');
        exit;
    } else {
        echo 'Invalid username or password';
    }
} else {
    echo 'Please enter a username and/or a password';   
}

?>

<form method="post" action="">
    <label for="username">Username</label>
    <input type="text" name="username" id="username">
    <label for="password">Password</label>
    <input type="password" name="password" id="password">
    <input type="submit" value="send">
</form>
