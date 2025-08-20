<?php include 'header.php'; session_start(); ?>
<?php include 'db_connection.php';

?>
<?php


// Assuming $cartItems contains the data from your form
$cartItems = isset($_POST['cartItems']) ? $_POST['cartItems'] : [];
$line_number = 0;

if ($cartItems[0]['type']  == 'add'){
    $sql = "Select MAX(docnumber) as docnumber from ework_partname where docnumber Like '%GP%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        // print_r($row);  
        $split_data =  str_split($row['docnumber'],3);
    
        $new_doc_number = 'GP-' . ($split_data[1] + 1);

    }
    
    } else {
    echo "0 results";
    }


    try {
		

        foreach ($cartItems as $cartItem) { 
            $line_number++;
            $PartName = $cartItem['Part'];
            $WorkOrder = $cartItem['WorkOrder'];
            $WO_docnumber = $cartItem['docnumber'];
            $docdate = $cartItem['docdate']; 
            $quantity = $cartItem['quantity'];
            $Customer = $cartItem['Customer'];
            $Style = $cartItem['Style']; 
            $StyleDescription = $cartItem['StyleDescription']; 
            $line = $cartItem['line']; 
            $linenumber = $line_number+1;
            $docstatus = 0;
            $entrypersonbadge = $_SESSION['cardnumber'];
            $username = $_SESSION['cardnumber'];
            $entrypersonname =$_SESSION['cardnumber'];
            $doccreationtime =  date('Y-m-d H:i:s');
            $lineentrytime =  date('Y-m-d H:i:s');
            $docnumber = $new_doc_number;
            $linestatus = 1;
            
        $sql = "INSERT INTO `ework_partname` (`WorkOrder`,`docdate`,`Style`,`line`,`linenumber`,`docstatus`,`entrypersonbadge`,`entrypersonname`,`doccreationtime`,`docnumber`,`username`,`lineentrytime`,`WO_docnumber`,`PartName`)
         VALUES ('$WorkOrder','$docdate','$Style','$line','$linenumber','$docstatus','$entrypersonbadge','$entrypersonname','$doccreationtime','$docnumber','$username','$lineentrytime','$WO_docnumber','$PartName')";


            if ($conn->query($sql) === TRUE) {
                // If the operation was successful
                $response['success'] = true;
                $response['message'] = 'New record created successfully';
            } else {
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
     
        $sql = "UPDATE ework_sales_order SET GarmentParts = 'Yes', docstatus = '5' WHERE docnumber = '$docnumbers';";

        
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
}elseif($cartItems[0]['type']  == 'update'){

    $doc =  $cartItems[0]['docnumber'];

    $sql = "Select MAX(linenumber) as linenumber from ework_partname where docnumber= '$doc' limit 1";

    
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
            $PartName = $cartItem['Part'];
            $WorkOrder = $cartItem['WorkOrder'];
            $WO_docnumber = $cartItem['docnumber'];
            $docdate = $cartItem['docdate']; 
            $Customer = $cartItem['Customer'];
            $Style = $cartItem['Style']; 
            $line = $cartItem['line']; 
            $linenumber = $line_number+1;
            $docstatus = 0;
            $entrypersonbadge = $_SESSION['cardnumber'];
            $username = $_SESSION['cardnumber'];
            $entrypersonname = $_SESSION['cardnumber'];
            $doccreationtime =  date('Y-m-d H:i:s');
            $lineentrytime =  date('Y-m-d H:i:s');
            $docnumber = $new_doc_number;
            $linestatus = 1;
            
        $sql = "INSERT INTO `ework_partname` (`WorkOrder`,`docdate`,`Style`,`line`,`linenumber`,`docstatus`,`entrypersonbadge`,`entrypersonname`,`doccreationtime`,`docnumber`,`username`,`lineentrytime`,
        `Customer`,`WO_docnumber`,`PartName`)
         VALUES ('$WorkOrder','$docdate','$Style','$line','$linenumber','$docstatus','$entrypersonbadge','$entrypersonname','$doccreationtime','$docnumber','$username','$lineentrytime','$Customer','$WO_docnumber','$PartName')";

            if ($conn->query($sql) === TRUE) {
                // If the operation was successful
                $response['success'] = true;
                $response['message'] = 'New record created successfully';
            } else {
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
    
    try {
        
            
        $idlines =  $cartItems[0]['idlines'];
        $PartName =  $cartItems[0]['Part'];

    
        $sql = "UPDATE ework_partname SET PartName = '$PartName' WHERE idlines = '$idlines';";

     
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

    
    
    
        $sql = " DELETE FROM ework_partname  WHERE idlines = $idlines;";

           
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