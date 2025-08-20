<?php
// Assuming you have established a database connection ($conn)
// ...
include 'db_connection.php';
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the card number from the URL parameter
    $id = $_GET['id'];

    // SQL query to delete the record based on the card number
    $sql = "DELETE FROM ework_target_efficiency WHERE ID = '$id'";

    if ($conn->query($sql) === TRUE) {
        // Success message or redirect to a success page
        // echo '<script>alert("Record deleted successfully");</script>';
        echo '<script>window.location.href = "all_daily_line_info.php";</script>';
    } else {
        // Error message
        echo "Error deleting record: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
