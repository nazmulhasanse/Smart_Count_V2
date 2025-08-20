<?php
session_start();

// Unset the session variable
unset($_SESSION['form_submitted']);

// Send a response back to the AJAX call (you can customize this as needed)
echo json_encode(['status' => 'success']);
?>