<?php
session_start(); 

// Unset all of the session variables.
$_SESSION = array();

// destroy the session.
session_destroy();

// Redirect to the welcome page
header("Location: preLogin.php");
exit();
?>
