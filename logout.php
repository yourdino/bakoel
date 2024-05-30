<?php
session_start();
$_SESSION = null;

//session_unset();
session_destroy();
header('Location: login.php');
?>