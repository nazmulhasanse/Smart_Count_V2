<?php
// Assuming you have established a database connection ($conn)
// ...
include 'db_connection.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch values from the form
    $cardnumber = $_POST['cardnumber'];
    // echo $cardnumber;
    // exit();
    $name = $_POST['Name'];
    $sex = $_POST['Sex'];
    $phonenumber = $_POST['Phonenumber'];
    $joindate = $_POST['JoinDate'];
    $dob = $_POST['DateofBirth'];
    $position = $_POST['Position'];
    $workstation = $_POST['Department'];

    // SQL query to update all fields based on the card number

    $sql = "UPDATE ework_workers SET 
                name = '$name', 
                sex = '$sex', 
                phonenumber = '$phonenumber', 
                joindate = '$joindate', 
                DateofBirth = '$dob', 
                position = '$position', 
                Department = '$workstation'
            WHERE cardnumber = '$cardnumber'";

    if ($conn->query($sql) === TRUE) {
        // Success message or redirect to a success page
        // echo '<script>alert("Record updated successfully");</script>';
        echo '<script>window.location.href = "all_workers.php";</script>';
    } else {
        // Error message
        echo "Error updating record: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
