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
    $Date       = $_POST['Date'];
    $Docnumber  = $_POST['Docnumber'];
    $SewingLine = $_POST['SewingLine'];
    $WorkOrder  = $_POST['WorkOrder'];
    $Style      = $_POST['Style'];
    $Color      = $_POST['Color'];
    $Size       = $_POST['Size'];
    $Qty        = $_POST['Qty'];
    $id         = $_POST['id'];
    // SQL query to update all fields based on the card number

    $sql = "UPDATE ework_daily_color_size SET 
                `date` = '$Date', 
                `docnumber` = '$Docnumber',
                `sewingLine` = '$SewingLine', 
                `workorder` = '$WorkOrder', 
                `style` = '$Style', 
                `color` = '$Color', 
                `size` = '$Size', 
                `qty` = '$Qty'
            WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        // Success message or redirect to a success page
        // echo '<script>alert("Record updated successfully");</script>';
        echo '<script>window.location.href = "all_daily_color_size.php";</script>';
    } else {
        // Error message
        echo "Error updating record: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
