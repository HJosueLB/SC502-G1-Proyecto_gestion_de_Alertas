<?php
session_start();
//Destroy all session variables
$_SESSION = array();

//Destroy the session
session_destroy();

//Redirect to login page
header("Location: login-page.php");
exit();
?>