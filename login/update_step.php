<?php 
include './ie/db_connection.php';
session_start();
if (!isset($_SESSION['cardnumber'])) {
    header("Location: index.php");
    exit;
}
// Check if idlines is set in the POST request
if (isset($_POST['idlines'])) {
    $idlines = $_POST['idlines'];
    $idlines_for_0 = $_POST['id'];

 // Define the first update query to set StepActive to 1
 $sql1 = "UPDATE `eworker_assignment` SET `StepActive` = '1' WHERE `idlines` = ?";
 // Define the second update query to set StepActive to 0
 $sql2 = "UPDATE `eworker_assignment` SET `StepActive` = '0' WHERE `idlines` = ?";

 // Prepare and bind for the first query
 if ($stmt1 = $conn->prepare($sql1)) {
     $stmt1->bind_param("i", $idlines);

     // Execute the first statement
     if ($stmt1->execute()) {
         echo "Record updated successfully for StepActive = 1";
     } else {
         echo "Error updating record for StepActive = 1: " . $stmt1->error;
     }

     // Close the first statement
     $stmt1->close();
 } else {
     echo "Error preparing statement for StepActive = 1: " . $conn->error;
 }

 // Prepare and bind for the second query
 if ($stmt2 = $conn->prepare($sql2)) {
     $stmt2->bind_param("i", $idlines_for_0);

     // Execute the second statement
     if ($stmt2->execute()) {
         echo "Record updated successfully for StepActive = 0";
     } else {
         echo "Error updating record for StepActive = 0: " . $stmt2->error;
     }

     // Close the second statement
     $stmt2->close();
 } else {
     echo "Error preparing statement for StepActive = 0: " . $conn->error;
 }
} else {
 echo "No idlines parameter provided in POST request";
}

// Close the connection
$conn->close();
?>