<?php 



session_start(); 

ini_set('display_errors', 1);
error_reporting(E_ALL);


include 'header.php'; 
include 'db_connection.php';


$status = isset($_POST['cartItems']) ? $_POST['cartItems'] : [];

if ($status[0]['type'] == 'updateDocStatus') {

    try {
        $nptnumber =  $status[0]['nptnumber'];
        $linenumber =  $status[0]['linenumber'];
      

        $sql = "UPDATE ework_daily_npt SET status = 1 WHERE nptnumber = '$nptnumber' AND linenumber = $linenumber;";

        // print_r($sql);
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
}elseif ($status[0]['type'] == 'disappDocStatus') {

    try {
        $nptnumber =  $status[0]['nptnumber'];
        $linenumber =  $status[0]['linenumber'];
      

        $sql = "UPDATE ework_daily_npt SET status = 2 WHERE nptnumber = '$nptnumber' AND linenumber = $linenumber;";

        // print_r($sql);
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
}else{



        

    // Assuming $cartItems contains the data from your form
    $cartItems = isset($_POST['cartItems']) ? $_POST['cartItems'] : [];
    $line_number = 0;

        try {


            foreach ($cartItems as $cartItem) { 
                $line_number++;
                $linenumber   = $line_number;
                $WorkOrder    = $cartItem['WorkOrder'];
                $docdate      = $cartItem['docdate']; 
                $docnumber    = $cartItem['docnumber']; 
                // $nptnumber = $new_npt_number; 
                $npttype      = $cartItem['npttype'];
                $ework_id     = $cartItem['ework_id'];
                $site         = $cartItem['site'];
                $building     = $cartItem['building'];
                $floor        = $cartItem['floor'];
                $line         = $cartItem['line'];
                $Customer     = $cartItem['Customer'];
                $Style        = $cartItem['Style']; 
                $StepNumber   = $cartItem['StepNumber']; 
                $start_time   = $cartItem['start_time']; 
                $end_time     = $cartItem['end_time']; 
                $duration     = $cartItem['duration']; 
                $reason       = $cartItem['reason']; 
                $remarks      = $cartItem['remarks']; 
                
                $entrypersonbadge = $_SESSION['cardnumber'];



                $sql1 = "SELECT nptnumber FROM ework_daily_npt WHERE docnumber = '$docnumber' AND docdate = '$docdate'";
                $result1 = $conn->query($sql1);
                $row1 = $result1->fetch_assoc();
                $nptnumbert = $row1['nptnumber'] ?? '';

                if(empty($nptnumbert)){
                    $sql = "SELECT MAX(CAST(SUBSTRING(nptnumber, 5) AS UNSIGNED)) AS max_nptnum FROM ework_daily_npt";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $max_nptnum = $row['max_nptnum'];

                        // Check if max_nptnum is not null
                        if ($max_nptnum !== null) {
                            $new_npt_number = 'NPT-' . ($max_nptnum + 1);
                            $nptnumber = $new_npt_number;
                        } else {
                            $nptnumber = 'NPT-1';
                        }
                    } else {
                        $nptnumber = 'NPT-1';
                    }
                } else {
                    $nptnumber = $nptnumbert;
                }


                $sql_header = "SELECT * FROM `ework_daily_npt` where nptnumber = '$nptnumber' order by linenumber DESC LIMIT 1";
                $result_header = $conn->query($sql_header);
                $row_header = $result_header->fetch_assoc();

                $linenumber       = $row_header['linenumber']+1;
               



                
            $sql = "INSERT INTO `ework_daily_npt` (`WorkOrder`, `docdate`, `docnumber`,  `nptnumber`, `npttype`, `Customer`, `Style`, `linenumber`, `StepNumber`, `entrypersonbadge`, `line`, `site`,`building`,`floor`,`start_time`,`end_time`,`duration`,`reason`,`remarks`,`ework_id`) VALUES ('$WorkOrder', '$docdate', '$docnumber',  '$nptnumber', '$npttype', '$Customer', '$Style','$linenumber', '$StepNumber', '$entrypersonbadge', '$line', '$site','$building','$floor','$start_time','$end_time','$duration','$reason','$remarks','$ework_id')";


 

                    $response = array();


                    if (mysqli_query($conn, $sql)) {

                        $response['success'] = true;
                        $response['nptnumber'] = $nptnumber;

                    } else {
                        
                        $response['success'] = false;
                        $response['message'] = 'New record creation failed: ' . mysqli_error($conn);
                    }

                    // Clear any previous output to ensure only JSON is returned
                    ob_clean();

                    // Set header to JSON and output the response
                    header('Content-Type: application/json');
                    echo json_encode($response);



          
        }
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
}
// Move this outside of the loop
$conn->close(); 


?>