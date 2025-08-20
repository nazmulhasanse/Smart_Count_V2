<?php
include '../ie/db_connection.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['cardnumber'])) {
    header("Location: index.php");
    exit;
}

$cardnumber = $_SESSION['cardnumber'];

// Fetch the data from the form
$name = $_POST['name'];
$date = $_POST['date'];
$quantity = $_POST['quantity'];
$line = $_POST['line'];

// Get current time
date_default_timezone_set('Asia/Dhaka'); // Set timezone to Dhaka, Bangladesh

$current_hour = date('h'); // Get current hour in 12-hour format (01 to 12)
$current_minute = date('i'); // Get current minute (00 to 59)

// Determine AM/PM using date('A') for uppercase or date('a') for lowercase
$am_pm = date('A'); // This will return 'AM' or 'PM'

// Calculate the hour based on your logic
if ($current_minute>0) {
    $hour = $current_hour + 1; 
} else {
    $hour = $current_hour; // If 12 PM, set to 1 PM; otherwise add 1
}

// Format hour with a space
$hour = $hour . ' ' . $am_pm; 


$sql = "INSERT INTO eworker_bulk_operation (date, hour, quantity, name, cardnumber, line, time) 
        VALUES ('$date', '$hour', '$quantity', '$name', '$cardnumber', '$line', NOW())";


// After your SQL query execution
if ($conn->query($sql) === TRUE) {
    header("Location: bulk_quantity.php?message=success");
    exit();
} else {
    header("Location: bulk_quantity.php?message=error");
    exit();
}



$conn->close();

header("Location: bulk_quantity.php");
exit;
