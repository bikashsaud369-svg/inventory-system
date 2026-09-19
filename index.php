<?php

session_start();

if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit();
}

header("Location: login.php");
exit();

?>