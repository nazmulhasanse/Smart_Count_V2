<?php
session_start();
header('Content-Type: application/json');
include './ie/db_connection.php';
date_default_timezone_set('Asia/Dhaka');
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($_POST['npt_type'] == 'end') {
        // Create DateTime objects
        $startDateTime = new DateTime($_POST['start_time']);
        $endDateTime = new DateTime($_POST['end_time']);

        // Calculate the interval
        $interval = $startDateTime->diff($endDateTime);

        // Convert the interval to total minutes
        $totalMinutes = ($interval->h * 60) + $interval->i;

        // Output the result
        $end_time =  $_POST['end_time'];
        $idlines =  $_POST['idlines'];
        $reason =  $_POST['reason'];
        $cardnumber =  $_POST['cardnumber'];
        try {
        $sql = "UPDATE ework_daily_npt SET end_time = '$end_time', reason = '$reason', duration = '$totalMinutes' WHERE idlines =  '$idlines' and cardnumber = '$cardnumber'";
           
          
            if ($conn->query($sql) === TRUE) {
                // Get the last inserted ID
                unset($_SESSION['npt_start_time']);
                // Return a JSON response indicating success
                echo json_encode(["status" => "success", "message" => "New record created successfully"]);
            } else {
                // Return a JSON response indicating error
                echo json_encode(["status" => "error", "message" => "Error: " . $sql . " - " . $conn->error]);
            }
            } catch (PDOException $e) {
                echo $sql . "<br>" . $e->getMessage();
            }

    }else{

  
    $WorkOrder =  $_POST['WorkOrder'];
    $Style = $_POST['Style'];
    $site = $_POST['site'];
    $building = $_POST['building'];
    $Customer =$_POST['Customer'];
    $floor =$_POST['floor'];
    $line = $_POST['line'];
    $npttype = $_POST['npttype'];
    $start_time = $_POST['start_time'];
    $docdate = $_POST['docdate'];
    $cardnumber = $_POST['cardnumber'];
    $docnumber = $_POST['docnumber'];
    // $reason = $_POST['reason'];
    $StepNumber = $_POST['StepNumber'];

    $sql1 = "SELECT nptnumber FROM ework_daily_npt WHERE docdate = '$docdate' and docnumber ='$docnumber'";
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

                $sql_header = "SELECT * FROM ework_daily_npt where nptnumber = '$nptnumber' order by linenumber DESC LIMIT 1";
                $result_header = $conn->query($sql_header);
                $row_header = $result_header->fetch_assoc();
    
                $linenumber       = $row_header['linenumber']+1;

            try {
                $sql = "INSERT INTO ework_daily_npt (`WorkOrder`, docdate, docnumber,  nptnumber, npttype, Customer, Style, linenumber, StepNumber, entrypersonbadge, line, `site`,`building`,`floor`,`status`,`action_plan`,`start_time`,`end_time`,`duration`,`reason`,`remarks`,`ework_id`,`cardnumber`) 
                VALUES ('$WorkOrder', '$docdate', '$docnumber',  '$nptnumber', '$npttype', '$Customer', '$Style','$linenumber', '$StepNumber', '', '$line', '$site','$building','$floor','','','$start_time','','','','','','$cardnumber')";
                    if ($conn->query($sql) === TRUE) {
                        // Get the last inserted ID
                        $last_id = $conn->insert_id;
                        $_SESSION['npt_start_time']= $_POST['start_time'];
                        // Return a JSON response indicating success
                        echo json_encode(["status" => "success", "message" => "New record created successfully", "last_id" => $last_id]);
                    } else {
                        // Return a JSON response indicating error
                        echo json_encode(["status" => "error", "message" => "Error: " . $sql . " - " . $conn->error]);
                    }
                    } catch (PDOException $e) {
                        echo $sql . "<br>" . $e->getMessage();
                    }
        }
    }