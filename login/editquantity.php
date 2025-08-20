<?php
session_start();
// header('Content-Type: application/json');
include './ie/db_connection.php';
date_default_timezone_set('Asia/Dhaka');
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['EntryType']) && $_POST['EntryType'] == 'Delete') {
        $stepID = $_POST['stepID'];
    
        // Check if the record with the given ID exists
        $sql_check_existence = "SELECT * FROM `ework_defect` WHERE `ID` = '$stepID'";
        $result_check_existence = $conn->query($sql_check_existence);
        
        $response = array();
        
        if ($result_check_existence->num_rows > 0) {

            $ework_id = $result_check_existence->fetch_assoc()['OperationID'];
            // Record exists, perform the update
            $sql_update = "UPDATE `eworker_operation`
                                 SET 
                                     `Status` = 'D'
                                 WHERE `ework_id` = '$ework_id'";
            $result_sql_update = $conn->query($sql_update);

            $sql_update_defect = "UPDATE `ework_defect`
                                 SET 
                                     `Status` = 'D'
                                 WHERE `ID` = '$stepID'";
        
            if ($conn->query($sql_update_defect) === TRUE) {
                $response["success"] = true;
                $response["message"] = "Record updated successfully";
            } else {
                // If the operation failed
                $response["success"] = false;
                $response["message"] = "Error updating record: " . $conn->error;
            }
        } else {
            // Record does not exist
            $response["success"] = false;
            $response["message"] = "Record with ID $stepID does not exist";
        }
        
        // Return the response as JSON
        echo json_encode($response);
        exit; 
    }else{
    // Assuming you have received the updated values from the AJAX request

    $reworkReason =   $_POST['reworkReason'];
    $StepNumber =  $_POST['stepNumber'];
    $partCode = $_POST['partCode'];
    $defectQty = $_POST['defectQty'];
    $stepOperationID = $_POST['stepOperationID'];
    $stepID = $_POST['stepID'];
    
    // Check if the record with the given ID exists
    $sql_check_existence = "SELECT * FROM `ework_defect` WHERE `ID` = '$stepID'";
    $result_check_existence = $conn->query($sql_check_existence);
  
    $response = array();
    
    if ($result_check_existence->num_rows > 0) {
        // Record exists, perform the update
        $sql_update_defect = "UPDATE `ework_defect`
                             SET 
                                 `StepNumber` = '$StepNumber',
                                 `PartName` = '$partCode',
                                 `DefectReason` = '$reworkReason',
                                 `No_of_Defect` = '$defectQty',
                                 `Status` = 'A'
                             WHERE `ID` = '$stepID'";
    
        if ($conn->query($sql_update_defect) === TRUE) {
            $response["success"] = true;
            $response["message"] = "Record updated successfully";
        } else {
            // If the operation failed
            $response["success"] = false;
            $response["message"] = "Error updating record: " . $conn->error;
        }
    } else {
        // Record does not exist
        $response["success"] = false;
        $response["message"] = "Record with ID $stepID does not exist";
    }
    
    // Return the response as JSON
    echo json_encode($response);
    exit; 
}
}