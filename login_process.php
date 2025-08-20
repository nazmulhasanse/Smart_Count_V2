<?php
include 'db_connection.php';
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cardnumber = $_POST["id"]; // Updated variable name
    $password = $_POST["password"];

    // Sanitize user input to prevent SQL injection (you should use prepared statements for better security)
    $cardnumber = $conn->real_escape_string($cardnumber);


// Set the timezone to Bangladesh
date_default_timezone_set('Asia/Dhaka');

// Get the current date and time
$currentDateTime = date('Y-m-d H:i:s');


    // Perform a database query to check if the user exists and the password matches
    $query = "SELECT * 
    FROM
        ework_workers where cardnumber = '$cardnumber' and Position IN (2, 3, 4)";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $hashed_password = md5($password);

        if ($hashed_password === $user['password']) {
            $_SESSION['username'] = $cardnumber;
            $_SESSION['cardnumber']=$cardnumber;
            $_SESSION['loginDatetime'] = $currentDateTime;
            header("Location: admin_dashboard.php");
        } else {
            $_SESSION['error'] = "Invalid password";
            header("Location: index.php");
        }
    } else {
        $_SESSION['error'] = "Invalid ID";
        header("Location: index.php");
    }

    // Close the database connection
    $conn->close();
}
?>