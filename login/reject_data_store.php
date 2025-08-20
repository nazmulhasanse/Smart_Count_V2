<?php
session_start();
header('Content-Type: application/json');
include './ie/db_connection.php';
date_default_timezone_set('Asia/Dhaka');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['ework_id']) && isset($_POST['last_row_update_qty'])) {
        
        if(isset($_POST['color']) && isset($_POST['size'])) {

            $color = $_POST['color'];
            $size = $_POST['size'];
            $ework_id = $_POST['ework_id'];
            $last_row_update_qty = $_POST['last_row_update_qty'];
            $date = date('Y-m-d H:i:s');
    
            try {
                $sql = "UPDATE eworker_operation SET Qty = '$last_row_update_qty', updated_time = '$date', color='$color', size='$size' WHERE ework_id = '$ework_id'";
        
                 // use query() method for MySQLi
              // use query() method for MySQLi
              if ($conn->query($sql) === TRUE) {
                // If the operation was successful
                $response['success'] = true;
                $response['message'] = 'New record created successfully';
            } else {
                // If the operation failed
                $response['success'] = false;
                $response['message'] = 'Error creating new record';
            }
            
            // Convert the response array to JSON format
            $json_response = json_encode($response);
            $_SESSION['form_submitted'] = true;
            // Send the JSON response back to the client
            echo $json_response;
            } catch (PDOException $e) {
                echo $sql . "<br>" . $e->getMessage();
            }
        
            $conn->close(); 
        }else{
            
            $ework_id = $_POST['ework_id'];
            $last_row_update_qty = $_POST['last_row_update_qty'];
            $date = date('Y-m-d H:i:s');

            try {
                $sql = "UPDATE eworker_operation SET Qty = '$last_row_update_qty', updated_time = '$date' WHERE ework_id = '$ework_id'";
        
                // use query() method for MySQLi
                if ($conn->query($sql) === TRUE) {
                    echo "Qty record update successfully";
                    $_SESSION['form_submitted'] = true;
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } catch (PDOException $e) {
                echo $sql . "<br>" . $e->getMessage();
            }
        
            $conn->close(); 
        }
    }elseif(isset($_POST['ework_id']) && isset($_POST['delete']) ){

        $ework_id = $_POST['ework_id'];
        $date = date('Y-m-d H:i:s');

        try {
            $sql = "UPDATE eworker_operation SET status = 'D', deleted_time = '$date' WHERE ework_id = '$ework_id'";
    
            // use query() method for MySQLi
            if ($conn->query($sql) === TRUE) {
                echo '<script>';
                echo 'alert("Record has been deleted successfully!");';
                echo 'window.location.href = "status.php";'; // Redirect after the alert
                echo '</script>';
                unset($_SESSION['form_submitted']);
                die();
                
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    
        $conn->close(); 


    }elseif (isset($_POST['dataArray'])) {

	$encodedData = $_POST['dataArray'];
	$decodedData = urldecode($encodedData);
	$data = json_decode($decodedData, true);
             if(empty($data)){
            echo "error";
            exit;
            }
            
        $name        = $_POST['Name'];
        $WorkOrder   = $_POST['WorkOrder'];
        $Step        = $_POST['Step'];
        $Style       = $_POST['Style'];
        $CardNo      = $_POST['CardNo'];
        $docnumber   = $_POST['docnumber'];
        $SewingLine  = $_POST['SewingLine'];
        $qty         = $_POST['qty'];
        $qty_type    = $_POST['qty_type'];
        $status      = "R";
        $date        = date('Y-m-d H:i:s'); // 'Y' should be uppercase for the full year

        try {
            

            // use query() method for MySQLi
            if (1 == 1) {
                if($qty_type == 'reject' && isset($_POST['dataArray'])) {
             
                 

                // Check if decoding was successful
                if ($data !== null) {
                    // Process each item in the array
                    foreach ($data as $item) {
                        
                        $RejectReason = $item['RejectReason'];
                        $Remarks      = $item['Remarks'];
                        
                        $sql_defect = "INSERT INTO eworker_operation (cardnumber, WorkOrder, Style, StepNumber, PartCode, ReworkReason, color, size, Qty, DateTime, Status, qty_type, SewingLine, docnumber,RejectReason, Remarks)
                                VALUES ('$CardNo', '$WorkOrder', '$Style', '$Step', '', '', '', '', '$qty', '$date', '$status','$qty_type','$SewingLine','$docnumber','$RejectReason','$Remarks')";
                 
                        if ($conn->query($sql_defect) === TRUE) {
                            $response = array(
                                "success" => true,
                                "ework_id" => $conn->insert_id,
                                "message" => "New record created successfully"
                            );
                        } else {
                            // If the operation failed
                            $response['success'] = false;
                            $response['message'] = 'Error creating new record';
                        }
                        
                        // Convert the response array to JSON format
                     
                    }
                    $json_response = json_encode($response);
                        
                    // Send the JSON response back to the client
                    echo $json_response;
                } else {
                    // Handle decoding error
                    echo "Error decoding JSO";
                }
                    
                }
                // $_SESSION['form_submitted'] = true;
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
        
    }
}

?>