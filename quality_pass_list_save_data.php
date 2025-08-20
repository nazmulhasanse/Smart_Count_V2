<?php
// Include your database connection script or configure it here
include 'db_connection.php';

// Check if the request is made using POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract data from the AJAX request
    $field = $_POST['field'];
    $value = $_POST['value'];
    $ework_id = $_POST['ework_id'];

    // Validate and sanitize the input data if needed

    // Update the data in the database
    $updateSql = "UPDATE eworker_operation SET $field = '$value' WHERE ework_id = '$ework_id'";
    $stmt = $conn->prepare($updateSql);
    
    if ($stmt) {
        $stmt->bind_param("si", $value, $docnumber);
        $stmt->execute();
        $stmt->close();
        echo "Data saved successfully";
    } else {
        echo "Error updating data: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    // Handle other types of requests or invalid requests
    echo "Invalid request";
}
?>