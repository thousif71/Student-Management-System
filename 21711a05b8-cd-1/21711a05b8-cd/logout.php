<?php
session_start();
session_destroy();

// Unset the session cookie
setcookie(session_name(), '', time() - 3600, '/');
setcookie("username", "", time() - 3600, "/");

header("Location: login.php");
exit();
?>
