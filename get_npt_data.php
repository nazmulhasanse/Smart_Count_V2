<?php
error_reporting(0);


// database connection already established
include 'db_connection.php';
// ...
// if ($_SERVER["REQUEST_METHOD"] == "GET") {
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Check if sewing line is provided
if(isset($_POST['docNumber'])) {
    // Sanitize input
    $docNumber = $_POST['docNumber'];


    
    // Perform database query to retrieve work order based on sewing line
    // Replace "your_table_name" with the actual name of your database table
    $query = "SELECT * FROM `ework_sales_order` WHERE docnumber = '$docNumber' LIMIT 1;";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $WorkOrder =  $row['WorkOrder'];
    $Style     =  $row['Style'];
    $site      =  $row['site'];
    $building  =  $row['building'];
    $floor     =  $row['floor'];
    $Customer  =  $row['Customer'];
    $line      =  $row['line'];
    
        
    // Prepare response as JSON
    $response = [
        'WorkOrder' => $WorkOrder,
        'Style'     => $Style,
        'site'      => $site,
        'building'  => $building,
        'floor'     => $floor,
        'Customer'  => $Customer,
        'line'      => $line
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // If sewing line is not provided, return an error
    header('HTTP/1.1 400 Bad Request');
    echo "Docnumber is not provided";
}


?>
