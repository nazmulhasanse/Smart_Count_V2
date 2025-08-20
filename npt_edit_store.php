<?php 
	include 'header.php'; 
	session_start();
	include 'db_connection.php'; 
?>
<?php
	$cartItems = isset($_POST['cartItems']) ? $_POST['cartItems'] : [];
	if($cartItems[0]['type']  == 'edit'){
			try {
		$idlines       =  $cartItems[0]['idlines'];
		$lg_StepNumber =  $cartItems[0]['lg_StepNumber'];
		$start_time    =  $cartItems[0]['start_time'];
		$end_time      =  $cartItems[0]['end_time'];
		$duration      =  $cartItems[0]['duration'];
		$reason        =  $cartItems[0]['reason'];
		$remarks       =  $cartItems[0]['remarks'];
		

	        $sql = "UPDATE `ework_daily_npt`
			SET `lg_StepNumber` = '$lg_StepNumber',
			    `start_time` = '$start_time',
			    `end_time` = '$end_time',
			    `duration` = '$duration',
			    `reason` = '$reason',
			    `remarks` = '$remarks'
			WHERE `idlines` = '$idlines'";

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
	}elseif($cartItems[0]['type']  == 'delete'){
	    try {
	        $idlines =  $cartItems[0]['idlines'];
	        $sql = " DELETE FROM ework_daily_npt  WHERE idlines = $idlines;";
	           
	            if ($conn->query($sql) === TRUE) {
	                // If the operation was successful
	                $response['success'] = true;
	                $response['message'] = 'Delete successfully Done';
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
    }elseif($cartItems[0]['type']  == 'updateActionPlan'){
	    try {
			$idlines     =  $cartItems[0]['idlines'];
			$action_plan =  $cartItems[0]['actionPlan'];
			$applyToAll  =  $cartItems[0]['applyToAll'];

			if($applyToAll == '1'){

				$query     = "SELECT nptnumber, reason FROM ework_daily_npt WHERE idlines = '$idlines'";
				$result    = $conn->query($query);
				$row       = $result->fetch_assoc();

				$nptnumber = $row['nptnumber'];
				$reason    = $row['reason'];

				$sql = "UPDATE ework_daily_npt SET action_plan = '$action_plan' WHERE nptnumber = '$nptnumber' AND reason = '$reason'";

			} else {

	        	$sql = "UPDATE ework_daily_npt SET action_plan = '$action_plan' WHERE idlines = '$idlines'";

			}  
	           
	            if ($conn->query($sql) === TRUE) {
	                // If the operation was successful
	                $response['success'] = true;
	                $response['message'] = 'Update successfully Done';
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
    }elseif($cartItems[0]['type']  == 'updateStatus'){
	    try {
	        $nptnumber =  $cartItems[0]['nptnumber'];
	        $sql = "UPDATE ework_daily_npt SET status = '1' WHERE nptnumber = '$nptnumber'";
	           
	            if ($conn->query($sql) === TRUE) {
	                // If the operation was successful
	                $response['success'] = true;
	                $response['message'] = 'Update successfully Done';
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
    }elseif($cartItems[0]['type']  == 'disapproveStatus'){
	    try {
	        $nptnumber =  $cartItems[0]['nptnumber'];
	        $sql = "UPDATE ework_daily_npt SET status = '' WHERE nptnumber = '$nptnumber'";
	           
	            if ($conn->query($sql) === TRUE) {
	                // If the operation was successful
	                $response['success'] = true;
	                $response['message'] = 'Update successfully Done';
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
    }elseif($cartItems[0]['type']  == 'addline'){
	    try {
	        $nptnumber =  $cartItems[0]['nptnumber'];
	        $sql_header = "SELECT * FROM `ework_daily_npt` where nptnumber = '$nptnumber' order by linenumber DESC LIMIT 1";
			$result_header = $conn->query($sql_header);
			$row_header = $result_header->fetch_assoc();

			$linenumber       = $row_header['linenumber']+1;
			$docnumber        = $row_header['docnumber'];
			$status           = '';
			$entrypersonbadge = $_SESSION['cardnumber'];
			$WorkOrder        = $cartItems[0]['WorkOrder'];
			$docdate          = $cartItems[0]['docdate']; 
			$site             = $cartItems[0]['site'];
			$building         = $cartItems[0]['building'];
			$floor            = $cartItems[0]['floor'];
			$line             = $cartItems[0]['line'];
			$Customer         = $cartItems[0]['Customer'];
			$Style            = $cartItems[0]['Style']; 
			$StepNumber       = $cartItems[0]['StepNumber']; 
			$start_time       = $cartItems[0]['start_time']; 
			$end_time         = $cartItems[0]['end_time']; 
			$duration         = $cartItems[0]['duration']; 
			$reason           = $cartItems[0]['reason']; 
			$remarks          = $cartItems[0]['remarks']; 
			$npttype          = $cartItems[0]['npttype']; 
			$ework_id         = $cartItems[0]['ework_id']; 


	        $sql = "INSERT INTO `ework_daily_npt` (`WorkOrder`, `docdate`, `docnumber`,  `nptnumber`, `npttype`, `Customer`, `Style`, `linenumber`, `StepNumber`, `entrypersonbadge`, `line`, `site`,`building`,`floor`,`start_time`,`end_time`,`duration`,`reason`,`remarks`,`status`,`ework_id`) VALUES ('$WorkOrder', '$docdate', '$docnumber',  '$nptnumber', '$npttype', '$Customer', '$Style','$linenumber', '$StepNumber', '$entrypersonbadge', '$line', '$site','$building','$floor','$start_time','$end_time','$duration','$reason','$remarks','$status','$ework_id')";
	           
	            if ($conn->query($sql) === TRUE) {
	                // If the operation was successful
	                $response['success'] = true;
	                $response['message'] = 'Line Successfully Inserted';
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
    }elseif($cartItems[0]['type']  == 'addlines'){

      foreach ($cartItems as $cartItem) {
	    try {
	        $nptnumber =  $cartItem['nptnumber'];
	        $sql_header = "SELECT * FROM `ework_daily_npt` where nptnumber = '$nptnumber' order by linenumber DESC LIMIT 1";
			$result_header = $conn->query($sql_header);
			$row_header = $result_header->fetch_assoc();

			$linenumber       = $row_header['linenumber']+1;
			$docnumber        = $row_header['docnumber'];
			$status           = '';
			$entrypersonbadge = $_SESSION['cardnumber'];
			$WorkOrder        = $cartItem['WorkOrder'];
			$docdate          = $cartItem['docdate']; 
			$site             = $cartItem['site'];
			$building         = $cartItem['building'];
			$floor            = $cartItem['floor'];
			$line             = $cartItem['line'];
			$Customer         = $cartItem['Customer'];
			$Style            = $cartItem['Style']; 
			$StepNumber       = $cartItem['StepNumber']; 
			$start_time       = $cartItem['start_time']; 
			$end_time         = $cartItem['end_time']; 
			$duration         = $cartItem['duration']; 
			$reason           = $cartItem['reason']; 
			$remarks          = $cartItem['remarks']; 
			$npttype          = $cartItem['npttype']; 
			$ework_id         = $cartItem['ework_id']; 


	        $sql = "INSERT INTO `ework_daily_npt` (`WorkOrder`, `docdate`, `docnumber`,  `nptnumber`, `npttype`, `Customer`, `Style`, `linenumber`, `StepNumber`, `entrypersonbadge`, `line`, `site`,`building`,`floor`,`start_time`,`end_time`,`duration`,`reason`,`remarks`,`status`,`ework_id`) VALUES ('$WorkOrder', '$docdate', '$docnumber',  '$nptnumber', '$npttype', '$Customer', '$Style','$linenumber', '$StepNumber', '$entrypersonbadge', '$line', '$site','$building','$floor','$start_time','$end_time','$duration','$reason','$remarks','$status','$ework_id')";
	           
            if ($conn->query($sql) === TRUE) {
                // If the operation was successful
                $response['success'] = true;
                $response['message'] = 'Line Successfully Inserted';
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
}
?>