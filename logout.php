<?php
session_start();

// Unset the session variable to log out the user
unset($_SESSION['loggedIn']);

// Redirect to the login page
header("Location: login.php");
exit();