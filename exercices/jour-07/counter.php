<?php
ini_set('session.gc_maxlifetime', 3600);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}


$_SESSION["visits"] = isset($_SESSION["visits"]) ? $_SESSION["visits"] + 1 : 0;

if (isset($_GET["reset"])) {
 $_SESSION["visits"] = 0;
 header("Location: counter.php");
 exit();
}
echo 'You visited '.$_SESSION["visits"].' times';

?>
<br>

<a href="?reset=1">Reset</a>
<a href="?kill=1">Kill</a>
<?php

if (isset($_GET["kill"])) {
	session_destroy();
	exit();
}
