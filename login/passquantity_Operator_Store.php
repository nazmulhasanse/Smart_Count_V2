<?php
session_start();
// header('Content-Type: application/json');
include './ie/db_connection.php';
date_default_timezone_set('Asia/Dhaka');
$cardnumber = $_POST['CardNo'];
$WorkOrder = $_POST['WorkOrder'];
$sql_qty = "SELECT ework_id, color, size, end_datetime
            FROM eworker_operation
            WHERE cardnumber = '$cardnumber' AND WorkOrder = '$WorkOrder' AND Status ='A' AND qty_type = 'pass'
            ORDER BY ework_id DESC
            LIMIT 1;";
$result_qty = $conn->query($sql_qty);

if (!$result_qty) {
    // Handle query error
    echo "Error: " . $conn->error;
} else {
    if ($result_qty->num_rows > 0) {
        while ($row_qty = $result_qty->fetch_assoc()) {
            $data_qty = $row_qty;
            if (empty($data_qty['end_datetime'])) {
                
                $ework_id = $_POST['ework_id'];
                $date = date('Y-m-d H:i:s');
            
        
                try {
                    $sql = "UPDATE eworker_operation SET  end_datetime = '$date' WHERE ework_id = '$ework_id' ";
        
                    if ($conn->query($sql) === TRUE) {
                       
                        $response = array(
                            "success" => true,
                            "message" => "New record created successfully"
                        );
                    } else {
                        // If the operation failed
                        $response['success'] = false;
                        $response['message'] = 'Error creating new record';
                    }
                    
                    // Convert the response array to JSON format
                 
                
                $json_response = json_encode($response);
                    
                // Send the JSON response back to the client
                echo $json_response;
                        
                  
                } catch (PDOException $e) {
                    echo $sql . "<br>" . $e->getMessage();
                }
        
                $conn->close(); 
            }else{
                if (isset($_POST['color'])) {

                    $name = $_POST['Name'];
                    $WorkOrder = $_POST['WorkOrder'];
                    $Step = $_POST['Step'];
                    $Style = $_POST['Style'];
                    $CardNo = $_POST['CardNo'];
                    $docnumber = $_POST['docnumber'];
                    $SewingLine = $_POST['SewingLine'];
                    $color = $_POST['color'];
                    $size = $_POST['size'];
                    $qty = $_POST['qty'];
                    $qty_type = $_POST['qty_type'];
                    $status = "A";
                    $date = date('Y-m-d H:i:s');; // 'Y' should be uppercase for the full year
        
                    try {
        
                        $daly_color_size_sql = "SELECT SUM(qty) as qty from ework_daily_color_size as dc WHERE dc.color = '$color' and dc.size = '$size' and dc.docnumber = '$docnumber'";
        
                        $result_qty = $conn->query($daly_color_size_sql);
                        while($row_qty = $result_qty->fetch_assoc()) {
                            $data_qty = $row_qty;
                           }
        
                        $daly_color_size_op_sql = "SELECT sum(op.Qty) as opqty FROM eworker_operation as op where op.color = '$color' and op.size ='$size' and op.docnumber ='$docnumber'";
                   
                        $result_op_qty = $conn->query($daly_color_size_op_sql);
                        while($row_op_qty = $result_op_qty->fetch_assoc()) {
                            $data_op_qty = $row_op_qty;
                           }
                    if ( $data_op_qty >= $data_qty) {
                        $response['success'] = false;
                        $response['message'] = 'This color & size output Qty cant be more than input Qty';
                         // Convert the response array to JSON format
                         $json_response = json_encode($response);
                         $_SESSION['form_submitted'] = true;
                         // Send the JSON response back to the client
                         echo $json_response;
                    }else{
                         
                        $sql = "INSERT INTO eworker_operation (cardnumber, WorkOrder, Style, StepNumber, PartCode, ReworkReason, color, size, Qty, DateTime, Status, qty_type, SewingLine,docnumber)
                        VALUES ('$CardNo', '$WorkOrder', '$Style', '$Step', '', '', '$color', '$size', '$qty', '$date', '$status','$qty_type','$SewingLine','$docnumber')";
        
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
                    }
                    } catch (PDOException $e) {
                        echo $sql . "<br>" . $e->getMessage();
                    }
        
                }else{
                    $name = $_POST['Name'];
                    $WorkOrder = $_POST['WorkOrder'];
                    $Step = $_POST['Step'];
                    $Style = $_POST['Style'];
                    $CardNo = $_POST['CardNo'];
                    $docnumber = $_POST['docnumber'];
                    $SewingLine = $_POST['SewingLine'];
                    $qty = $_POST['qty'];
                    $qty_type = $_POST['qty_type'];
                    $status = "A";
                    $date = date('Y-m-d H:i:s'); // 'Y' should be uppercase for the full year
        
                    try {
                        $sql = "INSERT INTO eworker_operation (cardnumber, WorkOrder, Style, StepNumber, PartCode, ReworkReason, color, size, Qty, DateTime, Status, qty_type, SewingLine, docnumber)
                        VALUES ('$CardNo', '$WorkOrder', '$Style', '$Step', '', '', '', '', '$qty', '$date', '$status','$qty_type','$SewingLine','$docnumber')";
        
                        // use query() method for MySQLi
                        if ($conn->query($sql) === TRUE) {
                        
                                $response = array(
                                    "success" => true,
                                    "message" => "New record created successfully"
                                );
                            } else {
                                // If the operation failed
                                $response['success'] = false;
                                $response['message'] = 'Error creating new record';
                            }
                            
                            // Convert the response array to JSON format
                        
                        
                        $json_response = json_encode($response);
                            
                        // Send the JSON response back to the client
                        echo $json_response;
                            $_SESSION['form_submitted'] = true;
                    
                    } catch (PDOException $e) {
                        echo $sql . "<br>" . $e->getMessage();
                    }
                }
            }
        }
    } else {
        if (isset($_POST['color'])) {

            $name = $_POST['Name'];
            $WorkOrder = $_POST['WorkOrder'];
            $Step = $_POST['Step'];
            $Style = $_POST['Style'];
            $CardNo = $_POST['CardNo'];
            $docnumber = $_POST['docnumber'];
            $SewingLine = $_POST['SewingLine'];
            $color = $_POST['color'];
            $size = $_POST['size'];
            $qty = $_POST['qty'];
            $qty_type = $_POST['qty_type'];
            $status = "A";
            $date = date('Y-m-d H:i:s');; // 'Y' should be uppercase for the full year

            try {

                $daly_color_size_sql = "SELECT SUM(qty) as qty from ework_daily_color_size as dc WHERE dc.color = '$color' and dc.size = '$size' and dc.docnumber = '$docnumber'";

                $result_qty = $conn->query($daly_color_size_sql);
                while($row_qty = $result_qty->fetch_assoc()) {
                    $data_qty = $row_qty;
                   }

                $daly_color_size_op_sql = "SELECT sum(op.Qty) as opqty FROM eworker_operation as op where op.color = '$color' and op.size ='$size' and op.docnumber ='$docnumber'";
           
                $result_op_qty = $conn->query($daly_color_size_op_sql);
                while($row_op_qty = $result_op_qty->fetch_assoc()) {
                    $data_op_qty = $row_op_qty;
                   }
            if ( $data_op_qty >= $data_qty) {
                $response['success'] = false;
                $response['message'] = 'This color & size output Qty cant be more than input Qty';
                 // Convert the response array to JSON format
                 $json_response = json_encode($response);
                 $_SESSION['form_submitted'] = true;
                 // Send the JSON response back to the client
                 echo $json_response;
            }else{
                 
                $sql = "INSERT INTO eworker_operation (cardnumber, WorkOrder, Style, StepNumber, PartCode, ReworkReason, color, size, Qty, DateTime, Status, qty_type, SewingLine,docnumber)
                VALUES ('$CardNo', '$WorkOrder', '$Style', '$Step', '', '', '$color', '$size', '$qty', '$date', '$status','$qty_type','$SewingLine','$docnumber')";

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
            }
            } catch (PDOException $e) {
                echo $sql . "<br>" . $e->getMessage();
            }

        }else{
            $name = $_POST['Name'];
            $WorkOrder = $_POST['WorkOrder'];
            $Step = $_POST['Step'];
            $Style = $_POST['Style'];
            $CardNo = $_POST['CardNo'];
            $docnumber = $_POST['docnumber'];
            $SewingLine = $_POST['SewingLine'];
            $qty = $_POST['qty'];
            $qty_type = $_POST['qty_type'];
            $status = "A";
            $date = date('Y-m-d H:i:s'); // 'Y' should be uppercase for the full year

            try {
                $sql = "INSERT INTO eworker_operation (cardnumber, WorkOrder, Style, StepNumber, PartCode, ReworkReason, color, size, Qty, DateTime, Status, qty_type, SewingLine, docnumber)
                VALUES ('$CardNo', '$WorkOrder', '$Style', '$Step', '', '', '', '', '$qty', '$date', '$status','$qty_type','$SewingLine','$docnumber')";

                // use query() method for MySQLi
                if ($conn->query($sql) === TRUE) {
                
                        $response = array(
                            "success" => true,
                            "message" => "New record created successfully"
                        );
                    } else {
                        // If the operation failed
                        $response['success'] = false;
                        $response['message'] = 'Error creating new record';
                    }
                    
                    // Convert the response array to JSON format
                
                
                $json_response = json_encode($response);
                    
                // Send the JSON response back to the client
                echo $json_response;
                    $_SESSION['form_submitted'] = true;
            
            } catch (PDOException $e) {
                echo $sql . "<br>" . $e->getMessage();
            }
        }
    }
}


  
    
?>