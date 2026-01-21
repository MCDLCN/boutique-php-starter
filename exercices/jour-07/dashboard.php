<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.gc_maxlifetime', 3600);
    session_start();
}
if (isset($_SESSION['username'])) {
    $user = $_SESSION['username'];
    echo "Hello $user";
} else {
    header("location: login.php");
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("location: login.php");
}

?>
<br>
<a href="?logout=1">Logout</a>