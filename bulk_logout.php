<?php
session_start(); 
session_unset(); // Unset all session variables
session_destroy(); 
header("Location: index.php"); 
exit();
?>
