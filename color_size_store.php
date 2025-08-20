<?php include 'header.php'; session_start(); ?>
<?php include 'db_connection.php';

?>
<?php

$cartItems = isset($_POST['cartItems']) ? $_POST['cartItems'] : [];
$line_number = 0;
if ($cartItems[0]['type']  == 'update') {
    
    $doc =  $cartItems[0]['docnumber'];

    $sql = "Select MAX(linenumber) as linenumber from ework_order_wise_color_size_qty where docnumber= '$doc' limit 1";

    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        // print_r($row);  
        $line_number =  $row['linenumber'];

    }
    
    } else {
    echo "0 results";
    }



        try {


            foreach ($cartItems as $cartItem) { 
                $line_number++;
                $size = $cartItem['size'];
                $color = $cartItem['color'];
                $qty = $cartItem['color_size_Quantity'];
                $CustomerPO = $cartItem['CustomerPO'];
                $WorkOrder = $cartItem['WorkOrder'];
                $WO_docnumber = $cartItem['WO_docnumber'];
                $docdate = $cartItem['docdate']; 
                $quantity = $cartItem['quantity'];
                $Customer = $cartItem['Customer'];
                $Style = $cartItem['Style']; 
                $StyleDescription = $cartItem['StyleDescription']; 
                $linenumber = $line_number;
                $docstatus = 0;
                $entrypersonbadge = $_SESSION['cardnumber'];
                $username = $_SESSION['cardnumber'];
                $entrypersonname = 'test';
                $doccreationtime =  date('Y-m-d H:i:s');
                $lineentrytime =  date('Y-m-d H:i:s');
                $docnumber =  $cartItem['docnumber'];;
                $linestatus = 1;
                
            $sql = "INSERT INTO `ework_order_wise_color_size_qty` (`WorkOrder`,`docdate`,`quantity`,`Style`,`linenumber`,`docstatus`,`entrypersonbadge`,`entrypersonname`,`doccreationtime`,`docnumber`,`username`,`linestatus`,`lineentrytime`,
            `color`,`size`,`qty`,`Customer`,`StyleDescription`,`WO_docnumber`,`CustomerPO`)
            VALUES ('$WorkOrder','$docdate','$quantity','$Style','$linenumber','$docstatus','$entrypersonbadge','$entrypersonname','$doccreationtime','$docnumber','$username','$linestatus','$lineentrytime','$color',
            '$size','$qty','$Customer', '$StyleDescription','$WO_docnumber','$CustomerPO')";

            
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
                // Send the JSON response back to the client
                echo $json_response;
        
        }
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }


        try {
            
            $doc_number =  $cartItems[0]['docnumber'];

            $sewing_line_data = [];
                
            $sql = "SELECT docnumber FROM `ework_sales_order` where docnumber = '$doc_number'";
            $result = $conn->query($sql);
    
            if ($result->num_rows > 0) {
            
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $sewing_line_data[] =  $row;
            }
            } else {
            echo "0 results";
            }
            foreach($sewing_line_data as $value){
                $docnumbers = $value['docnumber'];
        
            $sql = "UPDATE ework_sales_order SET ColorSizeAssortment = 'Yes', docstatus = '5' WHERE docnumber = '$docnumbers';";
    
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

}elseif($cartItems[0]['type']  == 'add'){

    $sql = "Select MAX(docnumber) as docnumber from ework_order_wise_color_size_qty where docnumber Like '%CS%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        // print_r($row);  
        $split_data =  str_split($row['docnumber'],3);
    
        $new_doc_number = 'CS-' . ($split_data[1] + 1);

    }
    
    } else {
    echo "0 results";
    }

        try {


            foreach ($cartItems as $cartItem) { 
                $line_number++;
                $size = $cartItem['size'];
                $color = $cartItem['color'];
                $qty = $cartItem['color_size_Quantity'];
                $CustomerPO = $cartItem['CustomerPO'];
                $WorkOrder = $cartItem['WorkOrder'];
                $WO_docnumber = $cartItem['docnumber'];
                $docdate = $cartItem['docdate']; 
                $quantity = $cartItem['quantity'];
                $Customer = $cartItem['Customer'];
                $Style = $cartItem['Style']; 
                $StyleDescription = $cartItem['StyleDescription']; 
                $linenumber = $line_number;
                $docstatus = 0;
                $entrypersonbadge = $_SESSION['cardnumber'];
                $username = $_SESSION['cardnumber'];
                $entrypersonname = 'test';
                $doccreationtime =  date('Y-m-d H:i:s');
                $lineentrytime =  date('Y-m-d H:i:s');
                $docnumber = $new_doc_number;
                $linestatus = 1;
                
            $sql = "INSERT INTO `ework_order_wise_color_size_qty` (`WorkOrder`,`docdate`,`quantity`,`Style`,`linenumber`,`docstatus`,`entrypersonbadge`,`entrypersonname`,`doccreationtime`,`docnumber`,`username`,`linestatus`,`lineentrytime`,
            `color`,`size`,`qty`,`Customer`,`StyleDescription`,`WO_docnumber`,`CustomerPO`)
            VALUES ('$WorkOrder','$docdate','$quantity','$Style','$linenumber','$docstatus','$entrypersonbadge','$entrypersonname','$doccreationtime','$docnumber','$username','$linestatus','$lineentrytime','$color',
            '$size','$qty','$Customer', '$StyleDescription','$WO_docnumber','$CustomerPO')";

            
                // use query() method for MySQLi
                if ($conn->query($sql) === TRUE) {
                    // If the operation was successful
                    $response['success'] = true;
                    $response['message'] = 'New record created successfully';
                    $response['docnumber'] = $new_doc_number;
                   
                } else {
                    // If the operation failed
                    $response['success'] = false;
                    $response['message'] = 'Error creating new record';
                }
                
                // Convert the response array to JSON format
                $json_response = json_encode($response);
                // Send the JSON response back to the client
                echo $json_response;
        
        }
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }


        try {
            
            $doc_number =  $cartItems[0]['docnumber'];

        
                
            $sql = "SELECT docnumber FROM `ework_sales_order` where docnumber = '$doc_number'";
            $result = $conn->query($sql);
    
            if ($result->num_rows > 0) {
                $sewing_line_data = [];
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $sewing_line_data[] =  $row;
            }
            } else {
            echo "0 results";
            }
            foreach($sewing_line_data as $value){
                $docnumbers = $value['docnumber'];
        
            $sql = "UPDATE ework_sales_order SET ColorSizeAssortment = 'Yes', docstatus = '5' WHERE docnumber = '$docnumbers';";
    
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
}elseif($cartItems[0]['type']  == 'edit'){
    // UPDATE `ework_order_wise_color_size_qty` SET `size` = '30' WHERE `ework_order_wise_color_size_qty`.`idlines` = 39;
    try {
        
            
        $idlines =  $cartItems[0]['idlines'];
        $color =  $cartItems[0]['color'];
        $size =  $cartItems[0]['size'];
        $quantity =  $cartItems[0]['quantity'];
        $CustomerPO =  $cartItems[0]['CustomerPO'];

    
    
    
        $sql = "UPDATE ework_order_wise_color_size_qty SET color = '$color',size = '$size',qty = '$quantity',CustomerPO = '$CustomerPO' WHERE idlines = '$idlines';";

    
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


}elseif($cartItems[0]['type']  == 'delete'){
    try {
        
            
        $idlines =  $cartItems[0]['idlines'];

    
    
    
        $sql = " DELETE FROM ework_order_wise_color_size_qty  WHERE idlines = $idlines;";

           
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
    }
// Move this outside of the loop
$conn->close(); 


?>