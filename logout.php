<?php
// Start the session
session_start();

// Destroy the session variable
unset($_SESSION['username']);

// Destroy all other session variables
session_destroy();

// Redirect to index.php
header("Location: index.php");
exit(); // Make sure to exit after the header to ensure no further code execution
?>